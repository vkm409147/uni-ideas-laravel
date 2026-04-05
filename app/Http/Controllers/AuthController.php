<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        $departments = Department::all(); // Lấy dữ liệu từ DB
        return view('auth.register', compact('departments'));
    }
    
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Chuyển hướng về trang chủ hoặc trang login
    return redirect('/login')->with('success', 'You have successfully logged out.!');
}
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'department_id' => 'required|exists:departments,id',
        'terms' => 'accepted', // Bắt buộc tick vào "Agree to Terms and Conditions"
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => 4, // Mặc định là Staff
        'department_id' => $request->department_id,
        'agreed_tc' => true,
    ]);

    Auth::login($user); // Đăng ký xong cho đăng nhập luôn

    return redirect('/dashboard');
}

public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // PHÂN QUYỀN ĐIỀU HƯỚNG TẠI ĐÂY
        if ($user->role_id == 1) { // Giả sử 1 là Admin
            return redirect()->intended(route('admin.dashboard'));
        } 
        
        // Nếu là Staff (role_id = 4 hoặc các role khác)
        return redirect()->route('ideas.index'); 
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}
    
}