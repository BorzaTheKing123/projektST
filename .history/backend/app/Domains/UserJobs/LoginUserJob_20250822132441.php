<?php

namespace App\Domains\UserJobs;

use Illuminate\Support\Facades\Auth;

class LoginUserJob
{

    protected $userData;

    public function __construct(private $request)
    {

    }

    public function handle()
    {
        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $this->request->session()->regenerate();
            // Naredi drugače
            return Auth::id();
        }
 
        return 'Neveljavni podatki';
    }
}