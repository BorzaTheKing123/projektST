<?php

namespace App\Domains\TveganjeJobs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tveganje;

class ValidateTveganjeJob
{
    public function __construct(private Request $request, private ?Tveganje $tveganja = null)
    {
    }

    public function handle()
    {
        // Pridobi ID stranke, če obstaja (pri urejanju)
        $ignoreId = $this->tveganja?->id ?? '';

        // Nastavi pravila
        $rules = [
            'name' => ['required', 'string', 'alpha_dash', 'min:2', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:stranke,email,' . $ignoreId . ',id'],
            'phone' => ['required', 'string', 'min:5'],
            'dejavnost' => ['required', 'string']
        ];

        // Ustvari validator
        $validator = Validator::make($this->request->all(), $rules);

        // Če validacija pade, vrni napake
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 422);
        }

        // Vse OK → vrni validirane podatke
        return $validator->validated();
    }
}