<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCanManage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->canManage()) {
            abort(403, 'Acesso restrito a administradores e gerentes.');
        }

        return $next($request);
    }
}
