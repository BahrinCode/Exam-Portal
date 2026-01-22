<?php
// app/Http/Middleware/LecturerMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LecturerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isLecturer()) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }
}