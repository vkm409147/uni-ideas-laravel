<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;
use App\Models\Category;

class DashboardController extends Controller
{
    public function dashboard()
{
    // 1. Thống kê theo phòng ban
    $stats = \App\Models\Department::withCount('ideas')->get();

    // 2. Lấy tổng số User
    $totalUsers = \App\Models\User::count();

    // 3. Chuẩn bị dữ liệu biểu đồ
    $labels = $stats->pluck('name');
    $data = $stats->pluck('ideas_count');

    // 4. Truyền sang View admin/dashboard.blade.php
    return view('admin.dashboard', compact('stats', 'labels', 'data', 'totalUsers'));
}
    public function index()
{
    $ideas = Idea::with(['user', 'category'])->withCount(['comments', 'reactions'])->latest()->paginate(10);
    $categories = Category::all();
    
    // Thêm các dòng này nếu bạn muốn hiển thị thống kê ở trang index
    $stats = \App\Models\Department::withCount('ideas')->get();
    $totalUsers = \App\Models\User::count();
    $labels = $stats->pluck('name');
    $data = $stats->pluck('ideas_count');

    return view('admin.dashboard', compact('ideas', 'categories', 'stats', 'totalUsers', 'labels', 'data'));
}
}