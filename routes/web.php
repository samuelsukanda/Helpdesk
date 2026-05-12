<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    TicketController,
    TicketCommentController,
    AdminUserController,
    AdminCategoryController,
    AdminDepartmentController,
    AdminSlaPolicyController,
    KnowledgeArticleController,
    ReportController,
    ProfileController,
    NotificationController,
};

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read.all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('tickets/{ticket}/comments/{comment}', [TicketCommentController::class, 'destroy'])->name('tickets.comments.destroy');
    Route::patch('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status.update');
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');

    // Knowledge Base
    Route::resource('knowledge', KnowledgeArticleController::class);
    Route::patch('knowledge/{knowledge}/publish', [KnowledgeArticleController::class, 'publish'])
        ->name('knowledge.publish')
        ->middleware('role:admin|agent');

    // Admin Only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::resource('users', AdminUserController::class);

        // Categories + sub-category AJAX
        Route::resource('categories', AdminCategoryController::class);
        Route::post('categories/{category}/sub', [AdminCategoryController::class, 'storeSubCategory'])->name('categories.sub.store');
        Route::delete('sub-categories/{subCategory}', [AdminCategoryController::class, 'destroySubCategory'])->name('sub-categories.destroy');

        // Departments
        Route::resource('departments', AdminDepartmentController::class);

        // SLA Policies
        Route::resource('sla-policies', AdminSlaPolicyController::class)->names('sla');
    });

    // Reports
    Route::middleware(['role:admin|agent'])->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export.pdf');
    });
});

require __DIR__ . '/auth.php';
