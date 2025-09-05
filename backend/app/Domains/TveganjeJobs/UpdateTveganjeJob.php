<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Tveganje;

class UpdateTveganjeJob
{
    public function __construct(private Tveganje $tveganja, private $request, private $info)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik tveganja
        if ($this->tveganja->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za urejanje tega tveganja.'
            ], 403);
        }

        // Posodobimo tveganja z validiranimi podatki
        $this->tveganja->update($this->info);

        return response()->json([
            'message' => 'Tveganje uspešno posodobljeno.',
            'tveganja' => $this->tveganja
        ]);
    }
}