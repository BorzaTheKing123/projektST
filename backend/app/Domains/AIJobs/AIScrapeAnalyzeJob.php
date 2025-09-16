<?php

namespace App\Domains\AIJobs;
use App\Models\HeatmapModels\RiskMention;
use Illuminate\Support\Facades\Log;
use App\Models\HeatmapModels\Risk;

class AIScrapeAnalyzeJob
{
    /**
     * Konstruktor za injektiranje odvisnosti ali prejem podatkov.
     */
    public function __construct(private $data, private $prompt, private $content)
    {
        //
    }

    /**
     * Izvede glavno logiko.
     *
     * @param  mixed  ...$parameters
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(600);

        $link = $this->data["link"];

        if (!$this->content || trim($this->content) === '') {
            Log::warning('AI vrnil prazen odgovor.');
            return response()->json(['error' => 'Ni predlogov.']);
        }

        // Normaliziramo JSON (če AI vrne enojne narekovaje)
        $normalized = str_replace("'", '"', $this->content);
        $parsed = json_decode($normalized, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('AI odgovor ni veljaven JSON', ['content' => $this->content]);
            return response()->json(['error' => 'Neveljaven AI JSON odgovor.']);
        }

        Log::info($parsed);

        foreach ($parsed as $pars) {
            $risk = Risk::where('category', $pars['category'])->first();

            RiskMention::create([
            'risk_id'    => $risk->id,
            'confidence' => $pars['confidence'] ?? null,
            'link' => $link,
            'summary' => $pars['summary'] ?? null,
        ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}