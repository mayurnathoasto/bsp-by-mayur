<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use App\Models\UserApiToken;
use Illuminate\Http\Request;

class MobileAuthController extends Controller
{
    public function requestOtp(Request $request, \App\Services\SmsService $smsService)
    {
        $country = $this->resolveCountry($request->input('country_code'));

        if ($country === 'IN') {
            $data = $request->validate([
                'phone' => ['required', 'digits:10'],
            ]);

            $identifier = trim($data['phone']);
            $type = 'phone';
        } else {
            $data = $request->validate([
                'email' => ['required', 'email'],
            ]);

            $identifier = strtolower(trim($data['email']));
            $type = 'email';
        }

        $otp = (string) random_int(100000, 999999);

        OtpCode::create([
            'identifier' => $identifier,
            'identifier_type' => $type,
            'country_code' => $country,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send SMS if it's a phone number and country is IN
        if ($type === 'phone' && $country === 'IN') {
            $smsService->sendOtp($identifier, $otp);
        }

        return response()->json([
            'message' => 'OTP generated successfully.',
            'country_code' => $country,
            'identifier_type' => $type,
            'identifier' => $identifier,
            'expires_in_seconds' => 600,
            'dev_otp' => config('app.debug') ? $otp : null,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $country = $this->resolveCountry($request->input('country_code'));

        $rules = [
            'otp' => ['required', 'digits:6'],
        ];

        if ($country === 'IN') {
            $rules['phone'] = ['required', 'digits:10'];
        } else {
            $rules['email'] = ['required', 'email'];
        }

        $data = $request->validate($rules);

        $identifier = $country === 'IN'
            ? trim($data['phone'])
            : strtolower(trim($data['email']));
        $type = $country === 'IN' ? 'phone' : 'email';

        $otpRow = OtpCode::query()
            ->where('identifier', $identifier)
            ->where('identifier_type', $type)
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$otpRow || (string) $otpRow->otp !== (string) $data['otp']) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        $otpRow->update(['used_at' => now()]);

        $loginIdentifier = $type === 'phone'
            ? 'P:' . $identifier
            : 'E:' . $identifier;

        $user = User::where('login_identifier', $loginIdentifier)->first();

        if (!$user) {
            $user = User::create([
                'login_identifier' => $loginIdentifier,
                'phone' => $type === 'phone' ? $identifier : null,
                'email' => $type === 'email' ? $identifier : null,
                'country_code' => $country,
                'name' => $type === 'phone'
                    ? 'User ' . substr($identifier, -4)
                    : 'User',
                'password' => bcrypt(str()->random(20)),
                'seller_status' => User::SELLER_STATUS_NONE,
            ]);
        }

        if ($type === 'phone' && is_null($user->phone_verified_at)) {
            $user->phone_verified_at = now();
            $user->save();
        }

        if ($type === 'email' && is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        $plain = bin2hex(random_bytes(32));

        UserApiToken::create([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plain),
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'message' => 'Authenticated successfully.',
            'access_token' => $plain,
            'user' => $this->transformUser($user->fresh(['businessProfile', 'city'])),
        ]);
    }

    public function me(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->attributes->get('apiUser');

        return response()->json([
            'user' => $this->transformUser($user->loadMissing(['businessProfile', 'city'])),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->attributes->get('apiUserToken');
        if ($token) {
            $token->update(['revoked_at' => now()]);
        }

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    protected function resolveCountry(?string $countryCode): string
    {
        $supported = config('countries.supported', []);
        $default = strtoupper(config('countries.default_country', 'IN'));
        $country = strtoupper(trim((string) $countryCode));

        return isset($supported[$country]) ? $country : $default;
    }

    protected function transformUser(User $user): array
    {
        $businessProfile = $user->businessProfile;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country_code' => strtoupper($businessProfile?->country_code ?? $user->country_code ?? config('countries.default_country', 'IN')),
            'city_name' => $businessProfile?->city_name ?? $user->city?->name,
            'state_name' => $businessProfile?->state_name,
            'seller_status' => $user->seller_status ?? User::SELLER_STATUS_NONE,
            'company_name' => $businessProfile?->company_name ?? $user->company_name,
            'designation' => $user->designation,
            'membership_label' => $user->seller_status === User::SELLER_STATUS_APPROVED ? 'Verified Seller' : 'Free Member',
            'profile_image_url' => $user->profile_photo_path,
        ];
    }
}
