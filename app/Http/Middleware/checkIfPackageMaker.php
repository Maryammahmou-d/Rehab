<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Traits\HasRoles;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class checkIfPackageMaker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$role="PackageMaker")
    {
             // Check if the authenticated user has the specified role
             if (Auth::check() && Auth::user()->hasRole($role)) {
                return $next($request);
            }

            // Redirect or handle unauthorized access
            return response()->json("Unauthorized Access", 401);

    }}
