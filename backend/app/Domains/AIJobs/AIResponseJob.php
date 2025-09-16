<?php

namespace App\Domains\AIJobs;
use Illuminate\Support\Facades\Http;

class AIresponseJob
{
    /**
     * Konstruktor za injektiranje odvisnosti ali prejem podatkov.
     */
    public function __construct(private $prompt)
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
        $openaiKey = config('app.openai_api_key');

        $aiResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $openaiKey,
            'Content-Type' => 'application/json',
        ])->timeout(600)
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'Si strokovnjak za varnost in tveganja.'],
                ['role' => 'user', 'content' => $this->prompt],
            ],
            'temperature' => 0.5,
        ]);

        return data_get($aiResponse->json(), 'choices.0.message.content');
    }
}