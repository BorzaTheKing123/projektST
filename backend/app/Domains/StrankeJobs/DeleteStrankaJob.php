<?php

namespace App\Domains\StrankeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Stranka;

class DeleteStrankaJob
{
    public function __construct(private Stranka $stranka)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik stranke
        if ($this->stranka->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za brisanje te stranke.'
            ], 403);
        }

        // Izbrišemo stranko prek modela
        $this->stranka->delete();

        return response()->json([
            'message' => 'Stranka uspešno izbrisana.'
        ]);
    }
}
