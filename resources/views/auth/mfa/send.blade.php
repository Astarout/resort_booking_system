{{-- Send Code Page --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Two-Factor Authentication</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-4 mx-5 text-center">
        {{-- Send Code Form --}}
        <form
            action="{{ route('2fa.send') }}"
            method="POST"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;"
            class="space-y-6"
        >
            @csrf

            {{-- Header --}}
            <header class="space-y-2">
                <div class="flex justify-center">
                    <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
                </div>

                <h1 class="text-2xl font-bold text-gray-800">Send Code</h1>
                <p class="text-sm text-gray-500">
                    For your security, please send a verification code to your registered email.
                </p>
            </header>

            <p id="countdown" class="text-center text-red-500 font-semibold mt-2"></p>

            {{-- Send Code Button --}}
            <x-button type="submit" variant="primary" class="w-full py-3 text-lg font-semibold">
                Send Email
            </x-button>
        </form>

        {{-- Logout Button --}}
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
    </div>

    <script>
        const LOCK_KEY = 'send_code_lock_until';
        const button = document.querySelector('button[type=submit]');
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
