<?php
namespace App\Http\Controllers\Api\Auth;

use App\Enum\ErrorApi;
use App\Helpers\JwtToken;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

/**
 * @group Authorization
 * @sorting 1
 * 
 */
class ApiTokenController extends ApiController
{
    public $apiSecretKey;
    
    public function __construct()
    {
        $this->apiSecretKey = config('services.api.secret_key');
    }

    /**
     * Get Token 
     * 
     * For first initial token to use for login <br> Authorization = base64encode( date('Y-m-d') | API_SECRET_KEY )
     * @sorting 1
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *  "status": 200,
     *  "message": "success",
     *  "data": {
     *      "token": "token-for-public-api-access",
     *      "expiredAt": 1655031621
     *   }
     *  }
     */
    public function getToken(Request $request)
    {
        $token = JwtToken::getToken();
        $token = base64_decode($token);
        $tokens = explode('|', $token);
        if ($tokens[0] == date('Y-m-d') && $tokens[1] == $this->apiSecretKey) {
            $token = JwtToken::setData(['scope' => 'guest'])->setExpired("+2 days")->build();
            return $this->sendSuccess($token);
        }
        return $this->unauthorized("Token was invalid",ErrorApi::INVALID_TOKEN);
    }

    /**
     * Renew Token
     * 
     * Request renew token to extend the token expiration period 
     * 
     * @sorting 2
     * 
     * @authenticated
     * @defaultParam
     * 
     * @header key string required
     * 
     * @response {
     *  "status": 200,
     *  "message": "success",
     *  "data": {
     *      "token": "token-whit-more-expired-time",
     *      "expiredAt": 1655031621
     *   }
     *  }
     */
    public function renewToken(Request $request)
    {
        $newToken = null;
        if((config('services.api.validate_blacklist') && JwtToken::isBlacklist())){
            return $this->unauthorized('Your token was not found !');
        }
        try {
            $token = base64_decode($request->header('key'));
            $tokens = explode('|', $token);
            if ($tokens[0] != date('Y-m-d') || $tokens[1] != $this->apiSecretKey) {
                return $this->unauthorized("Token was invalid",ErrorApi::INVALID_TOKEN);
            }

            $payload = JwtToken::decode();
            $newToken = JwtToken::setData($payload->data)->setExpired("+2 days")->build();
        } catch (\Exception$e) {
            if ($e->getMessage() == "Expired token") {
                $token = JwtToken::getToken();
                list($header, $payload, $signature) = explode(".", $token);
                $payload = json_decode(base64_decode($payload));
                $newToken = JwtToken::setData($payload->data)->setExpired("+2 days")->build();
            }
        }
        if($newToken){
            return $this->sendSuccess($newToken);
        }
        return $this->unauthorized('Token was invalid', ErrorApi::INVALID_TOKEN);
    }
}