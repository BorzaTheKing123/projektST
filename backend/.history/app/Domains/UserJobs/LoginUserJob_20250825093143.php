<?php

namespace App\Domains\UserJobs;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginUserJob
{

    protected $userData;

    public function __construct(private $request)
    {

    }

    public function handle()
    {   
        $credentials = Validator::make($this->request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => 'required',
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'message' => 'The given data was invalid!',
                'errors' => $credentials->errors()
            ], 422);
        }

        $user = User::where('email', $this->request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;
 
        // if (Auth::attempt($credentials)) {
        //     $this->request->session()->regenerate();
        //     // Naredi drugače
        //     return Auth::id();
        // }

        return response()->json([
            'message' => 'Uporabnik ne obstaja!',
        ], 409);
    }
}