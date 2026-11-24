<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | Reservation</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing/myFlatPickr',
    ])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 py-10 px-5">

    <form
        action="{{ route('user.reservation.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="w-full max-w-4xl bg-white px-3 sm:px-6 md:px-10 pb-10 rounded-2xl shadow-2xl space-y-8 flex flex-col"
        onsubmit="if (!confirm('Confirm Reservation?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
    >
        @csrf
        @method("POST")

        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Create Reservation</h1>
            <p class="text-sm text-gray-500">Fill in the details below to add a new reservation.</p>
        </header>

        <main class="overflow-y-auto space-y-6 pr-3 flex-1"
            x-data="{
                agreed: false,
                terms: true,
                accommodationsForm: false,
                accommodationCheck: false,
                details: false,
                finalize: false,
                selected: null,
                viewing: null,
            }"
        >
            {{-- Step 1 Terms and Condition agreement --}}
            <section
                class="space-y-5"
                x-cloak
                x-show="terms"
            >
                <header>
                    <h1 class="text-xl font-semibold text-gray-800 mb-5 border-b pb-3">Step 1: Terms and Conditions</h1>
                </header>
                <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-5 border-b pb-3">1. Check-In &amp; Check-Out</h2>
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
                        <li>
                            Guests must pay for damages or missing items:
                            <ul class="list-[circle] list-inside ml-6 mt-2 grid grid-cols-1 md:grid-cols-2 gap-1 text-gray-600">
                                <li>Glass – ₱200</li>
                                <li>Mug – ₱200</li>
                                <li>Dining Plate – ₱250</li>
                                <li>Casserole – ₱3000</li>
                                <li>Frying Pan – ₱3000</li>
                                <li>Pan Set (Couple Villa) – ₱3500</li>
                                <li>Spoon &amp; Fork – ₱100/pc</li>
                                <li>Dessert Plate – ₱200</li>
                                <li>Pitcher – ₱150</li>
                                <li>Bowl – ₱200</li>
                                <li>Knife – ₱500</li>
                                <li>Chopping Board – ₱500</li>
                                <li>Cheese Grater – ₱500</li>
                                <li>Stained Linens/Curtains – ₱250/pc</li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-5 border-b pb-3">2. Swimming Attire Policy</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Proper <span class="font-medium">swimwear</span> is required. Cotton shirts, denim, or undergarments are not allowed.</li>
                        <li>All guests must <span class="font-medium">shower before entering the pool</span>.</li>
                        <li>Children must wear <span class="font-medium">swim diapers</span> in the pool area.</li>
                        <li>Improper attire may result in removal from the pool area.</li>
                    </ul>
                </div>

                <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-5 border-b pb-3">3. Pet Policy</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>Pets are allowed in <span class="font-medium">common areas</span>, except the pool area.</li>
                        <li>Owners are responsible for <span class="font-medium">any mess, damage, or injury</span> caused by their pets.</li>
                        <li>All pets must <span class="font-medium">wear diapers</span> while on the property.</li>
                        <li>If inside the villa, pets must remain in a <span class="font-medium">secure cage</span>.</li>
                    </ul>
                </div>

                <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-5 border-b pb-3">4. Don’ts</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        <li>No use of <span class="font-medium">portable heaters</span> or fire-hazard devices.</li>
                        <li>Smoking only in designated areas. Penalty: <span class="text-red-600 font-semibold">₱500</span>.</li>
                        <li>No loitering, gambling, excessive alcohol, or inappropriate behavior.</li>
                        <li>No weapons, fireworks, or explosives allowed.</li>
                        <li>Maintain <span class="font-medium">quiet hours after 11:00 PM</span>.</li>
                    </ul>
                </div>

                <div class="bg-white shadow-md hover:shadow-lg transition rounded-2xl p-8 border border-gray-200">
                    <h2 class="text-lg font-semibold text-blue-800 mb-5 border-b pb-3">5. Reservation &amp; Cancellation</h2>
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
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    {{-- Terms Checkbox --}}
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input
                            type="checkbox"
                            id="agreeTerms"
                            class="w-4 h-4 text-blue-600 rounded border-gray-300"
                            x-model="agreed"
                        >
                        I agree to the terms and conditions
                    </label>

                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-2 w-full sm:w-auto">
                        <a href="{{ route('user.reservation') }}" class="w-full sm:w-auto">
                            <x-button variant="secondary" type="button" class="w-full"
                                @click.prevent="if(confirm('Go back?')) window.location = '{{ route('user.reservation') }}'"
                            >Back</x-button>
                        </a>

                        <button
                            type="button"
                            class="w-full sm:w-auto px-4 py-2 rounded-2xl bg-blue-600 text-white font-medium shadow hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition active:bg-blue-800"
                            :disabled="!agreed"
                            @click="terms = false, accommodationsForm = true"
                        >Next</button>
                    </div>
                </div>

            </section>

            {{-- Step 2 Select Accommodation --}}
            <section
                x-show="accommodationsForm"
                x-cloak
            >
                <header class="mb-5">
                    <h1 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Step 2: Select Accommodation</h1>
                </header>

                {{-- Accommodation Grid using Blade --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse ($accommodations as $accommodation)
                        <div
                            class="relative bg-white rounded-xl shadow hover:shadow-lg overflow-hidden border-2 transition flex flex-col"
                            :class="selected === {{ $accommodation->id }} ? 'border-blue-600 ring-2 ring-blue-400' : 'border-transparent'"
                        >
                            <img
                                src="{{ $accommodation->image ? asset('storage/' . $accommodation->image) : asset('storage/icons/ProfileIcon.jpg') }}"
                                alt="image"
                                class="w-full h-48 object-cover"
                            >

                            <div class="p-3 flex-1 flex flex-col justify-between">
                                <p class="text-sm text-gray-700 font-medium text-center">
                                    {{ $accommodation->name }}

                                    @if ($accommodation->tour_type)
                                        - <span class="capitalize text-gray-600">{{ $accommodation->tour_type }}</span>
                                    @endif
                                </p>

                                {{-- Pricing --}}
                                @if($accommodation->discount)
                                    @php $discountedPrice = $accommodation->price - ($accommodation->price * ($accommodation->discount / 100));@endphp

                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-green-700 font-semibold">
                                            ₱ {{ number_format($discountedPrice, 2) }}
                                        </span>

                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500 line-through">
                                                ₱ {{ number_format($accommodation->price, 2) }}
                                            </span>
                                            <span class="text-xs text-red-500 font-medium">
                                                -{{ $accommodation->discount }}% OFF
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-green-700 font-semibold mt-2 block">
                                        ₱ {{ number_format($accommodation->price, 2) }}
                                    </span>
                                @endif

                                {{-- Buttons --}}
                                <div class="mt-4 flex gap-2">
                                    {{-- Select button --}}
                                    <button
                                        type="button"
                                        class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 text-sm active:scale-95 transition"
                                        @click="selected = {{ $accommodation->id }}; accommodationCheck = true"
                                    >Select</button>

                                    {{-- View button --}}
                                    <button
                                        type="button"
                                        class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg shadow hover:bg-gray-200 text-sm active:scale-95 transition"
                                        @click="viewing = {{ $accommodation->id }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Modal for each accommodation --}}
                        <div
                            x-show="viewing === {{ $accommodation->id }}"
                            x-cloak
                            x-data
                            x-init="
                                $watch('viewing', value => {
                                    if (value) {
                                        document.body.style.overflow = 'hidden';
                                    } else {
                                        document.body.style.overflow = 'auto';
                                    }
                                })
                            "
                            class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
                        >
                            <div
                                class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative mx-4 sm:mx-0 h-auto sm:max-h-none sm:overflow-visible max-h-[90vh] overflow-y-auto sm:overflow-y-visible"
                                @click.away="viewing = null"
                            >
                                {{-- Close Button --}}
                                <button
                                    type="button"
                                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800"
                                    @click="viewing = null"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                    </svg>
                                </button>

                                {{-- Modal Content --}}
                                <img
                                    src="{{ $accommodation->image ? asset('storage/' . $accommodation->image) : asset('storage/icons/ProfileIcon.jpg') }}"
                                    alt="image"
                                    class="w-full h-48 sm:h-56 object-cover rounded-xl mb-4"
                                >

                                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">{{ $accommodation->name }}</h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
                                    {{-- Type --}}
                                    <div>
                                        <label class="block font-semibold text-gray-600 mb-1">Type</label>
                                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                            {{ $accommodation->tour_type ?? 'None' }}
                                        </div>
                                    </div>

                                    {{-- Capacity --}}
                                    <div>
                                        <label class="block font-semibold text-gray-600 mb-1">Capacity</label>
                                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                            {{ $accommodation->pax }} Pax
                                        </div>
                                    </div>

                                    {{-- Check-in --}}
                                    <div>
                                        <label class="block font-semibold text-gray-600 mb-1">Check-in Time</label>
                                            <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                                {{ $accommodation->check_in_time ? \Carbon\Carbon::createFromFormat('H:i:s', $accommodation->check_in_time)->format('g:i A') : 'None' }}
                                            </div>
                                    </div>

                                    {{-- Check-out --}}
                                    <div>
                                        <label class="block font-semibold text-gray-600 mb-1">Check-out Time</label>
                                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                            {{ $accommodation->check_out_time ? \Carbon\Carbon::createFromFormat('H:i:s', $accommodation->check_out_time)->format('g:i A') : 'None' }}
                                        </div>
                                    </div>

                                    {{-- Price --}}
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="block font-semibold text-gray-600 mb-1">Price</label>
                                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 flex items-center justify-between">
                                            @if($accommodation->discount)
                                                <span class="text-green-600 font-bold text-lg sm:text-xl">₱ {{ number_format($discountedPrice, 2) }}</span>
                                                <div class="flex items-center space-x-2">
                                                    <span class="line-through text-gray-400 text-sm">₱ {{ number_format($accommodation->price, 2) }}</span>
                                                    <span class="text-red-500 font-semibold text-sm">-{{ $accommodation->discount }}% OFF</span>
                                                </div>
                                            @else
                                                <span class="text-green-600 font-bold text-lg sm:text-xl">₱ {{ number_format($accommodation->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-span-1 sm:col-span-2">
                                        <label class="block font-semibold text-gray-600 mb-1">Description</label>
                                        <div class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                                            {{ $accommodation->description ?? 'No description' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-house-slash-fill" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                                <path d="M13.879 10.414a2.5 2.5 0 0 0-3.465 3.465zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465m-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95Z"/>
                            </svg>
                            <p class="text-2xl mt-5 font-medium">Accommodation not found</p>
                        </div>
                    @endforelse
                </div>

                <input type="hidden" name="accommodation_id" :value="selected">

                <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <x-button variant="secondary" type="button" class="w-full sm:w-auto"
                        @click="accommodationsForm = false; terms = true"
                    >Back</x-button>

                    <button
                        type="button"
                        class="w-full sm:w-auto px-4 py-2 rounded-2xl bg-blue-600 text-white font-medium shadow hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition active:bg-blue-800"
                        :disabled="!accommodationCheck"
                        @click="accommodationsForm = false, details = true"
                    >Next</button>
                </div>
            </section>

            {{-- Step 3 Details --}}
            <section
                x-show="details"
                x-cloak
                x-data="{
                    pax: null,
                    noOfGuest: null,
                    desiredDate: null,

                    selectedPets: [],
                    petQuantities: {},
                    selectedSheet: null,
                    idFile: null,

                    selectedTourType: null,

                    checkInTime: null,
                    checkOutTime: null,

                    checkInDisplay: null,
                    checkOutDisplay: null,


                    price: null,
                    entranceFee: null,
                    totalPrice: 0,

                    updatePax(id) {
                        switch(id) {
                            @foreach ($accommodations as $accommodation)
                                case {{ $accommodation->id }}:
                                    this.pax = {{ $accommodation->pax }};
                                    this.selectedTourType = @js($accommodation->tour_type);
                                    this.price = {{ $accommodation->discount ? ($accommodation->price - ($accommodation->price * ($accommodation->discount / 100))) : $accommodation->price }};

                                    @php
                                        $checkInTime = \Carbon\Carbon::parse($accommodation->check_in_time)->format('h:i A');
                                        $checkOutTime = \Carbon\Carbon::parse($accommodation->check_out_time)->format('h:i A');
                                    @endphp

                                    this.checkInTime = @js($checkInTime);
                                    this.checkOutTime = @js($checkOutTime);

                                    this.updateDatePicker({{ $accommodation->id }});

                                    @php
                                        $matchingFee = $entrance_fees->firstWhere('tour_type', $accommodation->tour_type);
                                    @endphp

                                    this.entranceFee = {{ $matchingFee ? $matchingFee->amount : 0 }};
                                break;
                            @endforeach
                        }
                    },

                    updateDatePicker(id) {
                        let input = document.querySelector('#accommodation-' + id + '-dates');

                        if (!input) return;

                        let disabledDates = JSON.parse(input.value);

                        // destroy old instance if exists

                        if (this.datePicker) {
                            this.datePicker.destroy();
                        }

                        this.datePicker = flatpickr('#date', {
                            dateFormat: 'Y-m-d',
                            disable: disabledDates,
                            minDate: new Date(Date.now() + (2 * 24 * 60 * 60 * 1000)),
                            onDayCreate: function(dObj, dStr, fp, dayElem) {
                                let dateStr = fp.formatDate(dayElem.dateObj, 'Y-m-d');

                                if (disabledDates.includes(dateStr)) {
                                    dayElem.classList.add('reserved-date');
                                }
                            }
                        });
                    },
                }"
                x-init="
                    $watch('selected', value => updatePax(value));

                    $watch('noOfGuest', value => {
                        if (value && price && entranceFee !== null) {
                            totalPrice = (Number(price) + (Number(entranceFee) * Number(value))).toFixed(2);
                        } else {
                            totalPrice = 0;
                        }
                    });

                    $watch('desiredDate', value => {
                        if (value && checkInTime && checkOutTime) {
                            const checkInDateTime = new Date(value + ' ' + checkInTime);
                            const checkOutDateTime = new Date(value + ' ' + checkOutTime);

                            // if checkOut time is before checkIn time, assume next day
                            if (checkOutDateTime <= checkInDateTime) {
                                checkOutDateTime.setDate(checkOutDateTime.getDate() + 1);
                            }

                            checkInDisplay = checkInDateTime.toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                            checkOutDisplay = checkOutDateTime.toLocaleString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
                        }
                    });
                "
                class="space-y-5"
            >
                <header class="mb-5">
                    <h1 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Step 3: Reservation Details</h1>
                </header>

                <template x-if="selected">
                    <div class="bg-gray-50 border rounded-xl p-5 shadow-md mb-6">
                        @foreach ($accommodations as $accommodation)
                            <div
                                x-show="selected === {{ $accommodation->id }}"
                                x-init="if(selected === {{ $accommodation->id }}) pax = {{ $accommodation->pax }}"
                                class="flex flex-col sm:flex-row gap-4 items-center"
                            >

                                <input
                                    type="hidden"
                                    id="accommodation-{{ $accommodation->id }}-dates"
                                    value='@json($accommodation->disabled_dates)'
                                >

                                <img
                                    src="{{ $accommodation->image ? asset('storage/' . $accommodation->image) : asset('storage/icons/ProfileIcon.jpg') }}"
                                    alt="Accommodation"
                                    class="w-32 h-32 object-cover rounded-lg shadow"
                                >

                                <div class="flex-1">
                                    <h2 class="text-lg font-semibold text-gray-800">
                                        {{ $accommodation->name }}
                                         @if ($accommodation->tour_type)
                                            <span class="text-sm text-gray-600 capitalize">{{ $accommodation->tour_type }}</span>
                                        @endif
                                    </h2>
                                    <p class="text-sm text-gray-500 mt-2 flex gap-1 items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                        </svg>
                                        {{ $accommodation->pax }}
                                    </p>

                                    @if($accommodation->discount)
                                        @php $discountedPrice = $accommodation->price - ($accommodation->price * ($accommodation->discount / 100));@endphp

                                        <div class="flex items-center justify-between">
                                            {{-- Discounted Price --}}
                                            <span class="text-green-700 font-semibold">
                                                ₱ {{ number_format($discountedPrice, 2) }}
                                            </span>

                                            {{-- Original Price + Discount --}}
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500 line-through">
                                                    ₱ {{ number_format($accommodation->price, 2) }}
                                                </span>
                                                <span class="text-xs text-red-500 font-medium">
                                                    -{{ $accommodation->discount }}% OFF
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-green-700 font-semibold">
                                            ₱ {{ number_format($accommodation->price, 2) }}
                                        </span>
                                    @endif

                                    @php $matchingFee = $entrance_fees->firstWhere('tour_type', $accommodation->tour_type); @endphp

                                    @if($matchingFee)
                                        <p class="text-sm text-blue-700 mt-2">
                                            Entrance Fee per Pax:
                                            <span class="font-semibold">₱ {{ number_format($matchingFee->amount, 2) }}</span>
                                        </p>
                                        <input type="hidden" name="entrance_fee" value="{{ $matchingFee->amount }}">
                                    @endif

                                    <p class="text-sm text-gray-500 mt-2">Please provide your stay details below.</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>

                {{-- No. of Guests and Desired Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-3">
                    <x-text-field
                        label="No. of Guest"
                        name="no_of_guest"
                        type="number"
                        id="no_of_guest"
                        x-model="noOfGuest"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
                            </svg>
                        '
                        min="1"
                        x-bind:max="pax"
                        x-bind:disabled="!details"
                        x-bind:required="details"
                    />

                    <x-text-field
                        label="Desired Date"
                        name="desired_date"
                        type="text"
                        id="date"
                        x-model="desiredDate"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        min="1"
                        x-bind:disabled="!details"
                        x-bind:required="details"
                    />
                </div>

                {{-- Check-in and Check-out Time and Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 px-3">
                    <x-text-field
                        label="Check-in"
                        name="check_in"
                        type="text"
                        id="check_in"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        readonly
                        x-model="checkInDisplay"
                    />

                    <x-text-field
                        label="Check-out"
                        name="check_out"
                        type="text"
                        id="check_out"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        readonly
                        x-model="checkOutDisplay"
                    />
                </div>

                <div class="col-span-2 mt-4">
                    <p class="text-lg font-semibold text-gray-800">
                        Total Price:
                        <span class="text-green-700" x-text="'₱ ' + (totalPrice || '0.00')"></span>
                    </p>
                </div>

                {{-- Pets --}}
                <div class="mt-6 bg-gray-50 p-4 rounded-xl shadow-inner">
                    <h3 class="font-semibold text-gray-700 mb-3">Pet selection</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @forelse ($allowed_pets as $pet)
                            <div class="flex items-center gap-3">
                                <input
                                    type="checkbox"
                                    id="pet-{{ $pet->id }}"
                                    value="{{ $pet->id }}"
                                    x-model="selectedPets"
                                    @change="if (!$event.target.checked) petQuantities[{{ $pet->id }}] = ''"
                                    x-bind:required="selectedPets.includes('{{ $pet->id }}')"
                                    class="rounded text-blue-600 focus:ring-blue-500"
                                >

                                <label for="pet-{{ $pet->id }}" class="text-gray-700 flex-1">{{ $pet->name }}</label>

                                <input
                                    type="number"
                                    min="1"
                                    max="{{ $pet->capacity }}"
                                    x-model="petQuantities[{{ $pet->id }}]"
                                    x-bind:disabled="!selectedPets.includes('{{ $pet->id }}')"
                                    placeholder="Capacity"
                                    class="w-24 border-2 border-blue-600 rounded-lg p-2 text-sm text-gray-800 focus:outline-none focus:border-blue-700 disabled:border-gray-300 disabled:bg-gray-100 text-center"
                                >
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">No pets allowed</p>
                        @endforelse
                    </div>

                    <template x-for="petId in selectedPets" :key="petId">
                        <input type="hidden" name="pets[]" :value="petId">
                    </template>

                    <template x-for="(quantity, id) in petQuantities" :key="id">
                        <input type="hidden" :name="`pet_quantities[${id}]`" :value="quantity">
                    </template>
                </div>

                {{-- Sheets --}}
                <div
                    class="mt-6 bg-gray-50 p-4 rounded-xl shadow-inner"
                    x-show="!selectedTourType"
                    x-cloak
                >
                    <h3 class="font-semibold text-gray-700 mb-3">Sheet Color</h3>

                    <div class="flex flex-wrap gap-4">
                        @forelse ($available_sheets as $sheet)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="radio"
                                    name="sheet_color"
                                    value="{{ $sheet->color }}"
                                    x-model="selectedSheet"
                                    class="text-blue-600 focus:ring-blue-500"
                                >

                                <div class="w-6 h-6 rounded border" style="background-color: {{ $sheet->color }};"></div>

                                <span class="text-gray-700">{{ $sheet->color }}</span>
                            </label>
                        @empty
                            <p class="text-center text-gray-500 py-4">No Available Sheets</p>
                        @endforelse
                    </div>
                </div>

                {{-- Valid ID--}}
                <div
                    class="mt-6 bg-gray-50 p-4 rounded-xl shadow-inner"
                    x-data="{ previewUrl: null }"
                    x-init="
                        $watch('idFile', value => {
                            if (!value) { previewUrl = null; return; }
                            const reader = new FileReader();
                            reader.onload = e => previewUrl = e.target.result;
                            reader.readAsDataURL(value);
                        })
                    "
                >
                    <h3 class="font-semibold text-gray-700 mb-3">Upload Valid ID</h3>

                    <input
                        id="valid_id"
                        type="file"
                        name="valid_id"
                        accept="image/*"
                        x-on:change="idFile = $event.target.files[0]"
                        required
                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none"
                    >

                    <template x-if="previewUrl">
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Preview:</p>
                            <img
                                :src="previewUrl"
                                alt="ID Preview"
                                class="w-48 h-auto rounded-lg border shadow-sm"
                            >
                        </div>
                    </template>
                </div>

                {{-- Note --}}
                <div class="mt-6 bg-yellow-50 p-4 rounded-xl border border-yellow-300 shadow-inner">
                    <h3 class="font-semibold text-gray-800 mb-2">Note:</h3>

                    <p class="text-gray-700 text-sm leading-relaxed">
                        Please pay a <span class="font-semibold text-blue-700">50% down payment</span> to secure your booking.
                        Payment can be made via <span class="font-semibold">GCash</span> or <span class="font-semibold">walk-in</span>.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <x-button variant="secondary" type="button" class="w-full sm:w-auto"
                        @click="
                            details = false;
                            accommodationsForm = true;
                            noOfGuest = null;
                            desiredDate = null;
                            selectedPets = null;
                            selectedPets = [];
                            petQuantities = {};
                            selectedSheet = null;
                            idFile = null;
                            $nextTick(() => {
                                const fileInput = document.getElementById('valid_id');
                                if (fileInput) fileInput.value = '';
                            });
                        "
                    >Back</x-button>

                    <button
                        type="submit"
                        class="w-full sm:w-auto px-4 py-2 rounded-2xl bg-blue-600 text-white font-medium shadow hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95 transition active:bg-blue-800"
                        :disabled="!noOfGuest || !desiredDate || !idFile"
                    >Finalize</button>
                </div>
            </section>
        </main>
    </form>

</body>

</html>
