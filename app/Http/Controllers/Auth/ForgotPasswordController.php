<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\SuccessfulPasswordReset;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordRequestForm() {
        return view('auth.forgot-password.forget-page');
    }

    private function sendResetEmail($user, $token) {
        $user->sendPasswordResetNotification($token);
    }

    public function sendPasswordResetLink(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            session(['last_password_reset_sent' => now()]);
            return back()
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Check cooldown
        if (session('last_password_reset_sent')) {
            $secondsPassed = Carbon::parse(session('last_password_reset_sent'))->diffInSeconds(now());
            if ($secondsPassed < 60) {
                return back()
                    ->with('status', 'Please wait ' . (60 - $secondsPassed) . ' seconds before requesting again.');
            }
        }

        // Send reset link
        $status = Password::broker('users')
            ->sendResetLink(
                $request->only('email'),
                function($user, $token) {
                    $this->sendResetEmail($user, $token);
                }
            );

        if ($status === Password::RESET_LINK_SENT) {
            session(['last_password_reset_sent' => now()]);
            return back()->with(['status' => __($status)]);
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, $token = null) {
        return view('auth.forgot-password.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->notify(new SuccessfulPasswordReset());
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
