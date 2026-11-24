{{-- Guidelines Page --}}
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | Guidelines</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-b from-gray-50 to-gray-100 text-gray-800 font-sans leading-relaxed">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-8 shadow-lg">
        <div class="max-w-5xl mx-auto px-4 text-center">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('storage/icons/Logo.jpg') }}"
                     alt="Casa Alvadon Logo"
                     class="w-20 h-20 md:w-24 md:h-24 rounded-full shadow-md border-4 border-white">
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide">Planning a visit to Casa Alvadon?</h1>
            <p class="mt-2 text-sm md:text-base text-blue-100">
                Here’s a quick guide to help you enjoy a smooth and relaxing stay with us!
            </p>
        </div>
    </header>

    <!-- Main -->
    <main class="max-w-5xl mx-auto px-6 py-12 space-y-10">

        <!-- Tour & Stay Schedule -->
        <section>
            <h2 class="text-2xl font-bold text-blue-900 mb-4">Schedules</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="bg-white shadow-md rounded-xl p-4 border-l-4 border-blue-700">
                    <h3 class="font-semibold text-blue-800">Day Tour</h3>
                    <p class="text-gray-600">8:00 AM – 5:00 PM</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-4 border-l-4 border-blue-700">
                    <h3 class="font-semibold text-blue-800">Night Tour</h3>
                    <p class="text-gray-600">8:00 PM – 6:00 AM</p>
                </div>
                <div class="bg-white shadow-md rounded-xl p-4 border-l-4 border-blue-700 sm:col-span-2">
                    <h3 class="font-semibold text-blue-800">Villa Stay</h3>
                    <p class="text-gray-600">Check-in at 2:00 PM | Check-out at 12:00 NN</p>
                </div>
            </div>
        </section>

        <!-- Guidelines -->
        <section>
            <h2 class="text-2xl font-bold text-blue-900 mb-4">Guidelines</h2>
            <ul class="space-y-4">
                <li class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                    <span class="font-medium text-blue-800">Extensions:</span>
                    Extensions are not allowed unless approved by management.
                </li>
                <li class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                    <span class="font-medium text-blue-800">Check-in / Check-out:</span>
                    Please strictly follow the assigned times to give our team ample time to clean, sanitize, and prepare for the next guests.
                </li>
                <li class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                    <span class="font-medium text-blue-800">Facilities:</span>
                    All facilities must be treated responsibly. Damages due to negligence or misuse will be charged to the guest’s account.
                </li>
                <li class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
                    <span class="font-medium text-blue-800">Staff:</span>
                    Our staff is dedicated to making your stay enjoyable. Please treat them with respect and kindness at all times.
                </li>
            </ul>
        </section>

        <p class="font-semibold text-center mt-8">Thank you for your cooperation.</p>
        <p class="uppercase font-bold text-gray-800 text-center">By the Management</p>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-6 mt-12 shadow-inner">
        <div class="max-w-5xl mx-auto text-center text-sm tracking-wide">
            &copy; 2025 Casa Alvadon. All rights reserved.
        </div>
    </footer>

</body>

</html>
