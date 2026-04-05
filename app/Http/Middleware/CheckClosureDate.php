<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Carbon\Carbon;

class CheckClosureDate
{
    public function handle(Request $request, Closure $next, $type)
    {
        
        $closureDateVal = SystemSetting::where('key', 'closure_date')->value('value');
        $finalClosureDateVal = SystemSetting::where('key', 'final_closure_date')->value('value');

        
        if (!$closureDateVal || !$finalClosureDateVal) {
            return $next($request);
        }

        $closureDate = Carbon::parse($closureDateVal);
        $finalClosureDate = Carbon::parse($finalClosureDateVal);
        $now = Carbon::now();

      
        if ($type === 'idea' && $now->greaterThan($closureDate)) {
            return redirect()->back()->with('error', 'Warning: The deadline for submitting new ideas has passed.!');
        }

        
        if ($type === 'comment' && $now->greaterThan($finalClosureDate)) {
            return redirect()->back()->with('error', 'Warning: The system has closed the comment feature!');
        }

        return $next($request);
    }
}