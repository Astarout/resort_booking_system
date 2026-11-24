<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TwoFactorCode;

class LogoutController extends Controller
{
    public function logout(Request $request) {
        if(Auth::check()) {

            $user = Auth::user();

            if ($user) {
                TwoFactorCode::where('user_id', $user->id)->update([
                    'verified_token' => null,
                ]);
            }

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login');
        }
    }
}
