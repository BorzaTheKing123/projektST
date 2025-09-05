<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Tveganje;

class DeleteTveganjeJob
{
    public function __construct(private Tveganje $tveganja)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik tveganja
        if ($this->tveganja->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za brisanje tega tveganja.'
            ], 403);
        }

        // Izbrišemo tveganja prek modela
        $this->tveganja->delete();

        return response()->json([
            'message' => 'Tveganje uspešno izbrisano.'
        ]);
    }
}