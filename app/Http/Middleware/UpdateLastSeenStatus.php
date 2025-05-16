<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeenStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) { 
            $user = Auth::user();
            
            $threshold = Carbon::now()->subMinutes(1);

            if (is_null($user->last_seen) || $user->last_seen->lt($threshold)) {
                $user->last_seen = Carbon::now();
                $user->save();
            }
        }
        return $next($request);
    }
}
