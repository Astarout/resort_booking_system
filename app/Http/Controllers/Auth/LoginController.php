<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');

        $key = 'login-attempts:' . Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()
                ->with('lock_seconds', $seconds)
                ->onlyInput('email');
        }

        $user = User::withTrashed()
            ->where('email', $request->input('email'))
            ->first();

        if ($user && $user->trashed() && \Hash::check($request->input('password'), $user->password)) {
            $request->session()->put('reactivate_user_id', $user->id);

            return redirect()
                ->route('account.reactivate');
        }

        if(Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            RateLimiter::clear($key);

            $current_user = Auth::user();

            if (! $this->isRememberedBrowser($request, $current_user)) {
                return redirect()->route('2fa.send');
            }

            if($current_user->usertype === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        RateLimiter::hit($key, 60);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function isRememberedBrowser(Request $request, $user) {
        $rememberToken = $request
            ->cookie('remember_browser_' . $user->id);

        return $rememberToken && TwoFactorCode::where('user_id', $user->id)
            ->where('remember_token', $rememberToken)
            ->exists();
    }
}
