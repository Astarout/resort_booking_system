<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Notifications\SuccessfulPasswordChange;

class ProfileController extends Controller
{
    public function index() {
        return view('admin.profile');
    }

    public function updateProfile(Request $request) {
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'middle_name' => ['nullable', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'address' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        # dd( $validatedData );

        $user_id = Auth::user()->id;

        $admin = User::findOrFail($user_id);

        if ($request->hasFile('avatar')) {

            $userFolder = 'profiles/admin/' . $admin->email;

            // Delete old avatar if it exists
            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            // Store new image
            $filePath = $request->file('avatar')->store($userFolder, 'public');

            $admin->avatar = $filePath;
        }

        $admin->first_name = $validatedData['first_name'];
        $admin->middle_name = $validatedData['middle_name'];
        $admin->last_name = $validatedData['last_name'];
        $admin->address = $validatedData['address'];
        $admin->save();

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile Updated Successfully');
    }

    public function changePassword(Request $request) {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', 'min:8', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'max:25'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:25'],
        ]);

        $admin = Auth::guard('web')->user();

        // Check if the old password is correct
        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()
                ->back()
                ->with('password_error', 'The old password is incorrect.');
        }

        // Check if the user is using his old password as new password
        if($validatedData['current_password'] == $validatedData['password']) {
            return redirect()
                ->back()
                ->with('password_error', 'You can\'t use your old password');
        }

        // Check if the password match
        if($validatedData['password'] != $validatedData['password_confirmation']) {
            return redirect()
                ->back()
                ->with('password_error', 'The passwords doesn\'t match');
        }

        // Update the password
        $admin->password = Hash::make($request->password);

        $admin->save();

        $admin->notify(new SuccessfulPasswordChange());

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Password updated successfully!');
    }

    public function markAccountToBeDeleted(Request $request) {
        $user = Auth::user();

        if (!$user) {
            return back()
                ->with('error', 'User not found.');
        }

        if (!Hash::check($request->delete_password, $user->password)) {
            return back()
                ->with('error', 'Incorrect password. Please try again.');
        }

        $user->setRememberToken(null);
        $user->delete();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Your account has been deactivated. You have 30 days to reactivate it by logging in again before it’s permanently deleted.');
    }
}
