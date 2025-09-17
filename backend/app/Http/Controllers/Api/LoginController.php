<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Features\UserFeatures\LoginUserFeature;
// use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        return new LoginUserFeature()->handle();
        
    }
}

