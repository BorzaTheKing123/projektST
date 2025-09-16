<?php

namespace App\Domains\StrankeJobs;

use App\Models\Stranka;
use Illuminate\Support\Facades\Auth;

class StoreNewStrankaJob
{
    public function __construct(private $request, private $input)
    {

    }

    public function handle()
    {   
        if (! Stranka::where('email', $this->request->input('email'))->exists())
        {
            $this->input['user_id'] = Auth::id();
            Stranka::create($this->input);
            return response()->json($this->input);
        }

        return $this->input;
        
    }
}