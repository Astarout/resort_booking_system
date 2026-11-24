
{{-- Forgot Password Page --}}
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Forgot Password</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body
    class="
        min-h-screen flex items-center justify-center
        bg-gradient-to-br from-blue-100 via-white to-blue-50
    "
>
    @php
        $cooldown = 0;
        if (session('last_password_reset_sent')) {
            $secondsPassed = \Carbon\Carbon::parse(session('last_password_reset_sent'))->diffInSeconds(now());
            $cooldown = max(0, 60 - intval($secondsPassed));
        }
    @endphp

    <form
        method="POST"
        action="{{ route('password.email') }}"
        class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-6 mx-5"
        onsubmit="this.querySelector('button[type=submit]').disabled = true;"
        x-data="{
            cooldown: {{ $cooldown }},
            timer: null,
            startTimer() {
                if(this.cooldown > 0) {
                    this.timer = setInterval(() => {
                        if(this.cooldown > 0) {
                            this.cooldown--;
                        } else {
                            clearInterval(this.timer);
                        }
                    }, 1000);
                }
            },
        }"
        x-init="startTimer()"
    >
        @csrf

        {{-- Header --}}
        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Forgot Password</h1>
            <p class="text-sm text-gray-500">Enter your email to reset your password.</p>
        </header>

        {{-- Status Message --}}
        @if (session('status'))
            <div class="p-3 text-center text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        {{-- Email Field --}}
        <x-text-field
            label="Email"
            name="email"
            type="email"
            id="email"
            value="{{ old('email') }}"
            placeholder="Enter your registered email"
            icon='
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                </svg>
            '
            required
        />

        {{-- Submit --}}
        <x-button type="submit" variant="primary" class="w-full py-2 text-lg font-semibold"
            x-bind:disabled="cooldown > 0"
        >
            <span x-show="cooldown === 0" x-cloak>
                Send Link
            </span>
            <span x-show="cooldown > 0" x-cloak>
                Resend in <span x-text="cooldown"></span>s
            </span>
        </x-button>

        {{-- Back to login --}}
        <p class="text-center text-sm text-gray-600">
            Remembered your password?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Login</a>
        </p>

        {{-- Homepage --}}
        <p class="text-center text-sm text-gray-600">
            <a href="{{ route('home.landing') }}" class="text-blue-600 hover:underline font-medium">Homepage</a>
        </p>
    </form>

</body>

</html>
