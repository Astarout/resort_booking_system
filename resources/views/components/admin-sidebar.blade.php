
{{-- Admin Sidebar --}}
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/support/clock.js',
    ])
</head>

<body class="flex min-h-screen bg-gray-100">

    {{-- PC Sidebar --}}
    <aside
        class="hidden lg:flex w-64 bg-[#141414] shadow-lg flex-col justify-between p-4"
    >

        {{-- Logo and Toggle--}}
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-12 h-12 shadow-lg rounded-full">
                <span class="text-md font-bold text-white">Casa Alvadon</span>
            </div>
        </div>

        {{-- Navigation List--}}
        <nav class="flex-1">
            <ul class="space-y-1 overflow-y-auto max-h-[75vh] pr-1 scrollbar-thin">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                            <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.calendar') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.calendar') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-fill" viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                        <span>Calendar</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.expectedGuests') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.expectedGuests') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-check" viewBox="0 0 16 16">
                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                        </svg>
                        <span>Guests</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.accommodation') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.accommodation') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                            <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                            <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                        </svg>
                        <span>Accommodations</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.reservations') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.reservations') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks-fill" viewBox="0 0 16 16">
                            <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5z"/>
                            <path d="M4.268 1A2 2 0 0 1 6 0h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L13 13.768V2a1 1 0 0 0-1-1z"/>
                        </svg>
                        <span>Reservations</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.reschedule_request') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.reschedule_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-date-fill" viewBox="0 0 16 16">
                            <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm5.402 9.746c.625 0 1.184-.484 1.184-1.18 0-.832-.527-1.23-1.16-1.23-.586 0-1.168.387-1.168 1.21 0 .817.543 1.2 1.144 1.2"/>
                            <path d="M16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-6.664-1.21c-1.11 0-1.656-.767-1.703-1.407h.683c.043.37.387.82 1.051.82.844 0 1.301-.848 1.305-2.164h-.027c-.153.414-.637.79-1.383.79-.852 0-1.676-.61-1.676-1.77 0-1.137.871-1.809 1.797-1.809 1.172 0 1.953.734 1.953 2.668 0 1.805-.742 2.871-2 2.871zm-2.89-5.435v5.332H5.77V8.079h-.012c-.29.156-.883.52-1.258.777V8.16a13 13 0 0 1 1.313-.805h.632z"/>
                        </svg>
                        <span>Reschedule Request</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.payments') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.payments') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                        </svg>
                        <span>Payments</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.refund_request') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.refund_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill" viewBox="0 0 16 16">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                        </svg>
                        <span>Refunds</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.report') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.report') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        <span>Reports</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.gallery') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.gallery') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                            <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                            <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z"/>
                        </svg>
                        <span>Gallery</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.accounts') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.accounts') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                        </svg>
                        <span>Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.ratings') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.ratings') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half" viewBox="0 0 16 16">
                            <path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z"/>
                        </svg>
                        <span>Ratings</span>
                    </a>
                </li>

                <li>
                    @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                    <a href="{{ route('admin.notifications') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200 relative
                            {{ request()->routeIs('admin.notifications') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
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
                    <a href="{{  route('admin.backup_and_recovery') }}"
                        class="
                            flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                            {{ request()->routeIs('admin.backup_and_recovery') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                        "
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                        </svg>
                        <span>Backups</span>
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
        <div x-data="{ logout_modal: false }" class="border-t pt-4 mt-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-10">
                    <div class="flex items-center space-x-3">
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/icons/ProfileIcon.jpg') }}"
                            alt="Profile Picture"
                            class="w-10 h-10 rounded-full border shadow-sm"
                        >
                        <a href="{{ route('admin.profile') }}">
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
                <ul class="space-y-0.75 overflow-y-auto max-h-[85vh]">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart-steps" viewBox="0 0 16 16">
                                <path d="M.5 0a.5.5 0 0 1 .5.5v15a.5.5 0 0 1-1 0V.5A.5.5 0 0 1 .5 0M2 1.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1-.5-.5zm2 4a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.calendar') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.calendar') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-calendar-fill" viewBox="0 0 16 16">
                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                            </svg>
                            <span>Calendar</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.expectedGuests') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.expectedGuests') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-check" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                            </svg>
                            <span>Guests</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.accommodation') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.accommodation') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-journal-plus" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                                <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                            </svg>
                            <span>Accommodations</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reservations') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.reservations') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks-fill" viewBox="0 0 16 16">
                                <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5z"/>
                                <path d="M4.268 1A2 2 0 0 1 6 0h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L13 13.768V2a1 1 0 0 0-1-1z"/>
                            </svg>
                            <span>Reservations</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reschedule_request') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.reschedule_request') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-date-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm5.402 9.746c.625 0 1.184-.484 1.184-1.18 0-.832-.527-1.23-1.16-1.23-.586 0-1.168.387-1.168 1.21 0 .817.543 1.2 1.144 1.2"/>
                                <path d="M16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-6.664-1.21c-1.11 0-1.656-.767-1.703-1.407h.683c.043.37.387.82 1.051.82.844 0 1.301-.848 1.305-2.164h-.027c-.153.414-.637.79-1.383.79-.852 0-1.676-.61-1.676-1.77 0-1.137.871-1.809 1.797-1.809 1.172 0 1.953.734 1.953 2.668 0 1.805-.742 2.871-2 2.871zm-2.89-5.435v5.332H5.77V8.079h-.012c-.29.156-.883.52-1.258.777V8.16a13 13 0 0 1 1.313-.805h.632z"/>
                            </svg>
                            <span>Reschedule Request</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.payments') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.payments') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                            </svg>
                            <span>Payments</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.report') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.report') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-front-fill" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1zm3 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1z"/>
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.gallery') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.gallery') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-images" viewBox="0 0 16 16">
                                <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                <path d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z"/>
                            </svg>
                            <span>Gallery</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.accounts') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.accounts') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                            <span>Users</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.ratings') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.ratings') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half" viewBox="0 0 16 16">
                                <path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z"/>
                            </svg>
                            <span>Ratings</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.notifications') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200 relative
                                {{ request()->routeIs('admin.notifications') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                            </svg>
                            <span>Notifications</span>
                            @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="ml-auto inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="{{  route('admin.backup_and_recovery') }}"
                            class="
                                flex items-center space-x-3 px-3 py-2 rounded-lg transition duration-200
                                {{ request()->routeIs('admin.backup_and_recovery') ? 'bg-gray-200 text-gray-900 font-semibold' : 'text-white hover:underline' }}
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                            </svg>
                            <span>Backups</span>
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
                            <a href="{{ route('admin.profile') }}">
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
