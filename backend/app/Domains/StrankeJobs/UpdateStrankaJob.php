<?php

namespace App\Domains\StrankeJobs;

use Illuminate\Support\Facades\Auth;
use App\Models\Stranka;

class UpdateStrankaJob
{
    public function __construct(private Stranka $stranka, private $request, private $info)
    {
    }

    public function handle()
    {
        // Preverimo, ali je trenutni uporabnik lastnik stranke
        if ($this->stranka->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Nimaš dovoljenja za urejanje te stranke.'
            ], 403);
        }

        // Posodobimo stranko z validiranimi podatki
        $this->stranka->update($this->info);

        return response()->json([
            'message' => 'Stranka uspešno posodobljena.',
            'stranka' => $this->stranka
        ]);
    }
}

