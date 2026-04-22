<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->banned_until && $user->banned_until > now()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $until = \Carbon\Carbon::parse($user->banned_until)->format('Y-m-d H:i') . ' UTC';
            $reason = $user->ban_reason ? " Reason: {$user->ban_reason}." : '';

            return redirect()->route('login')
                ->withErrors(['email' => "Your account is banned until {$until}.{$reason}"]);
        }

        return $next($request);
    }
}
