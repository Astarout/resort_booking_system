{{-- Verify Code Page --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Verify Code</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-4 mx-5">
        {{-- Header --}}
        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Verify Code</h1>
            <p class="text-sm text-gray-500">
                We’ve sent a 6-digit code to your email. Enter it below to continue.
            </p>
        </header>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <p id="countdown" class="text-center text-red-500 font-semibold mt-2"></p>

        {{-- Verify Form --}}
        <form
            action="{{ route('2fa.verify') }}"
            method="POST"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;"
            class="space-y-6"
        >
            @csrf

            {{-- Input Field --}}
            <section class="space-y-4">
                <x-text-field
                    label="Verification Code"
                    name="code"
                    type="text"
                    id="code"
                    value="{{ old('email') }}"
                    placeholder="Enter 6-digit code"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg>
                    '
                    required
                />

                <label class="flex items-center text-gray-600 text-sm">
                    <input
                        type="checkbox"
                        name="remember_browser"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <span class="ml-2">Remember this browser for 30 days</span>
                </label>
            </section>

            {{-- Confirm Code --}}
            <x-button type="submit" variant="primary" class="w-full py-3 text-lg font-semibold">
                Confirm Code
            </x-button>
        </form>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;"
        >
            @csrf
            <x-button
                type="submit"
                variant="secondary"
                class="w-full py-3 text-lg font-semibold"
            >Logout</x-button>
        </form>

        {{-- Resend Code --}}
        <form action="{{ route('2fa.resend') }}" method="POST" class="text-center"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;"
        >
            @csrf
            <button type="submit" class="text-blue-600 hover:underline text-sm">
                Resend Code
            </button>
        </form>
    </div>

    <script>
        const LOCK_KEY = 'send_code_lock_until';
        const button = document.querySelector('form[action="{{ route('2fa.resend') }}"] button');
        const message = document.getElementById('countdown');

        const now = Math.floor(Date.now() / 1000);
        let lockUntil = localStorage.getItem(LOCK_KEY);

        @if(session('lock_seconds'))
            if (!lockUntil) {
                lockUntil = now + {{ session('lock_seconds') }};
                localStorage.setItem(LOCK_KEY, lockUntil);
            }
        @endif

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

            button.disabled = true;
            button.classList.add("opacity-50", "cursor-not-allowed");

            const minutes = Math.floor(remaining / 60);
            const seconds = remaining % 60;
            message.textContent = `Please wait ${minutes}:${seconds.toString().padStart(2, '0')} before resending.`;

            setTimeout(updateTimer, 1000);
        }

        if (lockUntil) {
            updateTimer();
        }
    </script>
</body>

</html>
