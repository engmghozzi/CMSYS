<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FeatureController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Language routes
Route::get('lang/{lang}', [LanguageController::class, 'saveLanguage'])->name('settings.language');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// User routes with permissions
Route::middleware(['auth'])->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    
    
    Route::get('clients/{client}/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('clients/{client}/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('clients/{client}/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('clients/{client}/addresses/{address}', [AddressController::class, 'show'])->name('addresses.show');
    Route::get('clients/{client}/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('clients/{client}/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('clients/{client}/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
   
   
    Route::get('clients/{client}/contracts', [ContractController::class, 'globalindex'])->name('contracts.globalindex');
    Route::get('clients/{client}/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('clients/{client}/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('clients/{client}/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('clients/{client}/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('clients/{client}/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('clients/{client}/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
   
   
    Route::get('clients/{client}/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('clients/{client}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('clients/{client}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('clients/{client}/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('clients/{client}/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('clients/{client}/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('clients/{client}/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    
    
    // Contract-specific payment creation
    Route::get('clients/{client}/contracts/{contract}/payments/create', [PaymentController::class, 'createFromContract'])->name('payments.create.from.contract');
    Route::post('clients/{client}/contracts/{contract}/payments', [PaymentController::class, 'storeFromContract'])->name('payments.store.from.contract');



// Machine routes with permissions
    Route::get('clients/{client}/machines', [MachineController::class, 'index'])->name('machines.index');
    Route::get('clients/{client}/machines/create', [MachineController::class, 'create'])->name('machines.create');
    Route::post('clients/{client}/machines', [MachineController::class, 'store'])->name('machines.store');
    Route::get('clients/{client}/machines/{machine}', [MachineController::class, 'show'])->name('machines.show');
    Route::get('clients/{client}/machines/{machine}/edit', [MachineController::class, 'edit'])->name('machines.edit');
    Route::put('clients/{client}/machines/{machine}', [MachineController::class, 'update'])->name('machines.update');
    Route::delete('clients/{client}/machines/{machine}', [MachineController::class, 'destroy'])->name('machines.destroy');

// Contract-specific machine creation
    Route::get('clients/{client}/contracts/{contract}/machines/create', [MachineController::class, 'createFromContract'])->name('machines.create.from.contract');
    Route::post('clients/{client}/contracts/{contract}/machines', [MachineController::class, 'storeFromContract'])->name('machines.store.from.contract');

// Global routes with permissions
    Route::get('contracts', [ContractController::class, 'globalindex'])->name('contracts.globalindex');
    Route::get('payments', [PaymentController::class, 'globalindex'])->name('payments.globalindex');

// Role Management routes with permissions
    Route::resource('roles', RoleController::class);
    Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
    Route::get('roles/{role}/users', [RoleController::class, 'users'])->name('roles.users');


// Feature Management routes with permissions
    Route::resource('features', FeatureController::class);
    Route::get('features/{feature}/usage', [FeatureController::class, 'usage'])->name('features.usage');
    Route::post('features/bulk-update', [FeatureController::class, 'bulkUpdate'])->name('features.bulk-update');
    Route::get('features/categories', [FeatureController::class, 'categories'])->name('features.categories');
    Route::post('features/generate', [FeatureController::class, 'generateFromResources'])->name('features.generate');

// User Permission Management routes
    Route::get('users/{user}/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::put('users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::delete('users/{user}/permissions', [UserController::class, 'clearOverrides'])->name('users.permissions.clear');


    // Test route for role deletion debugging
    Route::get('test/role-delete/{role}', function($role) {
        $role = \App\Models\Role::findOrFail($role);
        return response()->json([
            'role_id' => $role->id,
            'role_name' => $role->name,
            'user_count' => $role->users()->count(),
            'can_be_deleted' => $role->canBeDeleted(),
            'has_features' => $role->features()->count()
        ]);
    })->name('test.role-delete');

    // Debug route for testing permissions
    Route::get('debug/permissions', function() {
        $user = auth()->user();
        if (!$user) {
            return 'Not authenticated';
        }
        
        $role = $user->role;
        $features = $user->features()->wherePivot('is_granted', true)->get();
        $roleFeatures = $role ? $role->features()->wherePivot('is_granted', true)->get() : collect();
        
        // Test all employee permissions
        $employeePermissions = [
            'clients.read', 'clients.create', 'clients.update',
            'contracts.read', 'contracts.create', 'contracts.update',
            'payments.read', 'payments.create', 'machines.read'
        ];
        
        $permissionResults = [];
        foreach ($employeePermissions as $permission) {
            $permissionResults[$permission] = $user->hasPermission($permission);
        }
        
        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'role' => $role ? $role->name : 'No role',
            'role_display_name' => $role ? $role->display_name : 'No role',
            'user_features' => $features->pluck('name')->toArray(),
            'role_features' => $roleFeatures->pluck('name')->toArray(),
            'employee_permissions' => $permissionResults,
            'all_permissions' => [
                'clients.read' => $user->hasPermission('clients.read'),
                'clients.create' => $user->hasPermission('clients.create'),
                'clients.update' => $user->hasPermission('clients.update'),
                'clients.delete' => $user->hasPermission('clients.delete'),
                'contracts.read' => $user->hasPermission('contracts.read'),
                'contracts.create' => $user->hasPermission('contracts.create'),
                'contracts.update' => $user->hasPermission('contracts.update'),
                'contracts.delete' => $user->hasPermission('contracts.delete'),
                'payments.read' => $user->hasPermission('payments.read'),
                'payments.create' => $user->hasPermission('payments.create'),
                'payments.update' => $user->hasPermission('payments.update'),
                'payments.delete' => $user->hasPermission('payments.delete'),
                'machines.read' => $user->hasPermission('machines.read'),
                'machines.create' => $user->hasPermission('machines.create'),
                'machines.update' => $user->hasPermission('machines.update'),
                'machines.delete' => $user->hasPermission('machines.delete'),
                'users.read' => $user->hasPermission('users.read'),
                'users.create' => $user->hasPermission('users.create'),
                'users.update' => $user->hasPermission('users.update'),
                'users.delete' => $user->hasPermission('users.delete'),
            ]
        ];
    })->name('debug.permissions');

    // Debug route for checking role features
    Route::get('debug/roles', function() {
        $roles = \App\Models\Role::with('features')->get();
        $result = [];
        
        foreach ($roles as $role) {
            $grantedFeatures = $role->features->where('pivot.is_granted', true);
            $revokedFeatures = $role->features->where('pivot.is_granted', false);
            
            $result[$role->name] = [
                'display_name' => $role->display_name,
                'total_features' => $role->features->count(),
                'granted_features' => $grantedFeatures->pluck('name', 'id')->toArray(),
                'revoked_features' => $revokedFeatures->pluck('name', 'id')->toArray(),
                'granted_count' => $grantedFeatures->count(),
                'revoked_count' => $revokedFeatures->count(),
            ];
        }
        
        return $result;
    })->name('debug.roles');

// Logs routes (superadmin only)
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/language', 'settings.language')->name('settings.language');
});

require __DIR__.'/auth.php';