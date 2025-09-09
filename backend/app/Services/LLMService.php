<?php
// app/Services/LLMService.php
namespace App\Services;

class LLMService
{
    public function analyzeArticle(string $text): array
    {
        // Simulacija LLM analize
        return [
            [
                'name' => 'Naravna nesreča',
                'slug' => 'natural-disaster',
                'category' => 'okolje',
                'confidence' => 0.94
            ],
            [
                'name' => 'Humanitarna kriza',
                'slug' => 'humanitarian-crisis',
                'category' => 'družba',
                'confidence' => 0.81
            ]
        ];
    }
}
