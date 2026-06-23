<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('parent_logged_in')) {
            return redirect()->route('parent.login')->with('error', 'Please login first.');
        }

        return $next($request);
    }
}