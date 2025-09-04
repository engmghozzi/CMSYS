<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
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

Route::middleware(['auth'])->group(function () {
    // User routes with permissions - only super_admin can access
    Route::middleware(['can_manage_features'])->group(function() {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Client routes
    Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    
    // Address routes
    Route::get('clients/{client}/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::get('clients/{client}/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
    Route::post('clients/{client}/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::get('clients/{client}/addresses/{address}', [AddressController::class, 'show'])->name('addresses.show');
    Route::get('clients/{client}/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
    Route::put('clients/{client}/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('clients/{client}/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
   
    // Contract routes
    Route::get('clients/{client}/contracts', [ContractController::class, 'globalindex'])->name('clients.contracts.index');
    Route::get('clients/{client}/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::get('clients/{client}/contracts/create/{address}', [ContractController::class, 'createFromAddress'])->name('contracts.create.from-address');
    Route::post('clients/{client}/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('clients/{client}/contracts/{contract}', [ContractController::class, 'show'])->name('contracts.show');
    Route::get('clients/{client}/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('clients/{client}/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('clients/{client}/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
    Route::get('clients/{client}/contracts/{contract}/renew', [ContractController::class, 'renew'])->name('contracts.renew');
   
    // Payment routes
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



    // Global routes
    Route::get('contracts', [ContractController::class, 'globalindex'])->name('contracts.globalindex');
    Route::get('contracts/export', [ContractController::class, 'export'])->name('contracts.export')->middleware('permission:contracts.export.excel');
    Route::get('contracts/print', [ContractController::class, 'print'])->name('contracts.print')->middleware('permission:contracts.export.pdf');
    Route::get('payments', [PaymentController::class, 'globalindex'])->name('payments.globalindex');

    // Role Management routes - only super_admin and admin can access
    Route::middleware(['can_manage_features'])->group(function() {
        Route::resource('roles', RoleController::class);
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::put('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');
        Route::get('roles/{role}/users', [RoleController::class, 'users'])->name('roles.users');

        // Feature Management routes - only super_admin and admin can access
        Route::resource('features', FeatureController::class);
        Route::get('features/{feature}/usage', [FeatureController::class, 'usage'])->name('features.usage');
        Route::post('features/bulk-update', [FeatureController::class, 'bulkUpdate'])->name('features.bulk-update');
        Route::get('features/categories', [FeatureController::class, 'categories'])->name('features.categories');
        Route::post('features/generate', [FeatureController::class, 'generateFromResources'])->name('features.generate');
    });

    // Logs routes (superadmin only)
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');

    // Settings routes
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    Volt::route('settings/language', 'settings.language')->name('settings.language');
});

// Temporary test route for S3 deletion debugging
Route::get('test/s3-debug/{contract}', function(\App\Models\Contract $contract) {
    $result = [
        'contract_id' => $contract->id,
        'attachment_url' => $contract->attachment_url,
        'raw_attachment_url' => $contract->getRawOriginal('attachment_url'),
        's3_file_path' => $contract->s3_file_path,
        'file_exists' => null,
        'deletion_result' => null
    ];
    
    if ($contract->s3_file_path) {
        $disk = \Illuminate\Support\Facades\Storage::disk('s3_contracts');
        $result['file_exists'] = $disk->exists($contract->s3_file_path);
        
        if ($result['file_exists']) {
            $result['deletion_result'] = $disk->delete($contract->s3_file_path);
        }
    }
    
    return response()->json($result);
})->name('test.s3.debug');

require __DIR__.'/auth.php';