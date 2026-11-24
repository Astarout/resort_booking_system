
{{-- Reset Password Page --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Reset Password</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 py-10"
    x-data="{
        password: '',
        confirm: '',
        get rules() {
            return {
                length: this.password.length >= 8 && this.password.length <= 25,
                upper: /[A-Z]/.test(this.password),
                lower: /[a-z]/.test(this.password),
                number: /[0-9]/.test(this.password),
                special: /[^A-Za-z0-9]/.test(this.password),
                match: this.password && this.password === this.confirm,
            }
        },
        get valid() {
            return Object.values(this.rules).every(Boolean);
        },
    }"
>

    <form
        method="POST"
        action="{{ route('password.update') }}"
        class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-6"
        @submit.prevent="
            if (!valid) return;
            if (!window.confirm('Update your password?')) return;
            $el.querySelector('button[type=submit]').disabled = true;
            $el.submit();
        "
    >
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Header --}}
        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Reset Password</h1>
            <p class="text-sm text-gray-500">Enter a new password for your account.</p>
        </header>

        {{-- Email --}}
        <x-text-field
            label="Email"
            name="email"
            type="email"
            id="email"
            value="{{ $email ?? old('email') }}"
            placeholder="Enter your email"
            :readonly="true"
            icon='
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                </svg>
            '
        />

        {{-- Password --}}
        <x-password-field
            label="New Password"
            name="password"
            type="password"
            id="password"
            placeholder="Enter new password"
            x-model="password"
            icon='
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                </svg>
            '
        />

        {{-- Confirm Password --}}
        <x-password-field
            label="Confirm Password"
            name="password_confirmation"
            type="password"
            id="password_confirmation"
            placeholder="Confirm new password"
            x-model="confirm"
            icon='
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                </svg>
            '
        />

        <ul class="text-sm space-y-1">
            <li :class="rules.length ? 'text-green-600' : 'text-red-500'">• 8–25 characters</li>
            <li :class="rules.upper ? 'text-green-600' : 'text-red-500'">• At least one uppercase letter</li>
            <li :class="rules.lower ? 'text-green-600' : 'text-red-500'">• At least one lowercase letter</li>
            <li :class="rules.number ? 'text-green-600' : 'text-red-500'">• At least one number</li>
            <li :class="rules.special ? 'text-green-600' : 'text-red-500'">• At least one special character</li>
            <li :class="rules.match ? 'text-green-600' : 'text-red-500'">• Passwords match</li>
        </ul>

        <x-button type="submit" variant="primary" class="w-full py-3 text-lg font-semibold">
            Reset Password
        </x-button>

        <p class="text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Back to Login</a>
        </p>
    </form>

</body>

</html>
