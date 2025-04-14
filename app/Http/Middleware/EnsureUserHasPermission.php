<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!method_exists(Auth::user(), 'hasPermission')) {
            abort(500, 'Method hasPermission not found in User model');
        }

        if (!Auth::user()->hasPermission($permission)) {
            abort(403, 'Bạn không có quyền thực hiện hành động này');
        }

        return $next($request);
    }
}
