<?php

namespace App\Http\Controllers;

use App\Services\Localization\GoogleTranslateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function setLanguagePreference(Request $request): JsonResponse
    {
        $data = $request->validate([
            'country' => ['required', 'string', 'size:2'],
            'accept' => ['required', 'boolean'],
        ]);

        $country = strtoupper(trim($data['country']));
        $config = config('countries.supported.' . $country, []);
        $targetLanguage = (string) ($config['language_target'] ?? '');

        if ($targetLanguage === '') {
            return response()->json([
                'message' => 'Selected country does not support translated mode.',
            ], 422);
        }

        if ((bool) $data['accept'] === true) {
            session([
                'language' => $targetLanguage,
                'language_name' => $config['language_name'] ?? strtoupper($targetLanguage),
                'language_country' => $country,
                'language_direction' => ($config['language_rtl'] ?? false) ? 'rtl' : 'ltr',
            ]);

            return response()->json([
                'ok' => true,
                'language' => session('language'),
                'direction' => session('language_direction', 'ltr'),
            ]);
        }

        session([
            'language' => 'en',
            'language_name' => 'English',
            'language_country' => null,
            'language_direction' => 'ltr',
        ]);

        return response()->json([
            'ok' => true,
            'language' => 'en',
            'direction' => 'ltr',
        ]);
    }

    public function translate(Request $request, GoogleTranslateService $translator): JsonResponse
    {
        $data = $request->validate([
            'texts' => ['required', 'array', 'min:1', 'max:200'],
            'texts.*' => ['nullable', 'string', 'max:5000'],
            'target' => ['required', 'string', 'max:10'],
        ]);

        $activeLanguage = trim((string) session('language', 'en'));
        $target = trim((string) $data['target']);

        if ($activeLanguage === 'en' || $target !== $activeLanguage) {
            return response()->json([
                'translations' => $data['texts'],
            ]);
        }

        return response()->json([
            'translations' => $translator->translateBatch($data['texts'], $target),
        ]);
    }
}
