<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Update last_seen every minute to avoid too many database updates
            $user = Auth::user();
            $wasOnline = $user->isOnline();
            
            if (!$user->last_seen || $user->last_seen->lt(now()->subMinute())) {
                $user->update(['last_seen' => now()]);
                
                // Broadcast status update if status changed
                $user->refresh();
                if ($wasOnline !== $user->isOnline()) {
                    broadcast(new \App\Events\UserStatusUpdated($user));
                }
            }
        }

        return $next($request);
    }
}
