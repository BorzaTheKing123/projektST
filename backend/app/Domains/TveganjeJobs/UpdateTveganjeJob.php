<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Tveganje;

class UpdateTveganjeJob
{
    public function __construct(private Tveganje $tveganje, private $request, private $info)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik tveganja
        if ($this->tveganje->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za urejanje tega tveganja.'
            ], 403);
        }

        // Posodobimo tveganje z validiranimi podatki
        $this->tveganje->update($this->info);

        return response()->json([
            'message' => 'Tveganje uspešno posodobljeno.',
            'tveganje' => $this->tveganje
        ]);
    }
}