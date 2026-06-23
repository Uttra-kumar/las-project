<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Check if user has admin role
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin only area.');
        }
        
        return $next($request);
    }
}