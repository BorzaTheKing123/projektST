<?php

namespace App\Http\Controllers\Api;

use App\Features\UserFeatures\RegisterUserFeature;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

