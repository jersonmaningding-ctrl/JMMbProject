<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTeacher
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session()->get('user');
        
        if (!$user || ($user->role !== 'teacher' && $user->role !== 'admin')) {
            return redirect('/login')->with('error', 'Access denied. Teacher access required.');
        }

        return $next($request);
    }
}
