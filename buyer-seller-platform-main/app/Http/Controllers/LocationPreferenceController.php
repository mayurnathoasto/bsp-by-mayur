<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\Currency\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocationPreferenceController extends Controller
{
    public function __construct(protected ExchangeRateService $exchangeRates)
    {
    }

    public function setCountry(Request $request): RedirectResponse
    {
        $country = $this->normalizeCountry((string) $request->input('country', ''));
        $supportedCountries = config('countries.supported', []);

        [$country] = $this->applyCountrySession($country, true);

        $existingCityCountry = strtoupper((string) session('city_country_code', ''));

        if (
            ($supportedCountries[$country]['city_selector'] ?? false) !== true ||
            ($existingCityCountry !== '' && $existingCityCountry !== $country)
        ) {
            $this->clearCitySession();
        }

        return redirect($this->redirectUrlForCountry($country));
    }

    public function setCurrency(Request $request): RedirectResponse
    {
        $currency = strtoupper(trim((string) $request->input('currency', '')));
        $supportedCurrencies = $this->exchangeRates->supportedCurrencies();

        if (!in_array($currency, $supportedCurrencies, true)) {
            return back()->with('error', 'Invalid currency selected.');
        }

        session([
            'currency' => $currency,
            'currency_source' => 'manual',
        ]);

        return back();
    }

    public function setCity(Request $request): RedirectResponse
    {
        $cityId = $request->input('city_id');
        $cityName = trim((string) $request->input('city_name', ''));
        $pincode = trim((string) $request->input('pincode', ''));
        $stateName = trim((string) $request->input('state_name', ''));
        $countryCode = $this->normalizeCountry((string) $request->input('country_code', (string) session('country', config('countries.default_country', 'IN'))));

        if (!empty($cityId) && $countryCode === 'IN') {
            $city = City::find((int) $cityId);

            if (!$city) {
                return back()->with('error', 'Invalid city selected.');
            }

            session([
                'city_id' => $city->id,
                'city_name' => $city->name,
                'city_pincode' => $city->pincode,
                'city_state_name' => $stateName !== '' ? $stateName : session('city_state_name'),
                'city_country_code' => 'IN',
            ]);

            return back();
        }

        if ($cityName === '') {
            return back()->with('error', 'Please select a city.');
        }

        session([
            'city_id' => null,
            'city_name' => $cityName,
            'city_pincode' => $pincode !== '' ? $pincode : null,
            'city_state_name' => $stateName !== '' ? $stateName : null,
            'city_country_code' => $countryCode,
        ]);

        return back();
    }

    public function detectLocation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'city_name' => ['required', 'string', 'max:120'],
            'country_code' => ['required', 'string', 'size:2'],
            'state_name' => ['nullable', 'string', 'max:120'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        $countryAliases = config('countries.aliases', []);
        $supportedCountries = config('countries.supported', []);
        $country = strtoupper(trim($data['country_code']));
        $country = $countryAliases[$country] ?? $country;

        if (!isset($supportedCountries[$country]) || empty($supportedCountries[$country]['live'])) {
            return response()->json([
                'message' => 'Detected country is not available in the marketplace yet.',
            ], 422);
        }

        [, $currency] = $this->applyCountrySession($country, true);

        session([
            'city_id' => null,
            'city_name' => trim($data['city_name']),
            'city_pincode' => filled($data['pincode'] ?? null) ? trim((string) $data['pincode']) : null,
            'city_state_name' => filled($data['state_name'] ?? null) ? trim((string) $data['state_name']) : null,
            'city_country_code' => $country,
        ]);

        return response()->json([
            'ok' => true,
            'country' => $country,
            'currency' => $currency,
            'city_name' => trim($data['city_name']),
            'redirect' => $this->redirectUrlForCountry($country),
        ]);
    }

    protected function normalizeCountry(string $country): string
    {
        $defaultCountry = strtoupper(config('countries.default_country', 'IN'));
        $supportedCountries = config('countries.supported', []);
        $countryAliases = config('countries.aliases', []);
        $country = strtoupper(trim($country));
        $country = $countryAliases[$country] ?? $country;

        if ($country === '' || !isset($supportedCountries[$country]) || empty($supportedCountries[$country]['live'])) {
            return $defaultCountry;
        }

        return $country;
    }

    protected function applyCountrySession(string $country, bool $promptForLanguage = false): array
    {
        $supportedCountries = config('countries.supported', []);
        $country = $this->normalizeCountry($country);
        $currency = $supportedCountries[$country]['currency'] ?? config('countries.default_currency', 'INR');

        session([
            'country' => $country,
            'currency' => $currency,
            'currency_source' => 'auto',
        ]);

        if ($promptForLanguage) {
            $this->syncLanguageStateForCountry($country);
        }

        return [$country, $currency];
    }

    protected function syncLanguageStateForCountry(string $country): void
    {
        $countryConfig = config('countries.supported.' . $country, []);
        $targetLanguage = trim((string) ($countryConfig['language_target'] ?? ''));
        $currentLanguage = trim((string) session('language', 'en'));
        $currentLanguageCountry = strtoupper((string) session('language_country', ''));

        if ($targetLanguage === '') {
            session([
                'language' => 'en',
                'language_name' => 'English',
                'language_country' => null,
                'language_direction' => 'ltr',
            ]);

            return;
        }

        if ($currentLanguage === $targetLanguage && $currentLanguageCountry === $country) {
            return;
        }

        session([
            'language' => 'en',
            'language_name' => 'English',
            'language_country' => null,
            'language_direction' => 'ltr',
        ]);

        session()->flash('language_prompt', [
            'country' => $country,
            'country_name' => $countryConfig['name'] ?? $country,
            'language' => $targetLanguage,
            'language_name' => $countryConfig['language_name'] ?? strtoupper($targetLanguage),
        ]);
    }

    protected function clearCitySession(): void
    {
        session()->forget([
            'city_id',
            'city_name',
            'city_pincode',
            'city_state_name',
            'city_country_code',
        ]);
    }

    protected function redirectUrlForCountry(string $country): string
    {
        return $country === 'IN'
            ? url('/')
            : url('/' . strtolower($country));
    }
}
