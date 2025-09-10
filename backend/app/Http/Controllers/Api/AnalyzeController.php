<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AnalyzeController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->validate([
            'link' => 'required|url',
            'summary' => 'nullable|string',
            'text' => 'required|string',
        ]);

        try {
            $risks = $this->analyzeWithLLM($data['text']);

            // Tu lahko shraniš članek in tveganja v bazo, če želiš
            // Article::create([...]); RiskRegister::sync($risks);

            return response()->json([
                'link' => $data['link'],
                'summary' => $data['summary'],
                'risks' => $risks
            ]);
        } catch (\Exception $e) {
            Log::error('Napaka pri analizi članka: ' . $e->getMessage());
            return response()->json(['error' => 'Napaka pri analizi članka'], 500);
        }
    }

    protected function analyzeWithLLM(string $text): array
    {
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a risk analyst. Return JSON.'],
                    ['role' => 'user', 'content' => "Analyze this article and return risks:\n" . $text]
                ],
                'temperature' => 0.2
            ]);

        if (!$response->successful()) {
            throw new \Exception('LLM API ni vrnil uspešnega odgovora');
        }

        $content = $response->json()['choices'][0]['message']['content'];
        return json_decode($content, true);
    }
}