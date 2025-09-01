<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
// 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\LocaleMiddleware;
use App\Livewire\Permission\CreatePermission;
use App\Livewire\Permission\EditPermission;
use App\Livewire\Permission\PermissionList;
use App\Livewire\Profile\EditProfile;
use App\Livewire\Role\CreateRole;
use App\Livewire\Role\EditRole;
use App\Livewire\Role\RoleList;
use App\Livewire\User\CreateUser;
use App\Livewire\User\EditUser;
use App\Livewire\User\ImportExportUsers;
use App\Livewire\User\UserList;
use App\Livewire\Finance\TransactionList;
use App\Livewire\Finance\CreateTransaction;
use App\Livewire\Finance\EditTransaction;

use App\Http\Controllers\Web\Fee\FeeController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    // return view('welcome');
    return view('web.fee.verify');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware([LocaleMiddleware::class])->group(function () {
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/users', UserList::class)->name('users.index');
        Route::get('/users/create', CreateUser::class)->name('users.create');
        Route::get('/users/{id}/edit', EditUser::class)->name('users.edit');
        Route::get('/users/import-export', ImportExportUsers::class)->name('users.import-export');
        Route::get('/users/export-pdf', [UserController::class, 'exportPdf'])->name('users.export-pdf');
        Route::resource('users', UserController::class)->except(['index', 'create', 'edit']);
        Route::get('/roles', RoleList::class)->name('roles.index');
        Route::get('/roles/create', CreateRole::class)->name('roles.create');
        Route::get('/roles/{id}/edit', EditRole::class)->name('roles.edit');
        Route::resource('roles', RoleController::class)->except(['index', 'create', 'edit']);
        Route::get('/permissions', PermissionList::class)->name('permissions.index');
        Route::get('/permissions/create', CreatePermission::class)->name('permissions.create');
        Route::get('/permissions/{id}/edit', EditPermission::class)->name('permissions.edit');
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/transactions', TransactionList::class)->name('transactions.index');
        Route::get('/transactions/create', CreateTransaction::class)->name('transactions.create');
        Route::get('/transactions/{id}/edit', EditTransaction::class)->name('transactions.edit');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', EditProfile::class)->name('profile.edit');
        Route::resource('profile', ProfileController::class)->only(['edit', 'update']);
    });

    Route::get('/set-locale/{locale}', function ($locale) {
        session(['locale' => $locale]);
        return redirect()->back();
    })->name('set-locale');
});

Route::get('/fee/verify', [FeeController::class, 'showVerificationForm'])->name('fee.verify');
Route::post('/fee/verify', [FeeController::class, 'verifyStudent'])->name('fee.verify.submit');
Route::post('/fee/submit', [FeeController::class, 'submitFeePayment'])->name('fee.submit');
Route::get('/fee/receipt/{id}', [FeeController::class, 'downloadReceiptPDF'])->name('fee.receipt');
Route::get('/fee/confirmation/{id}', [FeeController::class, 'downloadConfirmationPDF'])->name('fee.confirmation');
Route::get('/fee/attachment/{id}', [FeeController::class, 'viewAttachment'])->name('fee.attachment');
Route::post('/fee/update-status/{id}', [FeeController::class, 'updateStatus'])->name('fee.update-status');
Route::get('/fee/edit/{id}', [FeeController::class, 'editTransaction'])->name('fee.edit');
Route::post('/fee/update/{id}', [FeeController::class, 'updateTransaction'])->name('fee.update');
Route::get('/fee/export', [FeeController::class, 'export'])->name('fee.export');

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

// Clear All at once

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return "Cleared!";

});

// Link storage
Route::get('/link-storage', function () {
    $target = storage_path('app/public'); // Path to storage/app/public
    $link = public_path('storage');       // Path to public/storage

    if (!file_exists($link)) {
        symlink($target, $link);
        return "Storage linked successfully!";
    }

    return "Storage is already linked.";
});

# 📁 Serving Files from Storage in Laravel 12
Route::get('/file-storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return Response::file($fullPath);
})->where('path', '.*'); // <-- This allows slashes in {path}

// $dbPath = 'fee_attachments/202610060_security_deposit_20250813_151911.jpg';

// $url = url('/file-storage/' . $dbPath);

// <a href="{{ url('/file-storage/' . $attachment->path) }}" target="_blank">Download</a>

require __DIR__.'/auth.php';
