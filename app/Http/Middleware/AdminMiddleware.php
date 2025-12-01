<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // user đang login (qua Sanctum / token)

        if (!$user || $user->roles !== 'admin') {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập khu vực admin.'
            ], 403);
        }

        return $next($request);
    }
}
