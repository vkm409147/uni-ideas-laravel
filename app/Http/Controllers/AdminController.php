<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    
    // Trang quản lý hệ thống
    public function index()
    {
        return view('admin.index');
    }

    // Thiết lập ngày đóng cửa (Closure Dates)
    public function setDates(Request $request)
    {
        $request->validate([
            'closure_date' => 'required|date',
            'final_closure_date' => 'required|date|after:closure_date',
        ]);

        // Lưu vào bảng settings
        DB::table('settings')->updateOrInsert(
            ['id' => 1],
            [
                'closure_date' => $request->closure_date,
                'final_closure_date' => $request->final_closure_date
            ]
        );

        return back()->with('success', 'System deadlines have been updated!');
    }
    public function settings()
{
    // Lấy giá trị hiện tại từ DB để hiển thị lên Form
    $closure_date = SystemSetting::where('key', 'closure_date')->value('value');
    $final_closure_date = SystemSetting::where('key', 'final_closure_date')->value('value');

    return view('admin.settings', compact('closure_date', 'final_closure_date'));
}

public function updateSettings(Request $request)
{
    $request->validate([
        'closure_date' => 'required|date',
        'final_closure_date' => 'required|date|after:closure_date',
    ]);

    // Cập nhật hoặc tạo mới nếu chưa có
    SystemSetting::updateOrCreate(['key' => 'closure_date'], ['value' => $request->closure_date]);
    SystemSetting::updateOrCreate(['key' => 'final_closure_date'], ['value' => $request->final_closure_date]);

    return redirect()->back()->with('success', 'Cập nhật ngày khóa hệ thống thành công!');
}
}