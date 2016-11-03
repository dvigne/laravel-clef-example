<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class clef
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (session('user_id') == null || Auth::user()->logged_out_at > session('logged_in_at')) {
        Auth::logout();
        return redirect('/login');
      }
        return $next($request);
    }
}
