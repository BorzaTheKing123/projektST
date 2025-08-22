<?php

namespace App\Http\Controllers\UserControllers;

use App\Features\UserFeatures\RegisterUserFeature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('Register');
    }

    public function store(Request $request)
    {
        return new RegisterUserFeature($request)->handle();
    }
}
