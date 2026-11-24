<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Http\Requests\Auth\RegistrationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRegisterController extends Controller
{
    public function index() {
        return view('auth.user-register');
    }

    public function register(RegistrationRequest $request) {
        # validate the request
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            # Generate User ID
            $user_id = 'USR' . now()->format('YmdHi') . rand(100, 999);

            $avatar_path = null;

            User::create([
                'id' => $user_id,
                'first_name' => $validatedData['first_name'],
                'middle_name' => $validatedData['middle_name'] ?? null,
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'password' => Hash::make($validatedData['password']),
                'avatar' => $avatar_path,
                'verified' => true,
                'usertype' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Registration Successful');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }
}
