
{{-- User Record Page --}}
<x-user-sidebar>
    @vite([
        'resources/js/documents/reservationExport.js',
    ])

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Records</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Body --}}
    <div
        x-data="{
            reservations: @js($reservations->items()),
            pagination: @js($reservations->toArray()),

            showExportChoice: false,
            showDeleteModal: false,
            showViewModal: false,
            showRescheduleModal: false,
            showCancelModal: false,

            selectedReservation: null,

            cancelReservationId: null,

            status: localStorage.getItem('status') || 'all',
            search: localStorage.getItem('recordSearchQuery') || '',

            get filteredReservations() {
                return this.reservations.filter(r => {
                    let statusMatch = this.status === 'all' || r.status === this.status;
                    let searchText = this.search.toLowerCase();
                    let searchMatch = !this.search
                        || (r.id && r.id.toString().toLowerCase().includes(searchText))
                        || (r.accommodation?.name && r.accommodation.name.toLowerCase().includes(searchText));

                    return statusMatch && searchMatch;
                });
            },
        }"
        x-init="
            $watch('status', v => localStorage.setItem('status', v));
            $watch('search', v => localStorage.setItem('recordSearchQuery', v));
        "
    >
        {{-- Filter and Controls --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            {{-- Search Bar --}}
            <div class="w-full sm:w-1/2">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search..."
                        x-model="search"
                        class="w-full pl-4 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                    >

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>
            </div>

            {{-- Filter --}}
            <div class="flex gap-2 flex-wrap">
                <button
                    @click="status = 'all'"
                    :class="status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="status = 'PENDING'"
                    :class="status === 'PENDING' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">PENDING</button>
                <button
                    @click="status = 'CANCELLED'"
                    :class="status === 'CANCELLED' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">CANCELLED</button>

                <button
                    @click="status = 'APPROVED'"
                    :class="status === 'APPROVED' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">APPROVED</button>
                <button
                    @click="status = 'DONE'"
                    :class="status === 'DONE' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">DONE</button>
            </div>
        </div>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $reservations->links() }}
        </section>

        {{-- Desktop Table --}}
        <section class="overflow-x-auto bg-white shadow-md rounded-lg border border-gray-200 hidden md:block">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-center">Reservation ID</th>
                        <th class="px-6 py-3 text-center">Accommodation</th>
                        <th class="px-6 py-3 text-center">Price</th>
                        <th class="px-6 py-3 text-center">Date</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="reservation in filteredReservations" :key="reservation.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-800 text-center" x-text="reservation.id"></td>
                            <td class="px-6 py-3 text-gray-700 text-center" x-text="reservation.accommodation && reservation.accommodation.name ? reservation.accommodation.name : 'N/A'"></td>
                            <td class="px-6 py-3 font-medium text-green-500 text-center" x-text="reservation.total_price !== undefined ? new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(reservation.total_price) : '₱0.00'"></td>
                            <td class="px-6 py-3 text-gray-700 text-center" x-text="new Date(reservation.check_in_date_time).toLocaleDateString(undefined,{year:'numeric',month:'long',day:'numeric'}) + ' - ' + new Date(reservation.check_out_date_time).toLocaleDateString(undefined,{year:'numeric',month:'long',day:'numeric'})"></td>
                            <td class="px-6 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold text-center"
                                    :class="{
                                        'bg-green-100 text-green-700': reservation.status === 'APPROVED',
                                        'bg-red-100 text-red-700': reservation.status === 'PENDING',
                                        'bg-yellow-100 text-yellow-700': reservation?.status === 'CANCELLED',
                                        'bg-blue-100 text-blue-700': reservation.status === 'DONE',
                                        'bg-gray-100 text-red-700': !['APPROVED','PENDING','DONE', 'CANCELLED'].includes(reservation.status)
                                    }"
                                    x-text="reservation.status ?? 'pending'">
                                </span>
                            </td>
                            <td class="px-6 py-3 flex items-center justify-center gap-3 text-center">
                                <x-button variant="primary" class="rounded-lg" @click.prevent="selectedReservation = reservation; showViewModal = true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill">
                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                    </svg>
                                </x-button>

                                {{-- Export Button [print or download pdf] --}}
                                <x-button
                                    variant="secondary"
                                    class="rounded-lg"
                                    x-show="reservation.status !== 'PENDING'"
                                    @click.prevent="selectedReservation = reservation; showExportChoice = true"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                        <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                                        <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                    </svg>
                                </x-button>

                                {{-- Delete --}}
                                <template x-if="reservation.status !== 'APPROVED' && reservation.status !== 'DONE' && reservation.paid_balance == 0">
                                    <x-button variant="danger" class="rounded-lg" @click.prevent="selectedReservation = reservation; showDeleteModal = true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                    </x-button>
                                </template>
                            </td>
                        </tr>
                    </template>

                    <template x-if="filteredReservations.length === 0">
                        <tr>
                            <td colspan="6" class="py-10 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-journal-x" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708"/>
                                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                                    </svg>
                                    <p class="text-lg mt-3 font-medium">Reservation not found</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </section>

        {{-- Mobile Cards --}}
        <section class="space-y-4 md:hidden">
            <template x-for="reservation in filteredReservations" :key="reservation.id">
                <div class="border rounded-lg shadow p-4 bg-white">
                    <div class="mb-3">
                        <p class="text-sm text-gray-500">Reservation ID</p>
                        <p class="font-medium text-gray-800" x-text="reservation.id"></p>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">Accommodation</p>
                        <p class="font-medium" x-text="reservation.accommodation && reservation.accommodation.name ? reservation.accommodation.name : 'N/A'"></p>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="font-medium text-green-500" x-text="reservation.total_price !== undefined ? new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(reservation.total_price) : '₱0.00'"></p>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-medium text-gray-800" x-text="new Date(reservation.check_in_date_time).toLocaleDateString(undefined,{year:'numeric',month:'long',day:'numeric'}) + ' - ' + new Date(reservation.check_out_date_time).toLocaleDateString(undefined,{year:'numeric',month:'long',day:'numeric'})"></p>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                              :class="{
                                'bg-green-100 text-green-700': reservation.status === 'APPROVED',
                                'bg-red-100 text-red-700': reservation.status === 'PENDING',
                                'bg-yellow-100 text-yellow-700': reservation?.status === 'CANCELLED',
                                'bg-blue-100 text-blue-700': reservation.status === 'DONE',
                                'bg-gray-100 text-red-700': !['APPROVED','PENDING','DONE', 'CANCELLED'].includes(reservation.status)
                              }"
                              x-text="reservation.status ?? 'pending'">
                        </span>
                    </div>

                    <div class="flex gap-3 mt-3">
                        <x-button variant="primary" class="rounded-lg flex-1"
                            @click.prevent="selectedReservation = reservation; showViewModal = true"
                        >View</x-button>

                        {{-- Export Button [print or download pdf] --}}
                        <x-button
                            variant="secondary"
                            class="rounded-lg flex-1"
                            x-show="reservation.status !== 'PENDING'"
                            @click.prevent="selectedReservation = reservation; showExportChoice = true"
                        >Export</x-button>

                        <template x-if="reservation.status !== 'APPROVED' && reservation.status !== 'DONE' && reservation.paid_balance == 0">
                            <x-button variant="danger" class="rounded-lg flex-1"
                                @click.prevent="selectedReservation = reservation; showDeleteModal = true"
                            >Delete</x-button>
                        </template>
                    </div>
                </div>
            </template>

            {{-- No Reservation (Mobile) --}}
            <template x-if="filteredReservations.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-journal-x" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708"/>
                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                    </svg>
                    <p class="text-lg mt-3 font-medium">Reservation not found</p>
                </div>
            </template>
        </section>

        {{-- View Reservation Modal --}}
        <div
            x-show="showViewModal"
            x-cloak
            @keydown.escape.window="showViewModal = false; selectedReservation = null"
            x-init="$watch('showViewModal', v => document.body.style.overflow = v ? 'hidden' : 'auto')"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 py-10 overflow-y-auto"
        >
            {{-- Modal Container --}}
            <div
                class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden relative flex flex-col md:flex-row border border-gray-100 max-h-[90vh]"
                x-show="showViewModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
            >
                {{-- Close Button --}}
                <x-button
                    @click="showViewModal = false; selectedReservation = null"
                    class="absolute top-4 right-4"
                    variant="transparent"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                {{-- Image --}}
                <div class="w-full md:w-1/2 h-56 md:h-auto relative">
                    <img
                        :src="selectedReservation?.accommodation?.image ? '{{ asset('storage') }}/' + selectedReservation.accommodation.image : '{{ asset('storage/icons/ProfileIcon.jpg') }}'"
                        alt="Accommodation Image"
                        class="object-cover w-full h-full"
                    >

                    <div class="absolute bottom-0 w-full bg-gradient-to-t from-black/60 to-transparent p-4 text-white">
                        <h3 class="text-lg font-semibold truncate" x-text="selectedReservation?.accommodation?.name ?? 'N/A'"></h3>
                        <p class="text-sm text-gray-200" x-text="selectedReservation?.accommodation?.tour_type ?? ''"></p>
                    </div>
                </div>

                {{-- Details --}}
                <div class="w-full md:w-1/2 p-6 flex flex-col justify-between max-h-[85vh] overflow-y-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Reservation Overview</h2>

                    {{-- Customer Details --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Customer Details</h3>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Name:</span>
                                <span class="font-semibold text-gray-800 text-right" x-text="selectedReservation?.user ? (selectedReservation.user.first_name + ' ' + selectedReservation.user.last_name) : 'N/A'"></span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-semibold text-gray-800 text-right truncate" x-text="selectedReservation?.user?.email ?? 'N/A'"></span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Contact:</span>
                                <span class="font-semibold text-gray-800 text-right" x-text="selectedReservation?.user?.phone ?? 'N/A'"></span>
                            </div>

                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Address:</span>
                                <span class="font-semibold text-gray-800 text-right truncate" x-text="selectedReservation?.user?.address ?? 'N/A'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Accommodation Details --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Accommodation Details</h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Accommodation:</span>
                                <span class="font-semibold text-gray-800 text-right capitalize" x-text="selectedReservation?.accommodation?.name ?? 'N/A'"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Type:</span>
                                <span class="font-semibold text-gray-800 text-right capitalize" x-text="selectedReservation?.accommodation?.tour_type ?? 'N/A'"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Reservation Details --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Reservation Details</h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Reservation ID:</span>
                                <span class="font-semibold text-gray-800" x-text="selectedReservation?.id"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Reservation Date:</span>
                                <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Date(selectedReservation.reservation_date).toLocaleDateString(undefined, {year:'numeric',month:'long',day:'numeric'}) : ''"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-in:</span>
                                <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Date(selectedReservation.check_in_date_time).toLocaleString(undefined, { weekday:'long', year:'numeric', month:'long', day:'numeric', hour:'numeric', minute:'2-digit', hour12:true }) : ''"></span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">Check-out:</span>
                                <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Date(selectedReservation.check_out_date_time).toLocaleString(undefined, { weekday:'long', year:'numeric', month:'long', day:'numeric', hour:'numeric', minute:'2-digit', hour12:true }) : ''"></span>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Details --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Payment Details</h3>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Price per Day:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.accommodation_price ?? 0) : '₱0.00'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Discount Applied To Accommodation:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? (selectedReservation.discount_applied ? selectedReservation.discount_applied + '%' : '0%') : '0%'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Stay in Duration (Days)</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation?.no_of_days_stay ?? 'N/A'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Entrance Fee per Pax:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.entrance_fee_per_pax ?? 0) : '₱0.00'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">No. of guests:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation?.no_of_guest ?? 'N/A'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Entrance Fee:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.total_entrance_fee ?? 0) : '₱0.00'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Price:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.total_price ?? 0) : '₱0.00'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Paid Balance:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.paid_balance ?? 0) : '₱0.00'"></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Remaining Balance:</span>
                            <span class="font-semibold text-gray-800" x-text="selectedReservation ? new Intl.NumberFormat('en-PH', {style:'currency',currency:'PHP'}).format(selectedReservation.remaining_balance ?? 0) : '₱0.00'"></span>
                        </div>
                    </div>

                    {{-- Other Details --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Other Details</h3>

                        <div class="space-y-2 text-sm">
                            {{-- Sheet Color --}}
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500">Sheet Color:</span>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-5 h-5 rounded-full border border-gray-300"
                                        :style="`background-color: ${selectedReservation?.sheet_color ?? '#FFFFFF'}`"
                                    ></div>
                                    <span class="font-semibold text-gray-800" x-text="selectedReservation?.sheet_color ?? 'N/A'"></span>
                                </div>
                            </div>

                            {{-- Pets --}}
                            <div class="flex justify-between items-start">
                                <span class="text-gray-500">Pets:</span>

                                <div class="text-right">
                                    <template x-if="selectedReservation?.pets">
                                        <div class="flex flex-col items-end space-y-1 font-semibold text-gray-800">
                                            <template x-for="(pet, index) in JSON.parse(selectedReservation.pets)" :key="index">
                                                <span x-text="`${pet.name} — ${pet.quantity}`"></span>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="!selectedReservation?.pets">
                                        <span class="font-semibold text-gray-800">N/A</span>
                                    </template>
                                </div>
                            </div>

                            {{-- Valid ID --}}
                            <div class="flex flex-col space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">Valid ID:</span>

                                    <template x-if="!selectedReservation?.valid_id">
                                        <span class="font-semibold text-gray-800">N/A</span>
                                    </template>
                                </div>

                                <template x-if="selectedReservation?.valid_id">
                                    <div class="mt-2 flex justify-center">
                                        <a
                                            :href="`{{ route('user.records.showValidID', '') }}/${selectedReservation.id}`"
                                            target="_blank"
                                            class="block"
                                        >
                                            <img
                                                :src="`{{ route('user.records.showValidID', '') }}/${selectedReservation.id}`"
                                                alt="Valid ID"
                                                class="w-60 h-auto rounded-lg shadow-md border border-gray-300 hover:scale-105 transition-transform duration-200 cursor-pointer"
                                            >
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Status & Actions --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2 border-b border-gray-200 pb-1">Status</h3>

                        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-4">
                            <span class="text-sm font-medium text-gray-600">Status:</span>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold capitalize"
                                :class="{
                                    'bg-red-100 text-red-700': selectedReservation?.status === 'PENDING',
                                    'bg-yellow-100 text-yellow-700': selectedReservation?.status === 'CANCELLED',
                                    'bg-green-100 text-green-700': selectedReservation?.status === 'APPROVED',
                                    'bg-blue-100 text-blue-700': selectedReservation?.status === 'DONE',
                                    'bg-gray-100 text-gray-600': !selectedReservation?.status
                                }"
                                x-text="selectedReservation?.status ?? 'Pending'"
                            ></span>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            <x-button
                                variant="secondary"
                                @click="showViewModal = false; selectedReservation = null"
                            >Close</x-button>

                            <x-button
                                variant="danger"
                                @click.prevent="cancelReservationId = selectedReservation.id; showCancelModal = true"
                                x-show="
                                    selectedReservation?.status == 'APPROVED' &&
                                    selectedReservation?.status !== 'DONE' &&
                                    selectedReservation?.paid_balance >= (selectedReservation?.total_price * 0.5) &&
                                    new Date(selectedReservation?.reservation_date) >= new Date(Date.now() - 10 * 24 * 60 * 60 * 1000)
                                "
                            >Cancel</x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div
            x-show="showDeleteModal"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div
                class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-6"
                @click.away="showDeleteModal = false; selectedReservation = null"
                x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
            >
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Delete</h2>

                <p class="text-gray-600 mb-6">
                    Are you sure you want to delete reservation
                    <span class="font-medium" x-text="selectedReservation ? selectedReservation.accommodation.name : ''"></span>?
                    This action cannot be undone.
                </p>

                 <div class="flex justify-end gap-3">
                    <x-button
                        type="button"
                        variant="secondary"
                        @click="showDeleteModal = false; selectedReservation = null"
                    >Cancel</x-button>

                    <form :action="`/user/records/delete/${selectedReservation ? selectedReservation.id : ''}`" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-button variant="danger" type="submit">Delete</x-button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cancel Confirmation Modal --}}
        <div
            x-show="showCancelModal"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div
                class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-6"
                x-show="showCancelModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
            >
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Confirm Cancel</h2>

                <p class="text-gray-600 mb-6">
                    Are you sure you want to cancel
                    <span class="font-medium" x-text="selectedReservation ? selectedReservation.accommodation.name : ''"></span>?
                    This action cannot be undone.
                </p>

                 <div class="flex justify-end gap-3">
                    <x-button
                        type="button"
                        variant="secondary"
                        @click="showCancelModal = false; cancelReservationId = null"
                    >back</x-button>

                    <form :action="`/user/records/cancel/${cancelReservationId}`" method="POST">
                        @csrf
                        @method('PUT')
                        <x-button variant="danger" type="submit">Yes, Cancel</x-button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Export Choice Modal --}}
        <div
            x-show="showExportChoice"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div
                class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-6 relative"
                @click.away="showExportChoice = false"
                x-show="showExportChoice"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
            >
                <x-button
                    variant="transparent"
                    @click="showExportChoice = false"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-xl font-semibold text-gray-800 mb-4">Export Reservation</h2>

                <p class="text-gray-600 mb-6">Do you want to download the reservation details as PDF or print them?</p>

                <div class="flex justify-end gap-3">
                    <x-button
                        type="button"
                        variant="secondary"
                        @click="showExportChoice = false; window.dispatchEvent(new CustomEvent('generate-pdf', { detail: { type: 'download', reservation: selectedReservation } }))"
                    >Download</x-button>

                    <x-button
                        type="button"
                        variant="primary"
                        @click="showExportChoice = false; window.dispatchEvent(new CustomEvent('generate-pdf', { detail: { type: 'print', reservation: selectedReservation } }))"
                    >Print</x-button>
                </div>
            </div>
        </div>
    </div>
</x-user-sidebar>
