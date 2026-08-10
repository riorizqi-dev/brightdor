<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RaiseExecutionTime
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            @set_time_limit(120);
            @ini_set('max_execution_time', '120');
        }

        return $next($request);
    }
}
