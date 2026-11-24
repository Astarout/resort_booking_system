
{{-- User Sidebar --}}

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | User</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])
</head>

<body class="flex min-h-screen bg-gray-100">

    {{-- Computer Sidebar --}}
    <aside
        class="hidden lg:flex w-64 bg-[#141414] shadow-lg flex-col justify-between p-4"
    >

        {{-- Logo and Toggle--}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-12 h-12 shadow-lg rounded-full">
                <span class="text-md font-bold text-white">Casa Alvadon</span>
            </div>
        </div>

        {{-- Navigation List--}}
        <nav class="flex-1">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('user.dashboard') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-fill" viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.reservation') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.reservation') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        <span>Reservations</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.records') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.records') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-check" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        <span>Records</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.payments') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.payments') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                        </svg>
                        <span>Payments</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.refund_request') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.refund_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                        </svg>
                        <span>Refunds</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('user.reschedule_request') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('user.reschedule_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                            <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                        </svg>
                        <span>Reschedule Requests</span>
                    </a>
                </li>

                <li>
                    @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                    <a href="{{ route('user.notifications') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200 relative
                            {{ request()->routeIs('user.notifications') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                        </svg>
                        <span>Notifications</span>

                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('home.landing') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('home.landing') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                            <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                        </svg>
                        <span>Homepage</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- User Profile and Logout--}}
        <div x-data="{ logout_modal: false }" class="border-t pt-4 mt-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-10">
                    <div class="flex items-center space-x-3">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/icons/ProfileIcon.jpg') }}"
                            alt="Profile Picture"
                            class="w-10 h-10 rounded-full border shadow-sm"
                        >
                        <a href="{{ route('user.profile') }}">
                            <p class="font-semibold text-white text-sm line-clamp-1 max-w-[150px] hover:underline">
                                {{ Auth::user()->first_name ?? "Guest" }} {{ Auth::user()->last_name ?? "" }}
                            </p>
                        </a>
                    </div>

                    <x-button variant="transparent" @click="logout_modal = true" class="hover:font-bold text-white hover:bg-transparent active:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                        </svg>
                    </x-button>
                </div>
            </div>

            <div x-show="logout_modal" x-transition x-cloak
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            >
                <div class="bg-white rounded-xl shadow-lg p-6 w-80"
                    @click.outside="logout_modal = true"
                >
                    <h2 class="text-lg font-bold text-gray-800">Confirm Logout</h2>
                    <p ss="text-gray-600 mt-2">Are you sure you want to logout?</p>

                    <div class="flex justify-end space-x-3 mt-4">
                        <x-button variant="secondary" @click="logout_modal = false">
                            Cancel
                        </x-button>

                        <form action="{{ route('logout') }}" method="POST"
                            onsubmit="this.querySelector('button[type=submit]').disabled = true;"
                        >
                            @csrf
                            <x-button type="submit" variant="danger">
                                Logout
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </aside>

    {{-- Mobile Sidebar --}}
    <nav
        x-data="{ mobileSidebarOpen: false }"
        class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-[#141414] shadow-md"
    >
        {{-- Top bar --}}
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-10 h-10 rounded-full shadow">
                <span class="text-white font-bold text-lg">Casa Alvadon</span>
            </div>

            {{-- Hamburger Button --}}
            <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="text-white focus:outline-none">
                {{-- Menu Icon --}}
                <svg x-show="!mobileSidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                {{-- Close Icon --}}
                <svg x-show="mobileSidebarOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Slide-out menu --}}
        <div
            x-show="mobileSidebarOpen"
            x-transition:enter="transition transform duration-300 ease-out"
            x-transition:enter-start="-translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition transform duration-300 ease-in"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0"
            x-cloak
            class="absolute top-0 left-0 w-64 h-screen bg-[#141414] shadow-lg flex flex-col justify-between p-4"
            @click.away="mobileSidebarOpen = false"
        >
            {{-- Navigation List --}}
            <nav class="flex-1">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('user.dashboard') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-fill" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('user.reservation') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.reservation') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                            </svg>
                            <span>Reservations</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('user.records') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.records') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-check" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                            </svg>
                            <span>Records</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('user.payments') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.payments') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                            </svg>
                            <span>Payments</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('user.refund_request') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.refund_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                            </svg>
                            <span>Refunds</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('user.reschedule_request') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('user.reschedule') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-fill" viewBox="0 0 16 16">
                                <path d="M2 2v13.5a.5.5 0 0 0 .74.439L8 13.069l5.26 2.87A.5.5 0 0 0 14 15.5V2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2"/>
                            </svg>
                            <span>Reschedule Requests</span>
                        </a>
                    </li>

                    <li>
                        @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                        <a href="{{ route('user.notifications') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200 relative
                                {{ request()->routeIs('user.notifications') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                            </svg>
                            <span>Notifications</span>

                            @if($unreadCount > 0)
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('home.landing') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('home.landing') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                                <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                            </svg>
                            <span>Homepage</span>
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Profile and Logout--}}
            <div x-data="{ logout_modal: false }" class="border-t pt-4 mt-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-10">
                        <div class="flex items-center space-x-3">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/icons/ProfileIcon.jpg') }}"
                                alt="Profile Picture"
                                class="w-10 h-10 rounded-full border shadow-sm"
                            >
                            <a href="{{ route('user.profile') }}">
                                <p class="font-semibold text-white text-sm line-clamp-1 max-w-[150px] hover:underline">
                                    {{ Auth::user()->first_name ?? "Guest" }} {{ Auth::user()->last_name ?? "" }}
                                </p>
                            </a>
                        </div>

                        <x-button variant="transparent" @click="logout_modal = true" class="hover:font-bold text-white hover:bg-transparent active:bg-transparent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                            </svg>
                        </x-button>
                    </div>
                </div>

                <div x-show="logout_modal" x-transition x-cloak
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                >
                    <div class="bg-white rounded-xl shadow-lg p-6 w-80"
                        @click.outside="logout_modal = true"
                    >
                        <h2 class="text-lg font-bold text-gray-800">Confirm Logout</h2>
                        <p ss="text-gray-600 mt-2">Are you sure you want to logout?</p>

                        <div class="flex justify-end space-x-3 mt-4">
                            <x-button variant="secondary" @click="logout_modal = false">
                                Cancel
                            </x-button>

                            <form action="{{ route('logout') }}" method="POST"
                                onsubmit="this.querySelector('button[type=submit]').disabled = true;"
                            >
                                @csrf
                                <x-button type="submit" variant="danger">
                                    Logout
                                </x-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="flex-1 px-10 py-6 max-h-screen overflow-y-auto pt-20 lg:pt-6">
        @if (Auth::user()->email_verified_at === null)
            @php
                $cooldown = 0;
                if (session('last_verification_email_sent')) {
                    $secondsPassed = \Carbon\Carbon::parse(session('last_verification_email_sent'))->diffInSeconds(now());
                    $cooldown = max(0, 60 - intval($secondsPassed));
                }
            @endphp

            <div
                x-data="{
                    cooldown: {{ $cooldown }},
                    timer: null,
                    startTimer() {
                        if (this.cooldown > 0) {
                            this.timer = setInterval(() => {
                                if (this.cooldown > 0) {
                                    this.cooldown--;
                                } else {
                                    clearInterval(this.timer);
                                }
                            }, 1000);
                        }
                    }
                }"
                x-init="startTimer()"
                class="h-full flex justify-center items-center flex-col space-y-3"
            >
                <div class="flex flex-col space-y-3 justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                    </svg>
                    <p class="text-xl text-gray-800">Please <strong>verify your email address</strong> to continue using the system.</p>
                </div>

                {{-- Send --}}
                <form method="POST" action="{{ route('verification.send') }}"
                    onsubmit="this.querySelector('button[type=submit]').disabled = true;"
                >
                    @csrf
                    <x-button type="submit" variant="tertiary" x-bind:disabled="cooldown > 0">
                        <span x-cloak x-show="cooldown == 0">Send Verification Email</span>
                        <span x-cloak x-show="cooldown > 0">Resend in <span x-text="cooldown"></span>s</span>
                    </x-button>
                </form>
            </div>
        @elseif (Auth::user()->verified === 0)
            <div class="h-full flex justify-center items-center flex-col space-y-3">
                <div class="flex flex-col space-y-3 justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                    </svg>
                    <p class="text-xl text-gray-800 text-center">Your email is verified, but you still need <strong>admin approval</strong> before you can access the system. Please wait until an admin verifies your account.</p>
                </div>
            </div>
        @else
            {{ $slot }}
        @endif
    </main>

    {{-- Popup Messege --}}
    <div
        x-data="{ show: false, message: '', type: '' }"
        x-init="
            @if(session('success'))
                message = '{{ session('success') }}'; type = 'success'; show = true;
                setTimeout(() => show = false, 4000);
            @elseif(session('error'))
                message = '{{ session('error') }}'; type = 'error'; show = true;
                setTimeout(() => show = false, 4000);
            @endif
        "
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        class="fixed top-5 right-5 flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white z-50"
        :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
    >

        <span x-text="message"></span>

        <x-button variant="transparent" type="button" class="ml-2 text-white hover:text-gray-800" @click="show = false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
            </svg>
        </x-button>
    </div>
</body>

</html>
