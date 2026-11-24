<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    public function sendEmailVerification(Request $request) {
        if(Auth::guard('web')->check()) {

            $request->user('web')->sendEmailVerificationNotification();

            session(['last_verification_email_sent' => now()]);

            return redirect()
                ->back()
                ->with('success', 'Email Sent Successfully');
        }
    }

    public function verify(EmailVerificationRequest $request) {
        if(Auth::guard('web')->check()) {
            $request->fulfill();
            return redirect()
                ->back()
                ->with('success', 'Email Sent Successfully');
        }
    }
}
