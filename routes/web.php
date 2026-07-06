<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile Route
    Route::get('/profile', \App\Livewire\Profile\UserProfile::class)->name('profile');

    // Leave Routes (Accessible by authenticated users)
    Route::get('/leave/request', \App\Livewire\Leave\LeaveRequestForm::class)->name('leave.request');
    Route::get('/leave/approvals', \App\Livewire\Leave\ApprovalManager::class)->name('leave.approvals');
    Route::get('/leave/print/{id}', function($id) {
        $req = \App\Models\LeaveRequest::findOrFail($id);
        // Pastikan hanya yang berhak yang bisa print
        if (Auth::id() !== $req->user_id && !Auth::user()->hasRole('HR / Manager|Super Admin')) {
            abort(403);
        }
        return view('leave.print', compact('req'));
    })->name('leave.print');

    // Notifications
    Route::post('/notifications/read-all', function() {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    // Admin Routes (Livewire)
    Route::middleware(['role:Super Admin|HR / Manager'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users.index');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/roles', \App\Livewire\Admin\RoleManagement::class)->name('roles.index');
    });

    // Approval Chains Route (Requires specific permission)
    Route::middleware(['permission:manage_approval_chains'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/approval-chains', \App\Livewire\Admin\ApprovalChainManager::class)->name('approval-chains.index');
    });
});

// Route Verifikasi QR Code (Public / Bebas Akses)
Route::get('/leave/verify/{id}/{role}', function($id, $role) {
    $req = \App\Models\LeaveRequest::findOrFail($id);
    return view('leave.verify', compact('req', 'role'));
})->name('leave.verify');
