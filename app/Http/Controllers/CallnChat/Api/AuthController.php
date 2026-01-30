<?php

namespace App\Http\Controllers\CallnChat\Api;

use App\Actions\Auth\LoginWebGuest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request, LoginWebGuest $loginWebGuest)
    {
        $result = $loginWebGuest->handle($request);
        return response()->json($result);
    }
}
