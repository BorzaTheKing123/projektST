<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features\UserFeatures\LoginUserFeature;

class LoginController extends Controller
{

    public function edit()
    {
        return response()->json(['test' => 'Hello world'], 200);
    }

	public function login(Request $request)
    {
        return new LoginUserFeature($request)->handle();
    }
}
