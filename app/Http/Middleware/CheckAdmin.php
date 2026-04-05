<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
{
    if (Auth::check() && Auth::user()->role_id == 1) {
        return $next($request);
    }

    // Nếu không phải Admin, trả về trang Ideas kèm thông báo lỗi
    return redirect()->route('ideas.index')->with('error', 'Bạn không có quyền truy cập vùng này!');
}
}
