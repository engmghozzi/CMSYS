<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Feature;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        $search = $request->get('search');
        $email = $request->get('email');
        $isActive = $request->get('is_active');
        $role = $request->get('role');

        $query = User::with('role');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($email) {
            $query->where('email', 'like', "%{$email}%");
        }

        if ($isActive !== null && $isActive !== 'all') {
            $query->where('is_active', $isActive);
        }

        if ($role) {
            $query->whereHas('role', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::active()->get();

        return view('pages.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::active()->with('features')->get();
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        
        return view('pages.users.create', compact('roles', 'features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'sometimes|boolean',
            'features' => 'sometimes|array',
            'features.*' => 'exists:features,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated, $request) {
            $user = User::create($validated);

            // Get the selected role and its granted features
            $role = Role::with('features')->find($validated['role_id']);
            $roleGrantedFeatures = $role->features()->wherePivot('is_granted', true)->pluck('features.id')->toArray();
            
            // Get additional features selected by user
            $additionalFeatures = $request->get('features', []);
            
            // Get all features to handle the complete permission set
            $allFeatures = Feature::all();
            
            foreach ($allFeatures as $feature) {
                $isRoleGranted = in_array($feature->id, $roleGrantedFeatures);
                $isUserGranted = in_array($feature->id, $additionalFeatures);
                
                if ($isRoleGranted || $isUserGranted) {
                    // Grant feature if it's part of role OR user explicitly selected it
                    $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
                } else {
                    // Explicitly revoke feature if it's not granted by role and not selected by user
                    $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
                }
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user, Request $request)
    {
        $editing = $request->has('edit');
        $effectivePermissions = $user->getEffectivePermissions();
        $allPermissions = $user->getAllPermissions();
        
        return view('pages.users.show', compact('user', 'editing', 'effectivePermissions', 'allPermissions'));
    }

    public function edit(User $user)
    {
        $roles = Role::active()->with('features')->get();
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        $userFeatures = $user->features()->wherePivot('is_granted', true)->get()->pluck('id')->toArray();
        $userGrantedFeatures = $user->features()->wherePivot('is_granted', true)->get();
        $userRevokedFeatures = $user->features()->wherePivot('is_granted', false)->get();
        $effectivePermissions = $user->getEffectivePermissions();
        
        // Get current role's granted features for comparison
        $currentRole = $user->role;
        $roleGrantedFeatures = $currentRole ? $currentRole->features()->wherePivot('is_granted', true)->get() : collect();
        
        return view('pages.users.edit', compact('user', 'roles', 'features', 'userFeatures', 'userGrantedFeatures', 'userRevokedFeatures', 'effectivePermissions', 'roleGrantedFeatures'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'sometimes|boolean',
            'features' => 'sometimes|array',
            'features.*' => 'exists:features,id',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($user, $validated, $request) {
            $user->update($validated);

            // Get the selected role and its granted features
            $role = Role::with('features')->find($validated['role_id']);
            $roleGrantedFeatures = $role->features()->wherePivot('is_granted', true)->pluck('features.id')->toArray();
            
            // Get additional features selected by user
            $additionalFeatures = $request->get('features', []);
            
            // Get all features to handle the complete permission set
            $allFeatures = Feature::all();
            
            foreach ($allFeatures as $feature) {
                $isRoleGranted = in_array($feature->id, $roleGrantedFeatures);
                $isUserGranted = in_array($feature->id, $additionalFeatures);
                
                if ($isRoleGranted || $isUserGranted) {
                    // Grant feature if it's part of role OR user explicitly selected it
                    $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
                } else {
                    // Explicitly revoke feature if it's not granted by role and not selected by user
                    $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
                }
            }
        });

        return redirect()->route('users.show', $user->id)
            ->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    // Additional methods for permission management
    public function permissions(User $user)
    {
        $effectivePermissions = $user->getEffectivePermissions();
        $allPermissions = $user->getAllPermissions();
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        
        return view('pages.users.permissions', compact('user', 'effectivePermissions', 'allPermissions', 'features'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'sometimes|array',
            'permissions.*' => 'exists:features,id',
        ]);

        $selectedPermissions = $request->get('permissions', []);
        $user->syncPermissions($selectedPermissions);

        return redirect()->route('users.permissions', $user)
            ->with('success', 'User permissions updated successfully');
    }

    public function clearOverrides(User $user)
    {
        $user->clearPermissionOverrides();

        return redirect()->route('users.permissions', $user)
            ->with('success', 'User permission overrides cleared successfully');
    }


}
