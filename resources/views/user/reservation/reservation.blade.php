
{{-- User Reservation Page --}}
<x-user-sidebar>

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Reservations</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div
        x-data="accommodationSearch({{ $myReservations->count() > 0 ? 'true' : 'false' }})"
        x-init="init()"
    >
        {{-- Controls --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            {{-- Search bar  --}}
            <div class="w-full sm:w-1/2">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                        x-model="query"
                        @input="filter()"
                    >

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>
            </div>

            {{-- Reservation Button --}}
            <div>
                <x-button
                    variant="primary"
                    class="flex justify-center items-center gap-2"
                    @click="handleReservationClick"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-plus-fill" viewBox="0 0 16 16">
                        <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm4.5 6V9H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V10H6a.5.5 0 0 1 0-1h1.5V7.5a.5.5 0 0 1 1 0"/>
                    </svg>
                    Reserve now
                </x-button>
            </div>
        </div>

        {{-- Accommodation Grid --}}
        <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <template x-for="accommodation in filtered" :key="accommodation.id">
                <div class="relative bg-white rounded-xl shadow hover:shadow-lg overflow-hidden group">
                    <img :src="accommodation.image ? `{{ asset('storage') }}/${accommodation.image}` : `{{ asset('storage/icons/ProfileIcon.jpg') }}`" alt="image" class="w-full h-48 object-cover">

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex justify-center items-center gap-3 transition">
                        <a :href="`/user/reservation/view/${accommodation.id}`">
                            <x-button type="button" variant="primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                            </x-button>
                        </a>
                    </div>

                    <div class="p-3">
                        <p class="text-sm text-gray-700 font-medium text-center">
                            <span x-text="accommodation.name"></span>
                            <template x-if="accommodation.tour_type">
                                <span>
                                    <span class="text-gray-500">—</span>
                                    <span class="capitalize text-gray-600" x-text="accommodation.tour_type"></span>
                                </span>
                            </template>
                        </p>
                    </div>
                </div>
            </template>

            <template x-if="filtered.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-house-slash-fill" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                        <path d="m8 3.293 4.712 4.712A4.5 4.5 0 0 0 8.758 15H3.5A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                        <path d="M13.879 10.414a2.5 2.5 0 0 0-3.465 3.465zm.707.707-3.465 3.465a2.501 2.501 0 0 0 3.465-3.465m-4.56-1.096a3.5 3.5 0 1 1 4.949 4.95 3.5 3.5 0 0 1-4.95-4.95Z"/>
                    </svg>

                    <p class="text-2xl mt-5 font-medium">Accommodation not found</p>
                </div>
            </template>
        </section>

        {{-- Modal --}}
        <div
            x-show="showModal"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            x-cloak
        >
            <div
                class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 mx-5"
                x-show="showModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
            >
                {{-- Header with title + X button --}}
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">
                        Reservation Notice
                    </h2>

                    <button
                        @click="showModal = false"
                        class="text-gray-500 hover:text-gray-700"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <p class="text-gray-600 mb-4">
                    Please review your existing reservations before making a new one to avoid any scheduling conflicts.
                    You may continue or visit your records for more details.
                </p>

                {{-- List of reservations --}}
                <div class="max-h-60 overflow-y-auto mb-6">
                    @foreach ($myReservations as $res)
                        <div class="border rounded-lg p-3 mb-2">
                            <p class="text-gray-800 font-semibold">
                                {{ $res->accommodation->name ?? 'Unknown Accommodation' }}
                            </p>

                            <p class="text-gray-600 text-sm">
                                Check-in: {{ \Carbon\Carbon::parse($res->check_in_date_time)->format('l, F d, Y, g:i A') }}
                            </p>

                            <p class="text-gray-600 text-sm">
                                Check-out: {{ \Carbon\Carbon::parse($res->check_out_date_time)->format('l, F d, Y, g:i A') }}
                            </p>

                            <p class="text-sm text-{{ $res->status === 'PENDING' ? 'red-600' : 'green-600' }}">
                                Status: {{ ucfirst($res->status) }}
                            </p>
                        </div>
                    @endforeach
                </div>

                {{-- Footer buttons --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('user.records') }}">
                        <x-button variant="secondary" class="w-full sm:w-auto">Go to Records</x-button>
                    </a>

                    <a href="{{ route('user.reservation.form') }}">
                        <x-button variant="primary" class="w-full sm:w-auto">Continue</x-button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function accommodationSearch(hasOngoing) {
            return {
                query: localStorage.getItem('accommodationSearchQuery') || '',
                accommodations: @json($accommodations),
                filtered: [],
                showModal: false,
                hasOngoing: hasOngoing,

                init() {
                    this.filtered = this.accommodations;
                    this.filter();
                },

                filter() {
                    const q = this.query.toLowerCase();
                    this.filtered = this.accommodations.filter(a => a.name.toLowerCase().includes(q));

                    localStorage.setItem('accommodationSearchQuery', this.query);
                },

                handleReservationClick() {
                    if (this.hasOngoing) {
                        this.showModal = true;
                    } else {
                        window.location.href = "/user/reservation/form";
                    }
                }
            }
        }
    </script>
</x-user-sidebar>
