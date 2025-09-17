<?php

namespace App\Domains\TveganjeJobs;

use App\Models\Tveganje;
use Illuminate\Support\Facades\Auth;

class StoreNewTveganjeJob
{
    public function __construct(private $validated)
    {

    }

    public function handle()
    {   
        $tveganja = Tveganje::create($this->validated);
        return response()->json([
            'message' => 'Tveganje uspešno dodano.',
            'data' => $tveganja
        ], 201);
    }
}