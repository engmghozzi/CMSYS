<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $isActive = $request->get('is_active');
        $sort = $request->get('sort', 'name');
        $order = $request->get('order', 'asc');

        $query = Role::with(['users', 'features']);

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(name) LIKE ?', [strtolower($searchTerm)])
                  ->orWhereRaw('LOWER(display_name) LIKE ?', [strtolower($searchTerm)])
                  ->orWhereRaw('LOWER(description) LIKE ?', [strtolower($searchTerm)]);
            });
        }

        if ($category) {
            $query->whereHas('features', function ($q) use ($category) {
                $q->where('category', $category);
            });
        }

        if ($isActive !== null && $isActive !== 'all') {
            $query->where('is_active', $isActive);
        }

        // Handle sorting
        switch ($sort) {
            case 'name':
                $query->orderBy('name', $order);
                break;
            case 'display_name':
                $query->orderBy('display_name', $order);
                break;
            case 'created_at':
                $query->orderBy('created_at', $order);
                break;
            case 'users_count':
                $query->withCount('users')->orderBy('users_count', $order);
                break;
            case 'features_count':
                $query->withCount('features')->orderBy('features_count', $order);
                break;
            default:
                $query->orderBy('name', $order);
        }

        // If not sorting by counts, add the counts for display
        if (!in_array($sort, ['users_count', 'features_count'])) {
            $query->withCount(['users', 'features']);
        }

        $roles = $query->paginate(10)->withQueryString();
        $categories = Feature::distinct()->pluck('category')->sort();

        return view('pages.roles.index', compact('roles', 'categories'));
    }

    public function create()
    {
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        
        return view('pages.roles.create', compact('features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string',
            'is_active' => 'sometimes|boolean',
            'features' => 'sometimes|array',
            'features.*' => 'exists:features,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated, $request) {
            $role = Role::create($validated);

            // Handle feature assignments
            $allFeatures = Feature::all();
            $selectedFeatures = $request->get('features', []);
            
            foreach ($allFeatures as $feature) {
                if (in_array($feature->id, $selectedFeatures)) {
                    $role->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
                } else {
                    $role->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
                }
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $role->load(['users', 'features']);
        $effectivePermissions = $role->getEffectivePermissions();
        $allPermissions = $role->getAllPermissions();
        $assignedUsers = $role->users()->with('features')->get();
        
        return view('pages.roles.show', compact('role', 'effectivePermissions', 'allPermissions', 'assignedUsers'));
    }

    public function edit(Role $role)
    {
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        $roleFeatures = $role->features()->wherePivot('is_granted', true)->get()->pluck('id')->toArray();
        $effectivePermissions = $role->getEffectivePermissions();
        
        return view('pages.roles.edit', compact('role', 'features', 'roleFeatures', 'effectivePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'sometimes|array',
            'permissions.*' => 'string',
            'is_active' => 'sometimes|boolean',
            'features' => 'sometimes|array',
            'features.*' => 'exists:features,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($role, $validated, $request) {
            $role->update($validated);

            // Handle feature assignments
            $allFeatures = Feature::all();
            $selectedFeatures = $request->get('features', []);
            
            foreach ($allFeatures as $feature) {
                if (in_array($feature->id, $selectedFeatures)) {
                    $role->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
                } else {
                    $role->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
                }
            }
        });

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        // Log the deletion attempt for debugging
        \Log::info('Role deletion attempted', [
            'role_id' => $role->id,
            'role_name' => $role->name,
            'user_count' => $role->users()->count(),
            'can_be_deleted' => $role->canBeDeleted()
        ]);

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role that has assigned users. Please reassign users to another role first.');
        }

        // Check if role can be deleted using the model method
        if (!$role->canBeDeleted()) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete this role. It may have assigned users or be a system role.');
        }

        try {
            // Delete role features first
            $role->features()->detach();
            
            // Delete the role
            $role->delete();

            \Log::info('Role deleted successfully', [
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);

            return redirect()->route('roles.index')
                ->with('success', 'Role deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Error deleting role', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('roles.index')
                ->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }

    // Additional methods for role management
    public function permissions(Role $role)
    {
        $effectivePermissions = $role->getEffectivePermissions();
        $allPermissions = $role->getAllPermissions();
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        
        return view('pages.roles.permissions', compact('role', 'effectivePermissions', 'allPermissions', 'features'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:features,id',
        ]);

        $selectedPermissions = $request->get('permissions', []);
        $role->syncPermissions($selectedPermissions);

        return redirect()->route('roles.permissions', $role)
            ->with('success', 'Role permissions updated successfully');
    }

    public function users(Role $role)
    {
        $users = $role->users()->with('features')->paginate(10);
        
        return view('pages.roles.users', compact('role', 'users'));
    }


} 