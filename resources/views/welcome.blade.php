<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/landing/mySwiper.js',
        'resources/js/landing/aos.js',
        'resources/js/landing/reviewSwiper.js',
    ])
</head>

<body x-data="{ active: 1, mobileMenuOpen: false }" x-cloak>

    {{-- PC Navigation Bar --}} {{-- COMPLETE --}}
    <nav
        class="
            bg-[#fffff0] shadow-lg px-20 py-3 justify-between items-center sticky top-0 z-50
            hidden lg:flex
        "
    >
        <div class="flex items-center space-x-2">
            <img
                src="{{ asset('storage/icons/Logo-Favicon.png') }}"
                alt="Casa Alvadon"
                class="h-16 w-auto border-2 border-blue-600 rounded-2xl"
            >
            <span class="text-xl font-semibold text-gray-700">Casa Alvadon</span>
        </div>

        <ul class="flex space-x-6 text-gray-600 font-medium">
            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 1 }"
            >
                <a href="#home" @click.prevent="scrollWithoutHash('home')">Home</a>
            </li>

            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 2 }"
            >
                <a href="#about" @click.prevent="scrollWithoutHash('about')">About</a>
            </li>

            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 3 }"
            >
                <a href="#accommodations" @click.prevent="scrollWithoutHash('accommodations')">Accommodations</a>
            </li>

            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 4 }"
            >
                <a href="#gallery" @click.prevent="scrollWithoutHash('gallery')">Gallery</a>
            </li>

            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 5 }"
            >
                <a href="#ratings" @click.prevent="scrollWithoutHash('ratings')">Ratings</a>
            </li>

            <li
                class="px-3 py-2 hover:text-blue-600 cursor-pointer transition-colors duration-500 rounded-3xl"
                :class="{ 'bg-blue-600 text-white hover:text-white': active === 6 }"
            >
                <a href="#contacts" @click.prevent="scrollWithoutHash('contacts')">Contacts</a>
            </li>
        </ul>

    </nav>

    {{-- Mobile Navigation Bar --}} {{-- COMPLETE --}}
    <div
        class="sticky top-0 z-50 lg:hidden"
    >
        <nav
            class="bg-[#fffff0] shadow-lg px-6 py-3 flex justify-between items-center sticky top-0 z-50 lg:hidden"
            x-cloak
        >
            <div
                class="flex items-center space-x-2"
            >
                <img
                    src="{{ asset('storage/icons/Logo-Favicon.png') }}"
                    alt="Casa Alvadon"
                    class="h-12 w-auto border-2 border-blue-600 rounded-xl"
                >
                <span class="text-lg font-semibold text-gray-700">Casa Alvadon</span>
            </div>

            <x-button variant="transparent"
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="focus:outline-none"
            >
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </x-button>
        </nav>

        {{-- Mobile Dropdown --}}
        <div
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-16 left-0 right-0 bg-[#fffff0] shadow-2xl z-40"
        >
            <ul class="flex flex-col space-y-2 px-6 py-4 text-gray-600 font-medium" x-cloak>
                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 1 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#home" @click.prevent="scrollWithoutHash('home'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                        </svg>
                        Home
                    </a>
                </li>

                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 2 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#about" @click.prevent="scrollWithoutHash('about'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                        </svg>
                        About
                    </a>
                </li>

                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 3 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#accommodations" @click.prevent="scrollWithoutHash('accommodations'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journal-bookmark-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        Accommodation
                    </a>
                </li>

                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 4 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#gallery" @click.prevent="scrollWithoutHash('gallery'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                            <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                            <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z"/>
                        </svg>
                        Gallery
                    </a>
                </li>

                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 5 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#ratings" @click.prevent="scrollWithoutHash('ratings'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half" viewBox="0 0 16 16">
                            <path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z"/>
                        </svg>
                        Ratings
                    </a>
                </li>

                <li :class="{ 'bg-blue-600 text-white hover:text-white': active === 6 }" class="text-center px-3 py-2 rounded-lg hover:text-blue-600">
                    <a class="flex justify-center items-center gap-2" href="#contacts" @click.prevent="scrollWithoutHash('contacts'); mobileMenuOpen=false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text-fill" viewBox="0 0 16 16">
                            <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z"/>
                        </svg>
                        Contacts
                    </a>
                </li>
            </ul>
        </div>
    </div>

    {{-- Home --}} {{-- COMPLETE --}}
    <section
        id="home"
        class="bg-[#FFFFF0] min-h-screen pt-20 pb-20 px-6 sm:px-10 md:px-16 lg:px-28"
        x-intersect.half="active = 1"
    >

        @php
            $home = $galleries->where('type', 'home')->first();
        @endphp

        <div
            class="
                relative bg-cover bg-center min-h-[70vh] w-full rounded-3xl shadow-md text-white
                flex flex-col items-center justify-center gap-5 text-center px-4
                [&>*]:z-10
                before:content-[''] before:absolute before:inset-0 before:bg-black/65 before:rounded-3xl before:pointer-events-none
            "
            @if($home && !empty($home->image))
                bg-cover bg-center
            @else
                bg-gray-200
            @endif

            @if($home && !empty($home->image))
                style="background-image: url('{{ asset('storage/' . $home->image) }}');"
            @endif
        >

            <header>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold">Welcome To Casa Alvadon</h1>
            </header>

            <article>
                @if ($home && $home->description != null)
                    <p class="text-base sm:text-lg md:text-xl">{{ $home->description }}</p>
                @else
                    <p class="text-base sm:text-lg md:text-xl">Offers an exceptional experience with top-notch service and a variety of amenities.</p>
                @endif
            </article>

            <div class="buttons">
                @if (Auth::check())
                    @if (Auth::user()->usertype === 'admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-button variant="primary">
                                Dashboard
                            </x-button>
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}">
                            <x-button variant="primary">Dashboard</x-button>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}">
                        <x-button variant="primary">Book Now</x-button>
                    </a>
                @endif
            </div>

        </div>

    </section>

    {{-- About --}} {{-- COMPLETE --}}
    <section
        id="about"
        class="bg-[#f0ebd8] h-screen flex flex-col justify-center py-10 px-5 md:px-10 lg:px-16"
        x-intersect.half="active = 2"
        x-data = "{
            modalOpen: false,
            modalImage: '',
            modalDescription: '',
        }"
        x-cloak
    >
        <header
            class="relative text-center mb-10"
            data-aos="fade-down"
            data-aos-delay="200"
        >
            <h1 class="text-2xl md:text-4xl font-semibold tracking-tight text-gray-700">
                About Us
            </h1>
            <div class="mt-2 h-0.5 w-16 mx-auto bg-gradient-to-r from-gray-500 to-gray-700 rounded-full"></div>
        </header>

        <article
            class="max-w-6xl mx-auto px-6 lg:px-12 flex flex-col gap-10 w-full"
        >
            <div
                class="max-w-3xl mx-auto text-center"
                data-aos="fade-down"
                data-aos-delay="600"
            >
                <p class="text-base sm:text-lg md:text-xl leading-relaxed text-gray-700">
                    Welcome to Casa Alvadon — your go-to public resort in Calauan, Laguna, conveniently
                    located near Alaminos Laguna! Whether you're planning a quick day tour or an overnight
                    getaway, we’ve got everything you need for a fun and hassle-free stay.
                </p>
            </div>

            <div
                class="
                    swiper mySwiper
                    w-full max-w-5xl mx-auto
                "
                data-aos="fade-down"
                data-aos-delay="800"
            >
                <div class="swiper-wrapper">
                    @php
                        $about_galleries = $galleries->where('type', 'about');
                        $about_galleries_count = $about_galleries->count();
                    @endphp

                    @forelse ($about_galleries as $gallery)
                        <div class="swiper-slide">
                            <img
                                class="w-full h-80 object-cover rounded-2xl shadow-lg"
                                src="{{ asset('storage/' . $gallery->image) }}"
                                alt="{{ $gallery->description ?? 'No Description.' }}"
                                @click="
                                    modalOpen = true;
                                    modalImage = @js(asset("storage/" . $gallery->image));
                                    modalDescription = @js($gallery->description ?? "No Description.");
                                "
                            >
                        </div>
                    @empty
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="swiper-slide">
                                <img
                                    class="w-full h-80 object-cover rounded-2xl shadow-lg"
                                    src="{{ asset('storage/images/' . $i . '/.jpg') }}"
                                    alt="image"
                                    @click="
                                        modalOpen = true;
                                        modalImage = '{{ asset('storage/images/' . $i . '/.jpg') }}';
                                        modalDescription = 'A beautiful view of the resort.';
                                    "
                                >
                            </div>
                        @endfor
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>

            <x-gallery-modal show="modalOpen" image="modalImage" description="modalDescription" closeAction="modalOpen = false"/>
        </article>
    </section>

    {{-- Accommodations --}} {{-- COMPLETE --}}
    <section
        id="accommodations"
        class="bg-[#FFFFF0] min-h-screen py-16 px-6 md:px-10 lg:px-16 flex flex-col justify-center"
        x-intersect.half="active = 3"
    >
        {{-- Header --}}
        <header
            class="relative text-center mb-10"
            data-aos="fade-down"
            data-aos-delay="200"
        >
            <h1 class="text-2xl md:text-4xl font-semibold tracking-tight text-gray-700">
                Accommodations
            </h1>
            <div class="mt-2 h-0.5 w-16 mx-auto bg-gradient-to-r from-gray-500 to-gray-700 rounded-full"></div>
        </header>

        <article
            class="max-w-6xl mx-auto px-6 lg:px-12 flex flex-col gap-10 w-full"
        >
            {{-- Description --}}
            <div
                class="text-base text-center sm:text-lg md:text-xl leading-relaxed text-gray-700"
                data-aos="fade-down"
                data-aos-delay="600"
            >
                <p>
                    Discover our range of cozy rooms, villas, and suites designed to give you comfort and relaxation.
                </p>
            </div>

            {{-- Accommodations --}}
            <div
                class="swiper mySwiper w-full"
                data-aos="fade-down"
                data-aos-delay="800"
            >
                <div class="swiper-wrapper py-10">
                    @forelse ($accommodations as $accommodation)
                        <div class="swiper-slide">
                            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition p-4 flex flex-col items-center gap-4 h-96">
                                <div class="w-full h-64 overflow-hidden rounded-xl">
                                    <img src="{{ asset('storage/' . $accommodation->image) }}" alt="" class="w-full h-full object-cover">
                                </div>

                                <p class="text-lg font-bold text-gray-800 text-center">
                                    {{ $accommodation->name }}
                                    @if ($accommodation->tour_type) - {{ ucfirst($accommodation->tour_type) }} @endif
                                </p>

                                <div class="w-full text-center">
                                    @if ($accommodation->discount)
                                        @php $discountedPrice = $accommodation->price - ($accommodation->price * ($accommodation->discount / 100)); @endphp

                                        <div class="flex flex-row items-center gap-2 justify-center">
                                            <span class="text-green-700 font-semibold text-md">
                                                ₱ {{ number_format($discountedPrice, 2) }}
                                            </span>

                                            <div class="flex items-center gap-2 text-sm">
                                                <span class="line-through text-gray-400">
                                                    ₱ {{ number_format($accommodation->price, 2) }}
                                                </span>

                                                <span class="text-red-500 font-medium">
                                                    -{{ $accommodation->discount }}% OFF
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-green-700 font-semibold text-md">
                                            ₱ {{ number_format($accommodation->price, 2) }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('home.accommodation-details', ['encrypted_id' => Crypt::encrypt($accommodation->id)]) }}" target="_blank">
                                    <x-button variant="primary" class="flex justify-center items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                        </svg>
                                        Details
                                    </x-button>
                                </a>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </article>
    </section>

    {{-- Gallery --}} {{-- COMPLETE --}}
    <section
        id="gallery"
        class="
            gallery bg-[#f0ebd8] min-h-screen
            flex flex-col lg:flex-row justify-center items-center gap-10
            px-4 sm:px-5 md:px-10 lg:px-16 py-16
        "
        x-intersect.half="active = 4"
        x-data = "{
            modalOpen: false,
            modalImage: '',
            modalDescription: '',
        }"
    >
        <header
            class="relative text-center mb-10"
            data-aos="fade-right"
            data-aos-delay="200"
        >
            <h1 class="text-2xl md:text-4xl font-semibold tracking-tight text-gray-700">
                Gallery
            </h1>
            <div class="mt-2 h-0.5 w-16 mx-auto bg-gradient-to-r from-gray-500 to-gray-700 rounded-full"></div>
        </header>

        <div
            class="
                swiper mySwiper
                w-full max-x-5xl mx-auto;
            "
            data-aos="fade-right"
            data-aos-delay="800"
        >
            <div class="swiper-wrapper">
                @php
                    $galleries = $galleries->where('type', 'gallery');
                    $galleries_count = $galleries->count();
                @endphp

                @forelse ($galleries as $gallery)
                    <div class="swiper-slide">
                        <img
                            class="w-full h-96 object-cover rounded-2xl shadow-lg hover:cursor-pointer"
                            src="{{ asset('storage/' . $gallery->image) }}"
                            alt="{{ $gallery->description }}"
                            @click="
                                modalOpen = true;
                                modalImage = @js(asset("storage/" . $gallery->image));
                                modalDescription = @js($gallery->description ?? "No Description");
                            "
                        >
                    </div>
                @empty
                    @for ($i = 0; $i <= 5; $i++)
                        <div class="swiper-slide">
                            <img
                                class="w-full h-96 object-cover rounded-2xl shadow-lg hover:cursor-pointer"
                                src="{{ asset('storage/images/' .$i . '.jpg') }}"
                                alt="image"
                                @click="
                                    modalOpen = true;
                                    src="{{ asset('storage/images/' .$i . '.jpg') }}"
                                    modalDescription = 'A beautiful view of the resort.';
                            ">
                        </div>
                    @endfor
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>

            <!-- Navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

        </div>

        <x-gallery-modal show="modalOpen" image="modalImage" description="modalDescription" closeAction="modalOpen = false"/>


    </section>

    {{-- Ratings --}} {{-- COMPLETE --}}
    <section
        id="ratings"
        class="bg-[#FFFFF0] min-h-screen max-h-max py-16 px-6"
        x-intersect.half="active = 5"
        x-data="{
            selectedStar: 'all',

            reviews: {{ $ratings->map(fn($r) => ['id' => $r->id, 'rate' => $r->rate, 'user' => ['name' => optional($r->user)->first_name . ' ' . optional($r->user)->last_name, 'avatar' => optional($r->user)->avatar], 'comment' => $r->comment])->toJson() }},

            modalOpen: false,

            modalReview: null,

            get hasVisibleReviews() {
                if (this.selectedStar === 'all') {
                    return this.reviews.length > 0;
                }

                return this.reviews.some(r => r.rate == this.selectedStar);
            },

            openModal(review) {
                this.modalReview = review;
                this.modalOpen = true;
            },

            closeModal() {
                this.modalOpen = false;
                this.modalReview = null;
            },

            init() {
                this.$watch('modalOpen', value => {
                    document.body.style.overflow = value ? 'hidden' : 'auto';
                });
            }
        }"
    >
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-800">What Our Customers Say?</h2>
            <p class="text-gray-600 mt-2">Real reviews from people who stayed with us</p>
        </div>

        <!-- Average Rating -->
        <div class="flex flex-col items-center mt-6">
            <div class="text-6xl font-bold text-yellow-500">{{ number_format($averageRating, 1) }}</div>

            <div class="flex mt-2">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $fillPercent = 0;
                        if ($averageRating >= $i) {
                            $fillPercent = 100;
                        } elseif ($averageRating > $i-1) {
                            $fillPercent = ($averageRating - ($i-1)) * 100;
                        }
                    @endphp
                    <div class="relative w-8 h-8 inline-block mb-2">
                        <!-- empty star -->
                        <svg class="w-8 h-8 text-gray-300 absolute top-0 left-0" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                        </svg>
                        <!-- filled -->
                        <svg class="w-8 h-8 text-yellow-400 absolute top-0 left-0 overflow-hidden" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" style="clip-path: inset(0 {{100 - $fillPercent}}% 0 0);">
                            <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                        </svg>
                    </div>
                @endfor
            </div>

            <p class="text-gray-600 mt-1 text-sm">
                Based on {{ $ratings->count() }} review{{ $ratings->count() !== 1 ? 's' : '' }}
            </p>
        </div>

        <!-- Filter Buttons -->
        <div class="flex flex-wrap justify-center gap-2 mt-6">
            <button
                @click="selectedStar = 'all'"
                :class="selectedStar === 'all' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700'"
                class="px-4 py-2 rounded-full font-medium transition"
            >All</button>

            @for ($i = 1; $i <= 5; $i++)
                <button
                    @click="selectedStar = {{ $i }}"
                    :class="selectedStar === {{ $i }} ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-4 py-2 rounded-full font-medium transition flex items-center"
                >
                    {{ $i }}
                    <svg class="w-4 h-4 ml-1 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                    </svg>
                </button>
            @endfor
        </div>

        <!-- Swiper Reviews -->
        <div class="mt-10 max-w-6xl mx-auto" x-show="hasVisibleReviews">
            <div class="swiper myReviewSwiper">
                <div class="swiper-wrapper">
                    <template x-for="review in reviews" :key="review.id">
                        <div
                            class="swiper-slide cursor-pointer"
                            x-show="selectedStar === 'all' || selectedStar == review.rate"
                            @click="openModal(review)"
                        >
                            <div class="bg-white rounded-2xl p-6 flex flex-col text-center shadow-xl h-full">
                                <img
                                    :src="review.user.avatar ? '/storage/' + review.user.avatar : '/storage/icons/ProfileIcon.jpg'"
                                    alt="Profile"
                                    class="w-16 h-16 rounded-full border border-gray-300 object-cover mb-3 mx-auto"
                                >

                                <h3 class="font-semibold text-gray-800" x-text="review.user.name ?? 'Not Available'"></h3>

                                <div class="flex justify-center mt-2">
                                    <template x-for="i in 5" :key="i">
                                        <svg class="w-5 h-5" :class="i <= review.rate ? 'text-yellow-400' : 'text-gray-300'" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                                        </svg>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty state -->
                    <template x-if="reviews.length === 0">
                        <div class="swiper-slide">
                            <p class="text-gray-500 text-center">No Reviews Yet</p>
                        </div>
                    </template>
                </div>

                <!-- Swiper Navigation -->
                <div class="swiper-pagination mt-6"></div>
                <div class="swiper-button-prev text-yellow-500"></div>
                <div class="swiper-button-next text-yellow-500"></div>
            </div>
        </div>

        <!-- Empty Message -->
        <div class="mt-8 text-center text-gray-500" x-show="!hasVisibleReviews">
            <template x-if="selectedStar !== 'all'">
                <p class="text-lg font-medium text-gray-700">No reviews with <span x-text="selectedStar"></span>★ yet.</p>
            </template>
        </div>

        <!-- Review Modal -->
        <div
            x-show="modalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
        >
            <div class="bg-white rounded-xl p-6 max-w-md w-full relative mx-5" @click.away="closeModal()">

                <x-button
                    class="absolute top-3 right-3"
                    @click="closeModal()"
                    variant="transparent"
                ><i class="bi bi-x-lg"></i></x-button>

                <div class="flex flex-col items-center">
                    <img
                        :src="modalReview?.user.avatar ? '/storage/' + modalReview.user.avatar : '/storage/icons/ProfileIcon.jpg'"
                        alt="Profile"
                        class="w-20 h-20 rounded-full mb-3"
                    >

                    <h3 class="text-xl font-semibold text-gray-800" x-text="modalReview?.user.name"></h3>

                    <div class="flex mt-2">
                        <template x-for="i in 5" :key="i">
                            <svg class="w-5 h-5" :class="i <= modalReview?.rate ? 'text-yellow-400' : 'text-gray-300'" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                            </svg>
                        </template>
                    </div>

                    <p class="mt-4 text-gray-600 text-center italic" x-text="modalReview?.comment ? '“' + modalReview.comment + '”' : 'No Comment'"></p>
                </div>
            </div>
        </div>
    </section>

    {{-- Contacts --}} {{-- COMPLETE --}}
    <footer
        id="contacts"
        class="
            bg-gradient-to-r from-blue-950 via-gray-900 to-gray-900 text-gray-200 px-6 md:px-16 pt-12 pb-6 space-y-12
        "
        x-intersect.half="active = 6"
    >

        <section
            class="
                w-full h-72 md:h-96 rounded-2xl overflow-hidden shadow-2xl
            "
            data-aos="fade-down"
            data-aos-delay="200"
        >
            <iframe
                class="w-full h-full"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3869.929802942786!2d121.24900067456416!3d14.081321089580179!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd5dd242b0f347%3A0x4f2d2626222ffdb!2sCasa%20Alvadon!5e0!3m2!1sen!2sph!4v1747524547166!5m2!1sen!2sph"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </section>

        <section
            class="
                grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10
            "
            data-aos="fade-down"
            data-aos-delay="400"
        >
            <article class="contact space-y-4">
                <header>
                    <h3 class="text-xl font-semibold text-white">Contacts</h3>
                </header>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
                            <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671"/>
                            <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791"/>
                        </svg>
                        <a href="mailto:casaalvadon@gmail.com">casaalvadon@gmail.com</a>
                    </div>

                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sim-fill" viewBox="0 0 16 16">
                            <path d="M5 4.5a.5.5 0 0 1 .5-.5h2v2H5zM8.5 6V4h2a.5.5 0 0 1 .5.5V6zM5 7h6v2H5zm3.5 3H11v1.5a.5.5 0 0 1-.5.5h-2zm-1 0v2h-2a.5.5 0 0 1-.5-.5V10z"/>
                            <path d="M3.5 0A1.5 1.5 0 0 0 2 1.5v13A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5V3.414a1.5 1.5 0 0 0-.44-1.06L11.647.439A1.5 1.5 0 0 0 10.586 0zm2 3h5A1.5 1.5 0 0 1 12 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 11.5v-7A1.5 1.5 0 0 1 5.5 3"/>
                        </svg>
                        <p>0961-057-4052</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg>
                        <p>(049)546-7557</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg>
                        <p>Purok 1, Brgy Limao Calauan Laguna</p>
                    </div>
                </div>
            </article>

            <article class="links">
                <header>
                    <h3 class="text-xl font-semibold text-white mb-3">Guides</h3>
                </header>

                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home.terms-and-conditions') }}" target="_blank" class="hover:text-blue-400 transition">
                            Terms and Conditions
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.guidelines') }}" target="_blank" class="hover:text-blue-400 transition">
                            Guidelines
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.villa-booking-reminders') }}" target="_blank" class="hover:text-blue-400 transition">
                            Villa Booking Remindes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home.refund-and-rebooking-policy') }}" target="_blank" class="hover:text-blue-400 transition">
                            Refunds and Rebooking Policy
                        </a>
                    </li>
                </ul>
            </article>

            <article>
                <header>
                    <h3 class="text-xl font-semibold text-white mb-3">Quick Links</h3>
                </header>

                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#home" @click.prevent="scrollWithoutHash('home')" class="hover:text-blue-400 transition">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="#about" @click.prevent="scrollWithoutHash('about')" class="hover:text-blue-400 transition">
                            About
                        </a>
                    </li>

                    <li>
                        <a href="#accommodation" @click.prevent="scrollWithoutHash('accommodations')" class="hover:text-blue-400 transition">
                            Accommodations
                        </a>
                    </li>

                    <li>
                        <a href="#gallery" @click.prevent="scrollWithoutHash('gallery')" class="hover:text-blue-400 transition">
                            Gallery
                        </a>
                    </li>

                    <li>
                        <a href="#ratings" @click.prevent="scrollWithoutHash('ratings')" class="hover:text-blue-400 transition">
                            Ratings
                        </a>
                    </li>

                    <li>
                        <a href="#contacts" @click.prevent="scrollWithoutHash('contacts')" class="hover:text-blue-400 transition">
                            Contacts
                        </a>
                    </li>
                </ul>
            </article>

            <article class="social-media">
                <header>
                    <h3 class="text-xl font-semibold text-white mb-3">Follow Us</h3>
                </header>

                <div class="flex gap-6">
                    <span class="rounded-full bg-blue-600 transition shadow-md p-2">
                        <a href="https://web.facebook.com/casaalvadon" target="_blank" title="Facebook">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                            </svg>
                        </a>
                    </span>

                    <span class="rounded-full bg-red-800 transition shadow-md p-2">
                        <a href="https://www.instagram.com/casaalvadon/?fbclid=IwY2xjawKWHKRleHRuA2FlbQIxMABicmlkETFpUjlFZndvWWVvdDNBME14AR6-otbn5RmeHcWBoYB82CVdXPc8UMuyhRmBmN9g4i_j9elqbk-ayvfduSHI8A_aem_dy2fkIWY2pGMRSzfo5BaRw#" title="Instagram" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                            </svg>
                        </a>
                    </span>

                    <span class="rounded-full bg-black transition shadow-md p-2">
                        <a href="https://www.tiktok.com/@casaalvadon?fbclid=IwY2xjawKWF71leHRuA2FlbQIxMABicmlkETFubXdPdWRHRmVWQzdPeXppAR4uHRaV3TKjqJoX4dN1RurOsFjwDGdpGX36yhmwinf-GekjLudTHH7EesDZqw_aem_LejGdoaD2aJYsuAmr7u5rg" title="tiktok" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-tiktok" viewBox="0 0 16 16">
                                <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3z"/>
                            </svg>
                        </a>
                    </span>
                </div>
            </article>
        </section>

        <section class="text-center border-t border-gray-700 pt-6 mt-6">
            <p class="text-xs text-gray-400">&copy; 2025 Casa Alvadon. All rights reserved.</p>
        </section>
    </footer>

    <script>
        function scrollWithoutHash(id) {
            const el = document.getElementById(id);

            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
                history.pushState(null, '', window.location.pathname + window.location.search);
            }
        }
    </script>

</body>

</html>
