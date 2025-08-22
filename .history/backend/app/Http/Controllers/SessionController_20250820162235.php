<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SessionController extends Controller
{
    public function show()
    {
        return Inertia::render('Home');
    }
}
