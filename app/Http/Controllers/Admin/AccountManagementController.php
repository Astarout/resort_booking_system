<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountManagementController extends Controller
{
    public function index() {
        $current_user = Auth::user();

        $users = User::where('id', '!=', $current_user->id)
            ->orderBy('last_active_at', 'desc')
            ->paginate(30);

        return view('admin.account', [
            'users' => $users,
        ]);
    }

    public function verifyAccount($id) {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            // Update verified status
            $user->verified = 1;
            $user->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'User account has been verified successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Verification failed: ' . $e->getMessage());
        }
    }

    public function markAccountToBeDeleted($id) {
        $admin = Auth::user();

        if (!$admin || !$admin->usertype === 'admin') {
            return back()->with('error', 'Unauthorized action.');
        }

        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Remove remember token and soft delete the user
        $user->setRememberToken(null);
        $user->delete();

        return back()
            ->with('success', "User account '{$user->first_name}' has been deactivated. They have 30 days to reactivate before permanent deletion.");
    }

    public function deactivate($id) {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->verified = 0;
            $user->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'User account has been deactivated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to deactivate user account: ' . $e->getMessage());
        }
    }
}
