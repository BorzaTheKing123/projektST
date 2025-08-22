<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function show()
    {
        // To je lahko karkoli: podatki o prijavljenem uporabniku, zadnje novice, statistika ...

        $user = Auth::user();

        $data = [
            'appName' => config('app.name'),
            'welcomeMessage' => 'Dobrodošli na naši platformi!',
            'stats' => [
                'registeredUsers' => 1052,
                'activeProjects' => 42,
            ],
            // Podatke o uporabniku vključimo samo, če je prijavljen
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ] : null,
        ];

        // 2. Vrnite zbrane podatke kot JSON odgovor.
        return response()->json($data);
    }
}
