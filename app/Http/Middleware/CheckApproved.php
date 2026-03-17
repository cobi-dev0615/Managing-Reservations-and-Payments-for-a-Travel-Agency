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

            $message = __('messages.pending_approval_msg');
            if ($request->user() && $request->user()->isSuspended()) {
                $message = __('messages.account_suspended_msg');
            }

            return redirect()->route('login')->withErrors(['status' => $message]);
        }

        return $next($request);
    }
}
