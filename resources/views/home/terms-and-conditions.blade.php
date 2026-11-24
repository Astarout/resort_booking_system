
{{-- Terms and Conditions --}}
<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }} | Terms and Conditions</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gradient-to-b from-gray-50 to-gray-100 text-gray-800 font-sans leading-relaxed">

    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-8 shadow-lg">
        <div class="max-w-5xl mx-auto px-4 text-center">

            <div class="flex justify-center mb-4">
                <img src="{{ asset('storage/icons/Logo.jpg') }}"
                     alt="Casa Alvadon Logo"
                     class="w-20 h-20 md:w-24 md:h-24 rounded-full shadow-md border-4 border-white">
            </div>

            <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide">Terms &amp; Conditions</h1>
            <p class="mt-2 text-sm md:text-base text-blue-100">Please read carefully before your stay at Casa Alvadon</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 py-12 space-y-10">

        <!-- Section Template -->
        <section class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-semibold text-blue-800 mb-5 border-b pb-3">1. Check-In &amp; Check-Out</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Check-in is at <span class="font-medium">2pm onwards</span>.</li>
                <li>Check-out is strictly at <span class="font-medium">12 noon</span>. Additional fees apply for extensions.</li>
                <li>Guests must clear all personal belongings before check-out.</li>
                <li>All registered guests will be given <span class="font-medium">wristbands</span> as proof of stay.</li>
                <li>Full balance must be settled upon check-in at reception.</li>
                <li>Visitors are not allowed inside villas, only in common areas until the Front Desk closes.</li>
                <li>
                    Maintain cleanliness and avoid excessive mess <span class="text-red-600 font-semibold">(₱500 cleaning charge)</span>.
                    <ul class="list-[circle] list-inside ml-6 mt-2 space-y-1 text-gray-600">
                        <li>Dispose trash properly</li>
                        <li>Garbage collection at <span class="font-medium">8:00 PM &amp; 10:00 AM</span></li>
                        <li>Wash used kitchen utensils and cookware</li>
                    </ul>
                </li>
                <li>Inform management about any damages immediately.</li>
                <li>Guests must pay for damages or missing items.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-semibold text-blue-800 mb-5 border-b pb-3">2. Swimming Attire Policy</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Proper <span class="font-medium">swimwear</span> is required. Cotton shirts, denim, or undergarments are not allowed.</li>
                <li>All guests must <span class="font-medium">shower before entering the pool</span>.</li>
                <li>Children must wear <span class="font-medium">swim diapers</span> in the pool area.</li>
                <li>Improper attire may result in removal from the pool area.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-semibold text-blue-800 mb-5 border-b pb-3">3. Pet Policy</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Pets are allowed in <span class="font-medium">common areas</span>, except the pool area.</li>
                <li>Owners are responsible for <span class="font-medium">any mess, damage, or injury</span> caused by their pets.</li>
                <li>All pets must <span class="font-medium">wear diapers</span> while on the property.</li>
                <li>If inside the villa, pets must remain in a <span class="font-medium">secure cage</span>.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-semibold text-blue-800 mb-5 border-b pb-3">4. Don’ts</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>No use of <span class="font-medium">portable heaters</span> or fire-hazard devices.</li>
                <li>Smoking only in designated areas. Penalty: <span class="text-red-600 font-semibold">₱500</span>.</li>
                <li>No loitering, gambling, excessive alcohol, or inappropriate behavior.</li>
                <li>No weapons, fireworks, or explosives allowed.</li>
                <li>Maintain <span class="font-medium">quiet hours after 11:00 PM</span>.</li>
            </ul>
        </section>

        <section class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-semibold text-blue-800 mb-5 border-b pb-3">5. Reservation &amp; Cancellation</h2>
            <ul class="list-disc list-inside space-y-2 text-gray-700">
                <li>Reservations require <span class="font-medium">downpayment within 24 hours</span>.</li>
                <li>Unpaid bookings within 24 hours are cancelled automatically.</li>
                <li>Rates may change unless secured with advance payment.</li>
                <li><span class="font-bold text-red-600">All payments are NON-REFUNDABLE</span>, but rescheduling is allowed within 6 months.</li>
                <li>Maximum of <span class="font-medium">2 rebookings per booking</span>.</li>
                <li>Rebooking requests must be made <span class="font-medium">1 week before stay</span>.</li>
                <li>Same-day cancellations are not accepted; payments are forfeited.</li>
                <li>Early check-in/late check-out are subject to fees and availability.</li>
                <li>No-shows are fully charged and non-refundable.</li>
                <li>Shortened stays are charged in full.</li>
                <li>Casa Alvadon reserves the right to cancel suspicious bookings.</li>
            </ul>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-900 to-blue-700 text-white py-6 mt-12 shadow-inner">
        <div class="max-w-5xl mx-auto text-center text-sm tracking-wide">
            &copy; 2025 Casa Alvadon. All rights reserved.
        </div>
    </footer>

</body>

</html>
