<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;
use Carbon\Carbon;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Auth\Events\Verified;

class TwoFactorController extends Controller
{
    public function showSendPage() {
        return view('auth.mfa.send');
    }

    public function sendCode(Request $request) {
        $user = Auth::user();

        $key = 'send-code:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->with('lock_seconds', $seconds)
                ->with('error', 'Please wait before requesting another code.');
        }

        RateLimiter::hit($key, 60);

        $code = random_int(100000, 999999);

        TwoFactorCode::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        $user->notify(new TwoFactorCodeNotification($code));

        return redirect()
            ->route('2fa.verify')
            ->with('success', 'Code sent to your email.');
    }

    public function showVerifyPage() {
        return view('auth.mfa.verify');
    }

    public function verifyCode(Request $request) {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $user = Auth::user();
        $record = TwoFactorCode::where('user_id', $user->id)->first();

        if (! $record || !$record->expires_at || Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['code' => 'Code has expired.']);
        }

        if ($record->code != $request->code) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }
        
        // New
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();

            event(new Verified($user));
        }

        // ✅ Handle Remember Browser Option
        if ($request->boolean('remember_browser')) {
            $rememberToken = Str::random(60);

            $record->update([
                'remember_token' => $rememberToken,
                'code' => null,
                'expires_at' => null,
                'verified_token' => null,
            ]);

            // ✅ Persistent 30-day cookie (43200 minutes)
            cookie()->queue(cookie(
                'remember_browser_' . $user->id,
                $rememberToken,
                43200,
                '/',
                null,
                false,
                true,
                false,
                'Lax'
            ));
        } else {
            $verifiedToken = Str::random(64);

            $record->update([
                'verified_token' => $verifiedToken,
                'code' => null,
                'expires_at' => null,
            ]);

            session(['twofa_verified_token' => $verifiedToken]);
        }

        if ($user->usertype === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    public function resendCode() {
        return $this->sendCode(request());
    }
}
