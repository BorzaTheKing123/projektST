<?php

namespace App\Http\Controllers;

class SessionController extends Controller
{
    public function show()
    {
        return Inertia::render('Home');
    }
}
