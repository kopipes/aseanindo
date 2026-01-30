<?php

namespace App\Http\Middleware;

use App\Enum\ErrorApi;
use App\Traits\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPrivateMiddleware
{
    use JsonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->AuthenticatedUserApi){
            return $this->unauthorized("You do not have access to this url !", ErrorApi::FORBIDDEN);
        }
        return $next($request);
    }
}
