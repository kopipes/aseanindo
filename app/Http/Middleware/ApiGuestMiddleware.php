<?php

namespace App\Http\Middleware;

use App\Enum\ErrorApi;
use App\Helpers\Crypto;
use App\Helpers\JwtToken;
use App\Traits\JsonResponse;
use Closure;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGuestMiddleware
{
    use JsonResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->path() === 'v1/token') {
            return $next($request);
        }

        if(!$request->header('regid') || !$request->header('lang')){
            return $this->badRequest('Missing required header');
        }

        $token = JwtToken::getToken();
        $isBlacklistToken = (config('services.api.validate_blacklist') && JwtToken::isBlacklist());
        if ($isBlacklistToken || !$token) {
            return $this->unauthorized('Your token was not found !');
        }

        try {
            $decodedToken = JwtToken::decode($token);
            if ($decodedToken->iss !== config('app.name')) {
                logger('error iss');
                return $this->unauthorized('Your token was invalid', ErrorApi::INVALID_TOKEN);
            }
            $tokenData = $decodedToken?->data;
            if($tokenData->scope==='auth'){
                unset($tokenData->scope);
                $tokenData->id = Crypto::decrypt($tokenData->id);
                if(@$tokenData?->company_id){
                    $tokenData->company_id = Crypto::decrypt($tokenData->company_id);
                }
                $request->merge([
                    'AuthenticatedUserApi' =>  (array) $tokenData
                ]);
            }

            return $next($request);
        } catch (\Exception $e) {
            if ($e instanceof ExpiredException)  return $this->unauthorized("Your token was expired !", ErrorApi::EXPIRED_TOKEN);

            logger($e);
            return $this->unauthorized('Your token was invalid', ErrorApi::INVALID_TOKEN);
        }
    }
}
