<?php

namespace App\Domains\UserJobs;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
                'message' => 'Neveljaven vnos!',
                'errors' => $credentials->errors()
            ], 422);
        }

        $user = User::where('email', $this->request->email)->first();

        if (! $user || ! Hash::check($this->request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($this->request->device_name)->plainTextToken;
    }
}