<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthStudent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = session()->get('user');
        
        if (!$user || $user->role !== 'student') {
            return redirect('/login')->with('error', 'Access denied. Student access required.');
        }

        return $next($request);
    }
}
