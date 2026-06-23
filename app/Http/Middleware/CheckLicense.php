<?php
// app/Http/Middleware/CheckLicense.php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\LicenseHelper;

class CheckLicense
{
    public function handle($request, Closure $next)
    {
        // ? Skip for license routes (so user can renew)
        if ($request->routeIs('license.*')) {
            return $next($request);
        }

        // ? Skip for login page
        if ($request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        // ? Check if license is valid
        if (!LicenseHelper::isLicenseValid()) {
            // Redirect to license page
            return redirect()->route('license.index')->with('error', 'Your license has expired or is not activated. Please renew.');
        }

        return $next($request);
    }
}