<?php

namespace App\Http\Controllers\UserControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Features\UserFeatures\LoginUserFeature;

class LoginController extends Controller
{

    public function edit()
    {
        return Inertia::render('Login');
    }

	public function login(Request $request)
    {
        return new LoginUserFeature($request)->handle();
    }
}
