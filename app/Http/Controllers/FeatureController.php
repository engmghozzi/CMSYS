<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $resource = $request->get('resource');
        $isActive = $request->get('is_active');

        $query = Feature::with(['users', 'roles']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($resource) {
            $query->where('resource', $resource);
        }

        if ($isActive !== null && $isActive !== 'all') {
            $query->where('is_active', $isActive);
        }

        $features = $query->latest()->paginate(10)->withQueryString();
        $categories = Feature::distinct()->pluck('category')->sort();
        $resources = Feature::distinct()->pluck('resource')->sort();

        return view('pages.features.index', compact('features', 'categories', 'resources'));
    }

    public function create()
    {
        $categories = Feature::distinct()->pluck('category')->sort();
        $resources = Feature::distinct()->pluck('resource')->sort();
        $actions = ['create', 'read', 'update', 'delete', 'manage'];
        
        return view('pages.features.create', compact('categories', 'resources', 'actions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:features,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'action' => 'nullable|string|max:255',
            'resource' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Feature::create($validated);

        return redirect()->route('features.index')
            ->with('success', 'Feature created successfully');
    }

    public function show(Feature $feature)
    {
        $feature->load(['users', 'roles']);
        $usageStats = $feature->getUsageStats();
        $assignedUsers = $feature->getAssignedUsers();
        $assignedRoles = $feature->getAssignedRoles();
        
        return view('pages.features.show', compact('feature', 'usageStats', 'assignedUsers', 'assignedRoles'));
    }

    public function edit(Feature $feature)
    {
        $categories = Feature::distinct()->pluck('category')->sort();
        $resources = Feature::distinct()->pluck('resource')->sort();
        $actions = ['create', 'read', 'update', 'delete', 'manage'];
        
        return view('pages.features.edit', compact('feature', 'categories', 'resources', 'actions'));
    }

    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:features,name,' . $feature->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'action' => 'nullable|string|max:255',
            'resource' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $feature->update($validated);

        return redirect()->route('features.show', $feature)
            ->with('success', 'Feature updated successfully');
    }

    public function destroy(Feature $feature)
    {
        // Check if feature is assigned to any users or roles
        if (!$feature->canBeDeleted()) {
            return redirect()->route('features.index')
                ->with('error', 'Cannot delete feature that is assigned to users or roles');
        }

        $feature->delete();

        return redirect()->route('features.index')
            ->with('success', 'Feature deleted successfully');
    }

    // Additional methods for feature management
    public function usage(Feature $feature)
    {
        $usageStats = $feature->getUsageStats();
        $assignedUsers = $feature->getAssignedUsers();
        $assignedRoles = $feature->getAssignedRoles();
        
        return view('pages.features.usage', compact('feature', 'usageStats', 'assignedUsers', 'assignedRoles'));
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'features' => 'required|array',
            'features.*' => 'exists:features,id',
            'action' => 'required|in:activate,deactivate,delete',
        ]);

        $features = Feature::whereIn('id', $validated['features'])->get();

        DB::transaction(function () use ($features, $validated) {
            foreach ($features as $feature) {
                switch ($validated['action']) {
                    case 'activate':
                        $feature->update(['is_active' => true]);
                        break;
                    case 'deactivate':
                        $feature->update(['is_active' => false]);
                        break;
                    case 'delete':
                        if ($feature->canBeDeleted()) {
                            $feature->delete();
                        }
                        break;
                }
            }
        });

        return redirect()->route('features.index')
            ->with('success', 'Features updated successfully');
    }

    public function categories()
    {
        $categories = Feature::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(function ($category) {
                $count = Feature::where('category', $category)->count();
                return [
                    'name' => $category,
                    'display_name' => ucfirst(str_replace('_', ' ', $category)),
                    'count' => $count
                ];
            });

        return view('pages.features.categories', compact('categories'));
    }

    public function generateFromResources()
    {
        $resources = [
            'users' => 'Users',
            'roles' => 'Roles', 
            'features' => 'Features',
            'clients' => 'Clients',
            'contracts' => 'Contracts',
            'payments' => 'Payments',
            'machines' => 'Machines',
            'addresses' => 'Addresses',
            'reports' => 'Reports',
            'logs' => 'Logs',
            'settings' => 'Settings'
        ];

        $actions = ['create', 'read', 'update', 'delete', 'manage'];

        $generated = 0;
        foreach ($resources as $resource => $displayName) {
            foreach ($actions as $action) {
                $name = "{$action}.{$resource}";
                $displayName = "{$action} {$displayName}";
                
                if (!Feature::where('name', $name)->exists()) {
                    Feature::create([
                        'name' => $name,
                        'display_name' => $displayName,
                        'category' => $resource,
                        'action' => $action,
                        'resource' => $resource,
                        'is_active' => true
                    ]);
                    $generated++;
                }
            }
        }

        return redirect()->route('features.index')
            ->with('success', "Generated {$generated} new features");
    }
} 