<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;

class EnsureTwoFactorIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $record = TwoFactorCode::where('user_id', $user->id)->first();

        if (! $record) {
            return redirect()->route('2fa.send');
        }

        $rememberCookie = $request->cookie('remember_browser_' . $user->id);
        if ($rememberCookie && $record->remember_token === $rememberCookie) {
            return $next($request);
        }

        $sessionToken = session('twofa_verified_token');
        if ($record->verified_token && $sessionToken && hash_equals($record->verified_token, $sessionToken)) {
            return $next($request);
        }

        return redirect()->route('2fa.send');
    }
}
