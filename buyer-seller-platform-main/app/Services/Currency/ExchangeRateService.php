<?php

namespace App\Services\Currency;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function supportedCurrencies(): array
    {
        $supportedCountries = config('countries.supported', []);

        $currencies = collect($supportedCountries)
            ->pluck('currency')
            ->filter()
            ->map(fn(string $currency) => strtoupper($currency))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return $currencies;
    }

    public function defaultCurrencyForCountry(?string $country): string
    {
        $country = strtoupper((string) $country);
        $supportedCountries = config('countries.supported', []);

        return strtoupper(
            $supportedCountries[$country]['currency']
            ?? config('countries.default_currency', 'INR')
        );
    }

    public function convert(?float $amount, ?string $fromCurrency, ?string $toCurrency): ?float
    {
        if ($amount === null) {
            return null;
        }

        $fromCurrency = strtoupper(trim((string) $fromCurrency));
        $toCurrency = strtoupper(trim((string) $toCurrency));

        if ($fromCurrency === '' || $toCurrency === '' || $fromCurrency === $toCurrency) {
            return (float) $amount;
        }

        $rate = $this->rate($fromCurrency, $toCurrency);

        if ($rate === null) {
            return (float) $amount;
        }

        return round(((float) $amount) * $rate, 2);
    }

    public function format(?float $amount, ?string $currency, int $decimals = 2): ?string
    {
        if ($amount === null) {
            return null;
        }

        $currency = strtoupper(trim((string) $currency));

        if ($currency === '') {
            $currency = config('countries.default_currency', 'INR');
        }

        return $currency . ' ' . number_format((float) $amount, $decimals);
    }

    public function rate(string $fromCurrency, string $toCurrency): ?float
    {
        $fromCurrency = strtoupper(trim($fromCurrency));
        $toCurrency = strtoupper(trim($toCurrency));

        if ($fromCurrency === '' || $toCurrency === '') {
            return null;
        }

        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $cacheKey = sprintf('currency-rate:%s:%s', $fromCurrency, $toCurrency);

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($fromCurrency, $toCurrency) {
            $apiKey = trim((string) config('services.currency_api.key'));
            $baseUrl = rtrim((string) config('services.currency_api.base_url', 'https://api.currencyapi.com'), '/');

            if ($apiKey === '') {
                return null;
            }

            $response = Http::acceptJson()
                ->timeout(10)
                ->get($baseUrl . '/v3/latest', [
                    'apikey' => $apiKey,
                    'base_currency' => $fromCurrency,
                    'currencies' => $toCurrency,
                ]);

            if (!$response->successful()) {
                return null;
            }

            $value = data_get($response->json(), 'data.' . $toCurrency . '.value');

            return is_numeric($value) ? (float) $value : null;
        });
    }
}
