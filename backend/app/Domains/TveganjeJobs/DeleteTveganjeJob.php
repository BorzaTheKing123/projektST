<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Tveganje;

class DeleteTveganjeJob
{
    public function __construct(private $tveganje)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik tveganja
        //dd($this->tveganje->load('stranka')->toArray());
        if ($this->tveganje->stranka->user_id !== Auth::guard('api')->id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za brisanje tega tveganja.'
            ], 403);
        }

        // Izbrišemo tveganja prek modela
        $this->tveganje->delete();

        return response()->json([
            'message' => 'Tveganje uspešno izbrisano.'
        ]);
    }
}