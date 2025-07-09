<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class RequireJsonAcceptHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->wantsJson()) {
            return response()->json([
                'error' => 'Отсутствует или недействителен заголовок Accept',
                'message' => 'Запрос должен включать заголовок "Accept: application/json"',
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
