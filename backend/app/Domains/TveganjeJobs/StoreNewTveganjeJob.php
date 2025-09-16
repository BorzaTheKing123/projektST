<?php

namespace App\Domains\TveganjeJobs;

use App\Models\Tveganje;
use Illuminate\Support\Facades\Auth;

class StoreNewTveganjeJob
{
    public function __construct(private $request, private $input)
    {

    }

    public function handle()
    {   
        if (! Tveganje::where('email', $this->request->input('email'))->exists())
        {
            $this->input['user_id'] = Auth::id();
            Tveganje::create($this->input);
            return response()->json($this->input);
        }

        return response()->json([
            'message' => 'Email je bil že uporabljen!'
        ], 409);
        
    }
}