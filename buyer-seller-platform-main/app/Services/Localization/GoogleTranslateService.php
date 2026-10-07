<?php

namespace App\Services\Localization;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleTranslateService
{
    public function translateBatch(array $texts, string $target): array
    {
        $target = trim($target);
        $texts = array_values($texts);

        if ($target === '' || empty($texts)) {
            return $texts;
        }

        $apiKey = trim((string) config('services.google_translate.api_key'));

        if ($apiKey === '') {
            return $texts;
        }

        $orderedUniqueTexts = collect($texts)
            ->map(fn($text) => trim((string) $text))
            ->filter(fn($text) => $text !== '')
            ->unique()
            ->values();

        $translations = [];
        $missingTexts = [];

        foreach ($orderedUniqueTexts as $text) {
            $cacheKey = $this->cacheKey($target, $text);
            $cached = Cache::get($cacheKey);

            if (is_string($cached) && $cached !== '') {
                $translations[$text] = $cached;
                continue;
            }

            $missingTexts[] = $text;
        }

        foreach (array_chunk($missingTexts, 50) as $chunk) {
            $body = 'target=' . urlencode($target) . '&format=text';
            foreach ($chunk as $text) {
                $body .= '&q=' . urlencode($text);
            }

            $response = Http::acceptJson()
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->withBody($body, 'application/x-www-form-urlencoded')
                ->timeout(20)
                ->post('https://translation.googleapis.com/language/translate/v2?key=' . urlencode($apiKey));

            if (!$response->successful()) {
                continue;
            }

            $items = Collection::make(data_get($response->json(), 'data.translations', []))->values();

            foreach ($chunk as $index => $original) {
                $translated = html_entity_decode(
                    (string) data_get($items, $index . '.translatedText', $original),
                    ENT_QUOTES | ENT_HTML5,
                    'UTF-8'
                );

                $translations[$original] = $translated;
                Cache::put($this->cacheKey($target, $original), $translated, now()->addDays(30));
            }
        }

        return array_map(function ($text) use ($translations) {
            $text = trim((string) $text);

            if ($text === '') {
                return '';
            }

            return $translations[$text] ?? $text;
        }, $texts);
    }

    protected function cacheKey(string $target, string $text): string
    {
        return 'translation:' . $target . ':' . sha1($text);
    }
}
