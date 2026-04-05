<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    IdeaController, CommentController, ExportController, 
    AuthController, DashboardController, AdminController, 
    ReactionController, CategoryController
};
use App\Http\Controllers\Admin\UserController;

// 1. GUEST ROUTES
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    if (auth()->check()) {
        // Chuyển hướng thông minh dựa trên vai trò khi vào trang chủ
        return in_array(auth()->user()->role_id, [1, 2, 3]) 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('ideas.index');
    }
    return redirect()->route('login');
});

// 2. NHÓM CHUNG (Tất cả nhân viên - Role 1, 2, 3, 4)
Route::middleware(['auth'])->group(function () {
    
    // Xem danh sách và chi tiết (Phân trang 5 bài/trang trong Controller)
    Route::get('/ideas', [IdeaController::class, 'index'])->name('ideas.index');
    Route::get('/ideas/{id}', [IdeaController::class, 'show'])->name('ideas.show');
    
    // Tương tác: Vote & Comment (Phải qua Middleware check ngày khóa)
    Route::post('/ideas/{id}/vote', [ReactionController::class, 'vote'])->name('ideas.vote');
    Route::post('/ideas/{id}/comment', [CommentController::class, 'store'])
        ->name('comments.store')
        ->middleware('check.closure:comment');

    // Nộp ý tưởng (Cần đồng ý điều khoản và check ngày khóa)
    Route::get('/submit-idea', [IdeaController::class, 'create'])->name('ideas.create');
    Route::post('/ideas', [IdeaController::class, 'store'])
        ->name('ideas.store')
        ->middleware('check.closure:idea');

    // --- NHÓM ADMIN (Role 1) ---
    // Yêu cầu: Duy trì dữ liệu hệ thống, ngày khóa (Closure dates), thông tin Staff
    Route::middleware(['auth', 'checkrole:1'])->prefix('admin')->group(function () {
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    // Thêm dòng này để xử lý form submit
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        // Quản lý nhân viên (Staff details)
        Route::resource('users', UserController::class)->names('users');
    });

    // --- NHÓM QA MANAGER (Role 2) ---
    // Yêu cầu: Quản lý Categories, Download CSV/ZIP sau ngày khóa
    Route::middleware(['checkrole:2'])->prefix('qa-manager')->group(function () {
        Route::resource('categories', CategoryController::class)->names('categories');
        
        // Export dữ liệu
        Route::get('/export-csv', [ExportController::class, 'exportCSV'])->name('ideas.export');
        Route::get('/export-zip', [ExportController::class, 'exportZIP'])->name('ideas.exportZIP');
    });

    // --- NHÓM DASHBOARD THỐNG KÊ (Admin, QA Manager, QA Coordinator) ---
    // Yêu cầu: Phân tích thống kê (Số lượng idea mỗi khoa...)
    Route::middleware(['checkrole:1,2,3'])->get('/admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
});
Route::get('/ideas/{id}/edit', [IdeaController::class, 'edit'])->name('ideas.edit');
Route::put('/ideas/{id}', [IdeaController::class, 'update'])->name('ideas.update'); // Cần cái này để lưu sau khi sửa
Route::resource('ideas', IdeaController::class);