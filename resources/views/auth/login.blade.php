
{{-- Login Page --}}
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Login</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
    ])

</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50">
    <form
        action="{{ route('login') }}"
        method="POST"
        class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-6 mx-5"
        onsubmit="this.querySelector('button[type=submit]').disabled = true;"
    >
        @csrf

        {{-- Header --}}
        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Login Here</h1>

            <p class="text-sm text-gray-500">Welcome back! Please login to your account.</p>
        </header>

        {{-- Fields --}}
        <section class="space-y-4">
            <x-text-field
                label="Email"
                name="email"
                type="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                '
                required
            />

            <x-password-field
                label="Password"
                name="password"
                type="password"
                id="password"
                value="{{ old('password') }}"
                placeholder="Enter your password"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                    </svg>
                '
                required
            />

            {{-- Remember Me + Forgot Password --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm sm:text-md text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm sm:text-md text-blue-600 hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>
        </section>

        {{-- Submit + Register Link --}}
        <section class="space-y-3">
            <x-button type="submit" variant="primary" class="w-full py-3 text-lg font-semibold">
                Login
            </x-button>

            <p class="text-center text-sm text-gray-600">
                Don’t have an account?
                <a href="{{ route('user.register') }}" class="text-blue-600 hover:underline font-medium">Register</a>
            </p>

            <p class="text-center text-sm text-gray-600">
                <a href="{{ route('home.landing') }}" class="text-blue-600 hover:underline font-medium">Homepage</a>
            </p>
        </section>
    </form>

    @if (session('lock_seconds'))
        <script>
            const LOCK_KEY = 'login_lock_until';
            const now = Math.floor(Date.now() / 1000);
            let lockUntil = localStorage.getItem(LOCK_KEY);

            if (!lockUntil) {
                lockUntil = now + {{ session('lock_seconds') }};
                localStorage.setItem(LOCK_KEY, lockUntil);
            }

            const button = document.querySelector('button[type=submit]');
            const form = document.querySelector('form');
            const message = document.createElement('p');
            message.className = "text-center text-red-500 font-semibold mt-2";
            form.appendChild(message);

            function updateTimer() {
                const currentTime = Math.floor(Date.now() / 1000);
                const remaining = lockUntil - currentTime;

                if (remaining <= 0) {
                    localStorage.removeItem(LOCK_KEY);
                    button.disabled = false;
                    button.classList.remove("opacity-50", "cursor-not-allowed");
                    message.textContent = '';
                    return;
                }

                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                message.textContent = `Too many failed attempts. Please wait ${minutes}:${seconds.toString().padStart(2, '0')} before trying again.`;

                button.disabled = true;
                button.classList.add("opacity-50", "cursor-not-allowed");

                setTimeout(updateTimer, 1000);
            }

            updateTimer();
        </script>
    @else
        <script>
            const LOCK_KEY = 'login_lock_until';
            const lockUntil = localStorage.getItem(LOCK_KEY);

            if (lockUntil) {
                const now = Math.floor(Date.now() / 1000);
                const remaining = lockUntil - now;

                if (remaining > 0) {
                    const button = document.querySelector('button[type=submit]');
                    const form = document.querySelector('form');
                    const message = document.createElement('p');
                    message.className = "text-center text-red-500 font-semibold mt-2";
                    form.appendChild(message);

                    button.disabled = true;
                    button.classList.add("opacity-50", "cursor-not-allowed");

                    function updateTimer() {
                        const currentTime = Math.floor(Date.now() / 1000);
                        const remaining = lockUntil - currentTime;

                        if (remaining <= 0) {
                            localStorage.removeItem(LOCK_KEY);
                            button.disabled = false;
                            button.classList.remove("opacity-50", "cursor-not-allowed");
                            message.textContent = '';
                            return;
                        }

                        const minutes = Math.floor(remaining / 60);
                        const seconds = remaining % 60;
                        message.textContent = `Too many failed attempts. Please wait ${minutes}:${seconds.toString().padStart(2, '0')} before trying again.`;

                        setTimeout(updateTimer, 1000);
                    }

                    updateTimer();
                } else {
                    localStorage.removeItem(LOCK_KEY);
                }
            }
        </script>
    @endif
</body>

</html>
