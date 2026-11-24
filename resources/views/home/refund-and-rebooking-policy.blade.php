
{{-- Refund and Rebooking Policy Page --}}
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | Refund and Rebooking Policy</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-b from-gray-50 to-gray-100 text-gray-800 font-sans leading-relaxed">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-8 shadow-lg">
        <div class="max-w-4xl mx-auto px-4 text-center">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('storage/icons/Logo.jpg') }}"
                     alt="Casa Alvadon Logo"
                     class="w-20 h-20 md:w-24 md:h-24 rounded-full shadow-md border-4 border-white">
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide">Refund & Rebooking Policy</h1>
            <p class="mt-2 text-sm md:text-base text-blue-100">
                Important booking guidelines for your reservation
            </p>
        </div>
    </header>

    <!-- Main -->
    <main class="flex-grow max-w-3xl mx-auto px-6 py-12">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-700 shadow-md rounded-xl p-8">
            <ul class="list-disc list-inside text-gray-700 space-y-6 text-lg">
                <li>
                    <span class="font-semibold underline">No Refunds:</span>
                    All reserved bookings are <span class="font-semibold text-red-600">non-refundable</span>.
                </li>
                <li>
                    <span class="font-semibold underline">Rebooking:</span>
                    Rebooking is allowed only if requested at least
                    <span class="font-semibold text-blue-800 underline">one week before the original booking date</span>.
                    <p class="mt-2 ml-6 text-gray-700">
                        Please advise us accordingly — requests made less than one week before
                        will <span class="font-semibold text-red-600">not be accommodated</span>.
                    </p>
                </li>
            </ul>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-6 shadow-inner">
        <div class="max-w-5xl mx-auto text-center text-sm tracking-wide">
            &copy; 2025 Casa Alvadon. All rights reserved.
        </div>
    </footer>

</body>

</html>
