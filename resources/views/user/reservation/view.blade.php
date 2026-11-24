
{{-- View Accommodation --}}
<!DOCTYPE html>

<html lang="en" x-data="{ showModal: false, imageSrc: '' }" x-bind:class="{ 'overflow-hidden': showModal }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ env('APP_NAME') }} | View - {{ $accommodation->name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing/viewDates.js',
    ])

</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 py-6 px-4">
    <main class="w-full max-w-4xl bg-white p-6 rounded-xl shadow-xl space-y-6">

        {{-- Header --}}
        <header class="text-center space-y-1">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-12 h-12 rounded-full shadow-md">
            </div>
            <h1 class="text-xl font-bold text-gray-800">{{ $accommodation->name }}</h1>
            <p class="text-xs text-gray-500">Explore the full details of this accommodation.</p>
        </header>

        {{-- Content Grid --}}
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Details --}}
            <div class="space-y-3 text-sm">
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">Name</span>
                    <span class="block text-gray-800">{{ $accommodation->name }}</span>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">Pax</span>
                    <span class="block text-gray-800">{{ $accommodation->pax }}</span>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">Tour Type</span>
                    <span class="block text-gray-800">{{ $accommodation->tour_type ? ucfirst($accommodation->tour_type) : 'N/A' }}</span>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">
                        Entrance Fee per Pax
                    </span>

                    <span class="block text-green-700 font-semibold">
                        ₱ {{ number_format($entrance_fee_per_pax, 2) }}
                    </span>

                    <span class="block text-[12px] text-gray-500 italic">
                        Kids below 3ft are free and not included in the count
                    </span>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">View Dates</span>

                    <input
                        type="text"
                        id="datePicker"
                        class="mt-1 w-full border border-gray-300 rounded-md p-2 text-sm focus:ring focus:ring-blue-300 focus:outline-none"
                        placeholder="Available dates"
                        readonly
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                        <span class="block text-[11px] font-semibold text-gray-500 uppercase">Check-in</span>
                        <span class="block text-gray-800">
                            {{ $accommodation->check_in_time ? \Carbon\Carbon::createFromFormat('H:i:s', $accommodation->check_in_time)->format('g:i A') : 'N/A' }}
                        </span>
                    </div>
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                        <span class="block text-[11px] font-semibold text-gray-500 uppercase">Check-out</span>
                        <span class="block text-gray-800">
                            {{ $accommodation->check_out_time ? \Carbon\Carbon::createFromFormat('H:i:s', $accommodation->check_out_time)->format('g:i A') : 'N/A' }}
                        </span>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">No. of accommodation available</span>
                    <span class="block text-gray-800">{{ $accommodation->room_count }}</span>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">Price</span>
                    @if($accommodation->discount)
                        @php
                            $discountedPrice = $accommodation->price - ($accommodation->price * ($accommodation->discount / 100));
                        @endphp

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
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <span class="block text-[11px] font-semibold text-gray-500 uppercase">Description</span>
                    <p class="mt-1 text-gray-700 text-sm whitespace-pre-line">
                        {{ $accommodation->description ? $accommodation->description : 'None' }}
                    </p>
                </div>
            </div>

            {{-- Images --}}
            <div class="space-y-4">
                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-gray-700 mb-1 text-sm">Main Image</h2>
                    @if($accommodation->image)
                        <img
                            src="{{ asset('storage/' . $accommodation->image) }}"
                            alt="Main Image"
                            class="w-full h-40 object-cover rounded-md shadow cursor-pointer"
                            @click="imageSrc='{{ asset('storage/' . $accommodation->image) }}'; showModal=true"
                        >
                    @else
                        <p class="text-gray-400 italic text-xs">No image uploaded</p>
                    @endif
                </div>

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-gray-700 mb-1 text-sm">Gallery</h2>
                    @if($gallery->count() > 0)
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($gallery as $img)
                                <img
                                    src="{{ asset('storage/' . $img->image) }}"
                                    alt="Gallery Image"
                                    class="w-full h-20 object-cover rounded-md shadow cursor-pointer"
                                    @click="imageSrc='{{ asset('storage/' . $img->image) }}'; showModal=true"
                                >
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-400 italic text-xs">No gallery images</p>
                    @endif
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <div class="flex flex-row-reverse gap-3">
            <a href="{{ route('user.reservation') }}">
                <x-button type="button" variant="secondary" class="py-4 px-6 text-sm">Back</x-button>
            </a>
        </div>
    </main>

    {{-- Alpine.js Modal --}}
    <div
        x-show="showModal"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
        x-transition
        x-cloak
    >
        <div class="relative max-w-3xl w-full px-4">
            <button
                @click="showModal=false"
                class="absolute top-2 right-2 bg-white rounded-full p-1 shadow hover:bg-gray-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
            <img :src="imageSrc" alt="Preview" class="w-full max-h-[80vh] object-contain rounded-lg shadow-lg">
        </div>
    </div>

    <script>
        window.disabledDates = @json($accommodation->disabled_dates);
    </script>

</body>

</html>
