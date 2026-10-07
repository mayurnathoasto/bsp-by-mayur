<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class OtpAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function requestOtp(Request $request, \App\Services\SmsService $smsService)
    {
        $country = session('country', 'IN');

        if ($country === 'IN') {
            $data = $request->validate([
                'phone' => ['required', 'digits:10'],
            ]);
            $identifier = $data['phone'];
            $type = 'phone';
        } else {
            $data = $request->validate([
                'email' => ['required', 'email'],
            ]);
            $identifier = $data['email'];
            $type = 'email';
        }

        $otp = rand(100000, 999999);

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

        session([
            'otp_identifier' => $identifier,
            'otp_type' => $type,
            'otp_country' => $country,
        ]);

        return redirect()->route('login.verifyOtp');
    }

    public function showVerifyOtp(Request $request)
    {
        $identifier = session('otp_identifier');
        $type = session('otp_type');
        $country = session('otp_country', 'IN');
        $devOtp = session('dev_otp');

        if (!$identifier || !$type) {
            return redirect()->route('login');
        }

        return view('auth.verify_otp', compact(
            'identifier',
            'type',
            'country',
            'devOtp'
        ));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $identifier = session('otp_identifier');
        $type = session('otp_type');
        $country = session('otp_country', 'IN');

        if (!$identifier || !$type) {
            return redirect()->route('login');
        }

        $otpRow = OtpCode::where('identifier', $identifier)
            ->where('identifier_type', $type)
            ->whereNull('used_at')
            ->where('expires_at', '>=', now())
            ->latest()
            ->first();

        if (!$otpRow || $otpRow->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        $otpRow->update(['used_at' => now()]);

        // Build stable unique login identifier (prevents new user every login)
        $loginIdentifier = ($type === 'phone')
            ? 'P:' . trim($identifier)
            : 'E:' . strtolower(trim($identifier));

        $user = User::where('login_identifier', $loginIdentifier)->first();

        if (!$user) {
            $user = User::create([
                'login_identifier' => $loginIdentifier,
                'phone' => $type === 'phone' ? trim($identifier) : null,
                'email' => $type === 'email' ? strtolower(trim($identifier)) : null,
                'country_code' => $country, // keep consistent
                'name' => $type === 'phone'
                    ? ('User ' . substr(trim($identifier), -4))
                    : 'User',
                'password' => bcrypt(str()->random(20)),
            ]);
        }

        // Mark phone/email as verified on successful OTP
        if ($type === 'phone' && is_null($user->phone_verified_at)) {
            $user->phone_verified_at = now();
            $user->save();
        }

        if ($type === 'email' && is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }



        Auth::login($user);

        session()->forget([
            'otp_identifier',
            'otp_type',
            'otp_country',
            'dev_otp',
        ]);

        return redirect()->intended('/');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Google sign-in could not be completed. Please try again.',
                ]);
        }

        $googleId = trim((string) $googleUser->getId());
        $email = strtolower(trim((string) $googleUser->getEmail()));

        if ($googleId === '' || $email === '') {
            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Google account did not return a usable email address.',
                ]);
        }

        $avatar = trim((string) $googleUser->getAvatar()) ?: null;
        $country = strtoupper(session('country', config('countries.default_country', 'IN')));
        $emailVerified = (bool) data_get($googleUser->user, 'email_verified', true);

        $user = User::where('google_id', $googleId)->first();

        if (!$user) {
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => trim((string) $googleUser->getName()) ?: Str::headline(Str::before($email, '@')),
                'email' => $email,
                'login_identifier' => 'E:' . $email,
                'country_code' => $country,
                'password' => Str::random(40),
                'email_verified_at' => $emailVerified ? now() : null,
                'google_id' => $googleId,
                'profile_photo_path' => $avatar,
            ]);
        } else {
            if (!empty($user->google_id) && $user->google_id !== $googleId) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'login' => 'This email is already linked to another Google account.',
                    ]);
            }

            $user->google_id = $googleId;
            $user->email ??= $email;
            $user->country_code = $user->country_code ?: $country;

            if (empty($user->login_identifier) && !empty($user->email)) {
                $user->login_identifier = 'E:' . strtolower(trim($user->email));
            }

            if ($emailVerified && is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }

            if (blank($user->name)) {
                $user->name = trim((string) $googleUser->getName()) ?: Str::headline(Str::before($email, '@'));
            }

            if (blank($user->profile_photo_path) && $avatar) {
                $user->profile_photo_path = $avatar;
            }

            $user->save();
        }

        Auth::login($user, true);

        session()->forget([
            'otp_identifier',
            'otp_type',
            'otp_country',
            'dev_otp',
        ]);

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
