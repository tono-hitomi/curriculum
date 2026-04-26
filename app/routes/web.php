<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\AdminController;

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::get('/home', [EventController::class, 'index']);
    Route::resource('events', EventController::class);

    Route::get('/mypage', [EventController::class, 'mypage'])->name('mypage');
    Route::get('/profile', [EventController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [EventController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [EventController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/profile/delete', [EventController::class, 'confirmDelete'])->name('profile.delete.confirm');
    Route::delete('/profile/delete', [EventController::class, 'deleteAccount'])->name('profile.delete');

    Route::get('/bookmarks', [EventController::class, 'bookmarkIndex'])->name('bookmarks.index');
    Route::post('/events/{event}/bookmark', [EventController::class, 'bookmark'])->name('events.bookmark');
    Route::delete('/bookmarks/{event}', [EventController::class, 'unbookmark'])->name('bookmarks.destroy');

    Route::get('/events/{event}/apply', [EventController::class, 'apply'])->name('events.apply');
    Route::post('/events/{event}/apply', [EventController::class, 'storeApplication'])->name('events.apply.store');
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel'])->name('events.cancel');

    Route::get('/events/{event}/report', [EventController::class, 'report'])->name('events.report');
    Route::post('/events/{event}/report', [EventController::class, 'storeReport'])->name('events.report.store');

// --- 管理者専用画面グループ ---
    Route::prefix('admin')->middleware(['can:admin'])->name('admin.')->group(function () {
        
        // 違反報告リセット
        Route::delete('/events/{event}/reports-reset', [AdminController::class, 'resetReports'])->name('reports.destroy');

        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/events', [AdminController::class, 'eventList'])->name('events.index');
        Route::patch('/events/{event}/toggle-visible', [AdminController::class, 'toggleVisible'])->name('events.toggleVisible');
        Route::post('/users/{id}/restore', [AdminController::class, 'restore'])->name('users.restore');
        
        // 違反報告一覧
        Route::get('/reports', [AdminController::class, 'reportIndex'])->name('reports.index');

        Route::get('/users', [AdminController::class, 'userIndex'])->name('users.index');
        Route::get('/users/{user}/suspend', [AdminController::class, 'confirmSuspend'])->name('users.confirm_suspend');
        Route::post('/users/{user}/suspend', [AdminController::class, 'suspend'])->name('users.suspend');

        // CSVエクスポート
        Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('users.export');
        Route::get('/export/events', [AdminController::class, 'exportEvents'])->name('events.export');
        Route::get('/export/applications', [AdminController::class, 'exportApplications'])->name('applications.export');
    });
});