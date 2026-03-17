<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->isApproved()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'Sua conta esta aguardando aprovacao do administrador.';
            if ($request->user() && $request->user()->isSuspended()) {
                $message = 'Sua conta foi suspensa. Entre em contato com o administrador.';
            }

            return redirect()->route('login')->withErrors(['status' => $message]);
        }

        return $next($request);
    }
}
