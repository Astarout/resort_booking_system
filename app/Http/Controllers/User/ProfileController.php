<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Rating;
use App\Notifications\SuccessfulPasswordChange;
use App\Services\ProfanityFilterService;

class ProfileController extends Controller
{
    public function index() {
        return view('user.profile');
    }

    public function updateProfile(Request $request) {
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'middle_name' => ['nullable', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[\p{L}]+(?:\s[\p{L}]+)*$/u'],
            'address' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ]);

        # dd( $validatedData );

        $user_id = Auth::user()->id;

        $user = User::findOrFail($user_id);

        if ($request->hasFile('avatar')) {

            $userFolder = 'profiles/users/' . $user->email;

            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new image
            $filePath = $request->file('avatar')->store($userFolder, 'public');

            $user->avatar = $filePath;
        }

        $user->first_name = $validatedData['first_name'];
        $user->middle_name = $validatedData['middle_name'];
        $user->last_name = $validatedData['last_name'];
        $user->address = $validatedData['address'];
        $user->save();

        return redirect()
            ->route('user.profile')
            ->with('success', 'Profile Updated Successfully');
    }

    public function changePassword(Request $request) {
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', 'min:8', 'max:25'],
            'password' => ['required', 'string', 'min:8', 'max:25'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:25'],
        ]);

        $user = Auth::guard('web')->user();

        // Check if the old password is correct
        if (!Hash::check($request->current_password, $user->password)) {
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
        $user->password = Hash::make($request->password);

        $user->save();

        $user->notify(new SuccessfulPasswordChange());

        return redirect()
            ->route('user.profile')
            ->with('success', 'Password updated successfully!');
    }

    public function submitReview(Request $request, ProfanityFilterService $profanityFilterService) {

        $user_id = Auth::user()->id;

        # dd($request->all());

        if($request->filled('rate') && $request->rate == 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Your rating is empty!');
        }

        $request->validate([
            'rate' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->filled('comment') && $profanityFilterService->contains($request->comment)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Your review contains inappropriate words.');
        }

        try {
            DB::beginTransaction();

            $rating = Rating::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'rate' => $request->rate,
                    'comment' => $request->comment,
                ]
            );

            # dd($rating);

            DB::commit();

            return redirect()
                ->route('user.profile')
                ->with('success', 'Your review has been submitted successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'An error occurred while submitting your review. Please try again later.');
        }
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
