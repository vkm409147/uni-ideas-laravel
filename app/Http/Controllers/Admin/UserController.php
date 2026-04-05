<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    
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

        return back()->with('success', 'Đã cập nhật thời hạn hệ thống!');
    }
    public function index()
    {
        // Lấy danh sách kèm theo thông tin phòng ban để không bị lỗi 'department'
        $users = User::with('department')->latest()->get();
        $departments = Department::all();
        
        // Khai báo lại roles đúng với hệ thống của bạn
        $roles = [
            1 => 'Admin',
            2 => 'QA Manager',
            3 => 'QA Coordinator',
            4 => 'Staff'
        ];

        return view('admin.users.index', compact('users', 'departments', 'roles'));
    }

    public function store(Request $request)
    {
        
        // Kiểm tra dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Tạo người dùng
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Tạo tài khoản thành công!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Đã xóa người dùng.');
    }
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role_id' => 'required',
        'department_id' => 'required',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'department_id' => $request->department_id,
    ];

    // Chỉ cập nhật mật khẩu nếu người dùng có nhập mới
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->back()->with('success', 'Cập nhật tài khoản thành công!');
}
}