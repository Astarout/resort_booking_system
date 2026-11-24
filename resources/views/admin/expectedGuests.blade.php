<x-admin-sidebar>

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Guests Today</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Today's Guests --}}
    <section class="mb-6 border-2 border-dashed border-gray-400 rounded-lg p-6">
        @if ($todaysGuests->isEmpty())
            <div class="font-sans text-base h-64 w-full flex flex-col items-center justify-center text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-people-fill mb-2 text-gray-400" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No guests for today.</p>
            </div>
        @else
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6 font-sans text-sm auto-rows-fr justify-items-center"
            >
                @foreach ($todaysGuests as $guest)
                    @php
                        $user = $guest->first()->user;
                    @endphp
                    <div
                        x-data
                        class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg border border-gray-200 h-full w-full max-w-xs justify-center cursor-pointer transform transition duration-200 hover:scale-105 hover:shadow-xl active:scale-95"
                        @click="$dispatch('open-guest-modal', {
                            first_name: '{{ $user->first_name }}',
                            last_name: '{{ $user->last_name }}',
                            email: '{{ $user->email }}',
                            phone: '{{ $user->phone ?? '' }}',
                            avatar: '{{ $user->avatar ?? '' }}',
                            reservations: [
                                @foreach ($guest as $reservation) {
                                    id: '{{ $reservation->id }}',
                                    accommodation: '{{ $reservation->accommodation->name ?? 'N/A' }}',
                                    check_in: '{{ \Carbon\Carbon::parse($reservation->check_in_date_time)->format('F j, Y h:i A') }}',
                                    check_out: '{{ \Carbon\Carbon::parse($reservation->check_out_date_time)->format('F j, Y h:i A') }}',
                                    time_in_logged: {{ $reservation->time_in_logged ? 'true' : 'false' }},
                                    time_out_logged: {{ $reservation->time_out_logged ? 'true' : 'false' }}
                                } @if(!$loop->last),@endif
                                @endforeach
                            ]
                        })"
                    >
                        <img
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/icons/ProfileIcon.jpg') }}"
                            alt="Profile"
                            class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 shadow-sm mb-4"
                        >

                        <p class="font-semibold text-gray-900 text-lg text-center">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </p>

                        <p class="text-gray-500 text-sm text-center mt-1">
                            Tap to view details
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Guest modal --}}
    <div
        x-data="{ openGuestModal: false, guest: {}, showConfirmModal: false, actionType: '', reservationId: null }"
        x-on:open-guest-modal.window="
            guest = $event.detail;
            openGuestModal = true;
        "
        x-show="openGuestModal"
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] p-6 transform transition-all duration-300 scale-95 relative mx-5 flex flex-col"
            x-show="openGuestModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
        >
            <x-button
                @click="openGuestModal = false"
                variant="transparent"
                class="absolute top-4 right-4"
            ><i class="bi bi-x-lg"></i></x-button>

            <div class="flex flex-col items-center my-4">
                <img
                    :src="guest.avatar ? `/storage/${guest.avatar}` : '/storage/icons/ProfileIcon.jpg'"
                    alt="Guest Profile"
                    class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 shadow-lg mb-4"
                >
                <h2 class="text-xl font-bold text-gray-800" x-text="guest.first_name + ' ' + guest.last_name"></h2>
                <p class="text-gray-500 mt-1" x-text="guest.email"></p>
            </div>

            {{-- Additional Details --}}
            <div class="mt-4 space-y-4 overflow-y-auto flex-1">
                <template x-for="res in guest.reservations" :key="res.id">
                    <div class="p-4 rounded-xl border shadow-sm bg-gradient-to-r from-blue-50 to-white hover:shadow-md transition">

                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <span x-text="res.accommodation"></span>
                            </h3>
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600 font-medium">
                                ID: <span x-text="res.id"></span>
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Date</span>
                                <span class="text-gray-900 font-semibold" x-text="res.desired_date"></span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Check-in</span>
                                <span class="text-gray-900 font-semibold" x-text="res.check_in"></span>
                            </div>

                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Check-out</span>
                                <span class="text-gray-900 font-semibold" x-text="res.check_out"></span>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end items-center gap-2">

                            <!-- Time In -->
                            <template x-if="!res.time_in_logged">
                                <button
                                    @click.prevent="showConfirmModal = true; actionType = 'time-in'; reservationId = res.id"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                >Time in</button>
                            </template>

                            <!-- Time Out -->
                            <template x-if="res.time_in_logged && !res.time_out_logged">
                                <button
                                    @click.prevent="showConfirmModal = true; actionType = 'time-out'; reservationId = res.id"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition"
                                >Time out</button>
                            </template>

                            <template x-if="res.time_in_logged && res.time_out_logged">
                                <span class="text-green-600 font-semibold">Checked out</span>
                            </template>

                        </div>
                    </div>
                </template>
            </div>

            {{-- Confirm Modal --}}
            <div
                x-show="showConfirmModal"
                x-transition
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 rounded-2xl"
            >
                <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-lg mx-5">
                    <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="actionType === 'time-in' ? 'Confirm Time In' : 'Confirm Time Out'"></h3>
                    <p class="mb-4" x-text="actionType === 'time-in' ? 'Are you sure you want to record Time In for this reservation?' : 'Are you sure you want to record Time Out for this reservation?'"></p>
                    <div class="flex justify-end gap-2">
                        <button @click="showConfirmModal = false" class="px-4 py-2 rounded-lg border hover:bg-gray-100 transition">Cancel</button>

                        <form :action="`/admin/expected-guests/${actionType}`" method="POST">
                            @csrf
                            <input type="hidden" name="reservation_id" :value="reservationId">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Logs Table Section --}}
    <section class="mb-6 border-2 border-dashed border-gray-400 rounded-lg p-6">
        @if($logs->isEmpty())
            <div class="font-sans text-base h-32 w-full flex flex-col items-center justify-center text-center">
                <p class="text-gray-500 text-lg font-medium">No logs available.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-xl shadow-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Reservation ID</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Accommodation</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Time In</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Time Out</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Out Status</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Additional Fee</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Paid</th>
                            <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Log By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr class="border-t border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->reservation->user->first_name ?? '' }} {{ $log->reservation->user->last_name ?? '' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->reservation_id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->reservation->accommodation->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->date_and_time_in ? $log->date_and_time_in->format('M d, Y h:i A') : '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->date_and_time_out ? $log->date_and_time_out->format('M d, Y h:i A') : '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 text-center capitalize">{{ $log->status ? $log->status : '-'}}</td>

                                <td class="px-4 py-2 text-sm text-center">
                                    @if($log->additional_fee !== null)
                                        <span class="text-green-600 font-semibold">₱{{ number_format((float)$log->additional_fee, 2) }}</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-5 text-sm text-center">
                                    @if($log->is_paid)
                                        <span class="text-green-600 font-semibold">Yes</span>
                                    @elseif($log->additional_fee > 0)
                                        <form action="{{ route('admin.expectedGuests.markFeePaid') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="reservation_log_id" value="{{ $log->id }}">
                                            <x-button
                                                type="submit" variant="primary"
                                            >Paid</x-button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2 text-sm text-gray-800 text-center">{{ $log->user->first_name ?? '' }} {{ $log->user->last_name ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="my-4">
        {{ $logs->links() }}
    </section>

</x-admin-sidebar>
