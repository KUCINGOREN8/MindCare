<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected $apiKey;
    protected $organizationId;
    protected $baseUrl = 'https://api.openai.com/v1';
    protected $model = 'gpt-4o';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key') ?? env('PUBLIC_OPENAI_API_KEY');
        $this->organizationId = config('services.openai.org_id') ?? env('PUBLIC_OPENAI_ORG_KEY');

        if (!$this->apiKey) {
            throw new \Exception('OpenAI API key not configured. Check .env file.');
        }
    }

    public function generateDailyTip(string $mood = null, int $streakDays = 0): ?string
    {
        try {
            $moodContext = $mood ? "The user is feeling {$mood} today." : "No mood data available.";

            $prompt = "As a mental health assistant, provide a short, encouraging daily wellness tip (max 2 sentences).
                      Context: {$moodContext} User has maintained a {$streakDays}-day mood tracking streak.
                      Make it personalized, practical, and uplifting.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'OpenAI-Organization' => $this->organizationId,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a compassionate mental wellness coach. Provide brief, actionable tips.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 150,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'] ?? null;
            }

        } catch (\Exception $e) {
            Log::error('OpenAI Service Exception: ' . $e->getMessage());
        }

        return $this->getFallbackTip($mood);
    }

    protected function getFallbackTip(?string $mood): string
    {
        $fallbackTips = [
            'sad' => 'Try taking 5 deep breaths and writing down 3 things you\'re grateful for today.',
            'flat' => 'A short 10-minute walk outside can help boost your energy and mood.',
            'good' => 'Keep up the positive momentum! Consider trying a new hobby or activity.',
            'happy' => 'Your positive energy is contagious! Share a smile with someone today.',
            'blissful' => 'Savor this moment of happiness. Consider journaling about what made today special.',
            'default' => 'Take a moment to practice mindfulness. Focus on your breathing for 60 seconds.'
        ];

        return $fallbackTips[$mood] ?? $fallbackTips['default'];
    }

    public function generateAchievementMessage(int $streakDays): string
    {
        if ($streakDays >= 30) {
            return "Amazing! You've tracked your mood for a full month! 🌟 Consistency is key to self-awareness.";
        } elseif ($streakDays >= 7) {
            return "Great job! {$streakDays}-day streak! You're building a powerful self-care habit. 🔥";
        } elseif ($streakDays >= 3) {
            return "Nice! {$streakDays} days in a row! Keep up the good work tracking your feelings. 💪";
        }

        return "You're on a {$streakDays}-day streak! Every day of awareness counts. 🌱";
    }
}
