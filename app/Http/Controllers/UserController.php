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

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($email) {
            $query->where('email', 'like', "%{$email}%");
        }

        // Filter by is_active if provided and not 'all'
        if ($isActive !== null && $isActive !== 'all') {
            $query->where('is_active', $isActive);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::active()->get();
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

        $user = User::create($validated);

        // Handle feature assignments
        $allFeatures = \App\Models\Feature::all();
        $selectedFeatures = $request->get('features', []);
        
        foreach ($allFeatures as $feature) {
            if (in_array($feature->id, $selectedFeatures)) {
                // Feature is selected - grant it
                $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
            } else {
                // Feature is not selected - explicitly revoke it
                $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user, Request $request)
    {
        $editing = $request->has('edit');
        return view('pages.users.show', compact('user', 'editing'));
    }

    public function edit(User $user)
    {
        $roles = Role::active()->get();
        $features = Feature::active()->orderBy('category')->orderBy('display_name')->get();
        $userFeatures = $user->features()->wherePivot('is_granted', true)->pluck('features.id')->toArray();
        
        return view('pages.users.edit', compact('user', 'roles', 'features', 'userFeatures'));
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

        $user->update($validated);

        // Handle feature assignments
        $allFeatures = \App\Models\Feature::all();
        $selectedFeatures = $request->get('features', []);
        
        foreach ($allFeatures as $feature) {
            if (in_array($feature->id, $selectedFeatures)) {
                // Feature is selected - grant it
                $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => true]]);
            } else {
                // Feature is not selected - explicitly revoke it
                $user->features()->syncWithoutDetaching([$feature->id => ['is_granted' => false]]);
            }
        }

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
}
