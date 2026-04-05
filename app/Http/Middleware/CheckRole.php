<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // QUAN TRỌNG: Phải có dòng này
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Kiểm tra quyền (role_id)
        if (in_array(Auth::user()->role_id, $roles)) {
            return $next($request);
        }

        // 3. Nếu không có quyền: Trả về trang lỗi 403 của Laravel (trông chuyên nghiệp hơn JSON)
        abort(403, 'You do not have permission to access this page.!');
    }
}