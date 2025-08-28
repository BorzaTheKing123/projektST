<?php

namespace App\Http\Controllers\Api;

use App\Features\UserFeatures\RegisterUserFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function create()
    {
        return response()->json(200);
    }

    public function store(Request $request)
    {
        return new RegisterUserFeature($request)->handle();
    }
}
