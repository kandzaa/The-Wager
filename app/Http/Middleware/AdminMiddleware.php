<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }

    //izdzēš user
    public function userDelete()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }
        return false;
    }

    //edit user
    public function userEdit()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;

        }
        return false;
    }

    //izdzēš wager
    public function wagerDelete()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }
        return false;
    }

    //edit wager
    public function wagerEdit()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;

        }
        return false;
    }

}
