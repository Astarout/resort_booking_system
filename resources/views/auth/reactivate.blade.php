{{-- Reactivation Page --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Reactivate Account</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
    ])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50">
    <form
        action="{{ route('account.reactivate.submit') }}"
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

            <h1 class="text-2xl font-bold text-gray-800">Reactivate Account</h1>

            <p class="text-sm text-gray-500">
                Hi <strong>{{ $user->name }}</strong>, your account is currently deactivated.
            </p>

            @if ($daysLeft > 0)
                <p class="text-red-500 text-sm font-medium mt-2">
                    Your account will be permanently deleted in {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }}.
                </p>
            @else
                <p class="text-red-600 text-sm font-medium mt-2">
                    Your account is scheduled for permanent deletion soon. Please reactivate now.
                </p>
            @endif
        </header>

        {{-- Reactivation Button --}}
        <section class="space-y-3">
            <x-button type="submit" variant="primary" class="w-full py-3 text-lg font-semibold">
                Reactivate My Account
            </x-button>

            <p class="text-center text-sm text-gray-600">
                Not you? <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Go back to login</a>
            </p>

            <p class="text-center text-sm text-gray-600">
                <a href="{{ route('home.landing') }}" class="text-blue-600 hover:underline font-medium">Homepage</a>
            </p>
        </section>
    </form>
</body>

</html>
