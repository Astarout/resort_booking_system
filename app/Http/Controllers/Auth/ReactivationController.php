<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReactivationController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->session()->get('reactivate_user_id');

        if (! $userId) {
            return redirect()
                ->route('login');
        }

        $user = User::withTrashed()
            ->find($userId);

        if (! $user) {
            return redirect()
                ->route('login');
        }

        $permanentDays = 30;
        $deletedAt = $user->deleted_at;

        $daysLeft = null;

        if ($deletedAt) {
            $daysSinceDeleted = now()->diffInDays($deletedAt);
            $daysLeft = max($permanentDays - $daysSinceDeleted, 0);
        }

        return view('auth.reactivate', compact('user', 'daysLeft'));
    }

    public function reactivate(Request $request)
    {
        $userId = $request->session()->get('reactivate_user_id');

        $user = User::withTrashed()->find($userId);

        if ($user) {
            $user->restore();

            Auth::login($user);

            $request->session()->forget('reactivate_user_id');

            if ($user->usertype === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Your admin account has been reactivated!');
            }

            return redirect()
                ->route('user.dashboard')
                ->with('success', 'Your account has been reactivated!');
        }

        return redirect()
            ->route('login')->withErrors(['email' => 'Unable to reactivate account.']);
    }
}

