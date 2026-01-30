<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use JsonResponse;

    public function user()
    {
        $auth = request('AuthenticatedUserApi');
        return $auth ? (object) $auth : null;
    }
}
