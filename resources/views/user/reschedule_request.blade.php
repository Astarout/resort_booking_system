
{{--  Admin Reschedule Request Page --}}
<x-user-sidebar>
    @vite([
        'resources/js/landing/myFlatPickr.js',
    ])

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Reschedule Requests</h1>
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
            fullyBooked: @js($fullyBookedByAccommodation),

            showRequestModal: false,
            showConfirmModal: false,
            showAvailableReservationsForReschedule: false,
            showRescheduleRequestModal: false,

            selectedAction: '',
            selected: {},

            selectedReservation: null,
            flatpickrInstance: null,

            init() {
                this.$watch('selectedReservation', (reservation) => {
                    this.initFlatpickr(reservation);
                });
            },

            initFlatpickr(reservation) {
                if (this.flatpickrInstance) {
                    this.flatpickrInstance.destroy();
                }

                let accId = reservation ? reservation.accommodation_id : null;
                let disabledDates = accId && this.fullyBooked[accId] ? this.fullyBooked[accId] : [];

                let maxDays = reservation ? reservation.no_of_days_stay : 1;

                let checkInTime = reservation?.accommodation?.check_in_time || '08:00';
                let checkOutTime = reservation?.accommodation?.check_out_time || '08:00';

                checkInTime = checkInTime.split(' ')[1] || checkInTime;
                checkOutTime = checkOutTime.split(' ')[1] || checkOutTime;

                const pad = (n) => n.toString().padStart(2, '0');

                const formatDateTime = (dateObj, time) => {
                    return `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())} ${time}`;
                };

                const isDateDisabled = (dateObj) => {
                    const dateStr = `${dateObj.getFullYear()}-${pad(dateObj.getMonth()+1)}-${pad(dateObj.getDate())}`;
                    return disabledDates.includes(dateStr);
                };

                this.flatpickrInstance = flatpickr('#date', {
                    dateFormat: 'Y-m-d',
                    disable: disabledDates,
                    minDate: new Date(Date.now() + (2 * 24 * 60 * 60 * 1000)),

                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        let dateStr = fp.formatDate(dayElem.dateObj, 'Y-m-d');

                        if (disabledDates.includes(dateStr)) {
                            dayElem.classList.add('reserved-date');
                        }
                    },

                    onChange: (selectedDates) => {
                        if (!selectedDates.length) return;

                        let newDate = selectedDates[0];
                        let checkIn = formatDateTime(newDate, checkInTime);

                        let checkOutDate = new Date(newDate.getTime());
                        checkOutDate.setDate(checkOutDate.getDate() + maxDays - 1);

                        const [checkInHour, checkInMinute] = checkInTime.split(':').map(Number);
                        const [checkOutHour, checkOutMinute] = checkOutTime.split(':').map(Number);

                        if (checkOutHour < checkInHour || (checkOutHour === checkInHour && checkOutMinute <= checkInMinute)) {
                            checkOutDate.setDate(checkOutDate.getDate() + 1);
                        }

                        let valid = true;

                        for (let i = 1; i <= maxDays; i++) {
                            let tempDate = new Date(newDate.getTime());
                            tempDate.setDate(tempDate.getDate() + i);

                            if (isDateDisabled(tempDate)) {
                                valid = false;
                                break;
                            }
                        }

                        let checkOut = valid ? formatDateTime(checkOutDate, checkOutTime) : 'Invalid date';

                        if (!valid) {
                            checkIn = 'Invalid date';
                        }

                        document.getElementById('new_check_in_date_time').value = checkIn;
                        document.getElementById('new_check_out_date_time').value = checkOut;

                        let checkInDateTime = new Date(checkIn);
                        let checkOutDateTime = new Date(checkOut);

                        document.getElementById('check_in_date_time').value = checkInDateTime.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });

                        document.getElementById('check_out_date_time').value = checkOutDateTime.toLocaleString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        });
                    },
                });
            },

            openModal(reservation) {
                this.selected = reservation;
                this.showRequestModal = true;
            },

            closeModal() {
                this.showRequestModal = false;
                this.selected = {};
            },

            confirmAction() {
                this.showConfirmModal = true;
                this.showRequestModal = false;
            },

            performAction() {
                let action = this.selectedAction.toLowerCase();
                window.location.href = `/admin/reschedule-request/${action}/${this.selected.id}`;
            },

            reservations: {{ $rescheduleRequests->map(function($r) {
                return [
                    'id' => $r->id,
                    'reservation_id' => $r->reservation->id,
                    'request_status' => $r->status,
                    'check_in_out_date_time' => \Carbon\Carbon::parse($r->reservation->check_in_date_time)->format('F j, Y h:i A') . ' - ' . \Carbon\Carbon::parse($r->reservation->check_out_date_time)->format('F j, Y h:i A'),
                    'new_date_time' => \Carbon\Carbon::parse($r->new_check_in_date_time)->format('F j, Y h:i A') . ' - ' . \Carbon\Carbon::parse($r->new_check_out_date_time)->format('F j, Y h:i A'),
                    'reason' => $r->reason,
                    'accommodation' => ['name' => $r->reservation->accommodation->name],
                    'user' => [
                        'first_name' => $r->reservation->user->first_name,
                        'last_name' => $r->reservation->user->last_name,
                        'avatar' => $r->reservation->user->avatar,
                        'id' => $r->reservation->user->id,
                    ]
                ];
            })->toJson() }},

            request_status: localStorage.getItem('request_status') || 'all',

            search: localStorage.getItem('rescheduleRequestSearchQuery') || '',

            get filteredReservations() {
                return this.reservations.filter(r => {
                    let statusMatch = this.request_status === 'all' || r.request_status === this.request_status;

                    let searchText = this.search.toLowerCase();

                    let searchMatch = !this.search
                        || (r.reservation_id && r.reservation_id.toString().toLowerCase().includes(searchText))
                        || (r.user?.id && r.user?.id.toString().toLowerCase().includes(searchText))
                        || (r.accommodation?.name?.toLowerCase().includes(searchText))
                        || (r.user?.first_name?.toLowerCase().includes(searchText))
                        || (r.user?.last_name?.toLowerCase().includes(searchText))
                        || ((r.user?.first_name + ' ' + r.user?.last_name).toLowerCase().includes(searchText))
                        || ((r.user?.last_name + ' ' + r.user?.first_name).toLowerCase().includes(searchText));

                    return statusMatch && searchMatch;
                });
            }
        }"
        x-init="
            $watch('request_status', v => localStorage.setItem('request_status', v));
            $watch('search', v => localStorage.setItem('rescheduleRequestSearchQuery', v));
        "
    >

        {{-- Controls --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            {{-- Search --}}
            <div class="w-full sm:w-1/2">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search by U-ID, R-ID, Name..."
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
                    @click="request_status = 'all'"
                    :class="request_status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="request_status = 'PENDING'"
                    :class="request_status === 'PENDING' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">PENDING</button>
                <button
                    @click="request_status = 'APPROVED'"
                    :class="request_status === 'APPROVED' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">APPROVED</button>
                <button
                    @click="request_status = 'DENIED'"
                    :class="request_status === 'DENIED' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">DENIED</button>
            </div>

            <div>
                <x-button variant="primary" class="flex gap-2" @click="showAvailableReservationsForReschedule = true">
                    <i class="bi bi-calendar-fill"></i>
                    Reschedule
                </x-button>
            </div>
        </div>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $rescheduleRequests->links() }}
        </section>

        {{-- Reschedule Requests Cards--}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 p-1 mt-6">
            <template x-for="r in filteredReservations" :key="r.id">
                <div
                    x-transition.opacity.scale
                    class="w-full bg-white shadow-md rounded-xl p-5 flex flex-col justify-between border border-gray-200 hover:shadow-lg transition duration-200"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <img
                                :src="`/storage/${r.user.avatar}`"
                                alt="Profile"
                                class="w-10 h-10 rounded-full object-cover border-blue-600 border-2"
                            >
                            <div>
                                <h2 class="font-semibold text-gray-800 text-sm" x-text="r.user.first_name + ' ' + r.user.last_name"></h2>
                                <p class="text-xs text-gray-500" x-text="'U-ID: ' + r.user.id"></p>
                            </div>
                        </div>

                        <span
                            class="px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap"
                            :class="{
                                'bg-yellow-100 text-yellow-800': r.request_status === 'PENDING',
                                'bg-green-100 text-green-800': r.request_status === 'APPROVED',
                                'bg-red-100 text-red-800': r.request_status === 'DENIED',
                                'bg-gray-100 text-gray-800': !['PENDING','APPROVED','DENIED'].includes(r.request_status)
                            }"
                            x-text="r.request_status"
                        ></span>
                    </div>

                    <div class="mt-4 bg-gray-50 rounded-lg p-3 w-full">
                        <h3 class="text-gray-800 font-semibold text-sm mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-date-fill text-blue-600" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm5.402 9.746c.625 0 1.184-.484 1.184-1.18 0-.832-.527-1.23-1.16-1.23-.586 0-1.168.387-1.168 1.21 0 .817.543 1.2 1.144 1.2"/>
                                <path d="M16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-6.664-1.21c-1.11 0-1.656-.767-1.703-1.407h.683c.043.37.387.82 1.051.82.844 0 1.301-.848 1.305-2.164h-.027c-.153.414-.637.79-1.383.79-.852 0-1.676-.61-1.676-1.77 0-1.137.871-1.809 1.797-1.809 1.172 0 1.953.734 1.953 2.668 0 1.805-.742 2.871-2 2.871zm-2.89-5.435v5.332H5.77V8.079h-.012c-.29.156-.883.52-1.258.777V8.16a13 13 0 0 1 1.313-.805h.632z"/>
                            </svg>
                            Reservation Details
                        </h3>

                        <div class="space-y-1.5 text-sm text-gray-700">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-900 flex gap-1 items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill text-purple-500" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                                    </svg>
                                    Reservation ID:
                                </span>
                                <span x-text="r.reservation_id"></span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-900 flex gap-1 items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill text-amber-600" viewBox="0 0 16 16">
                                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                        <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                                    </svg>
                                    Accommodation:
                                </span>
                                <span class="truncate" x-text="r.accommodation.name"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <x-button
                            variant="primary"
                            class="text-sm px-4 py-1.5"
                            @click="openModal(r)"
                        >Details</x-button>
                    </div>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="filteredReservations.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-journal-x" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6.146 6.146a.5.5 0 0 1 .708 0L8 7.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 8l1.147 1.146a.5.5 0 0 1-.708.708L8 8.707 6.854 9.854a.5.5 0 0 1-.708-.708L7.293 8 6.146 6.854a.5.5 0 0 1 0-.708"/>
                        <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                        <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                    </svg>
                    <p class="text-2xl mt-5 font-medium">No Request available</p>
                </div>
            </template>
        </section>

        {{-- Request Details Modal --}}
        <div
            x-show="showRequestModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition.opacity
            x-cloak
        >
            <div
                class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg space-y-4 relative mx-5"
                x-transition.scale
            >
                <x-button
                    variant="transparent"
                    class="absolute top-3 right-3"
                    @click="closeModal()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill text-blue-600" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
                    </svg>
                    Request Details
                </h2>

                <div class="grid grid-cols-1 gap-4">
                    {{-- Reservation ID --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Reservation ID</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-2-back-fill text-gray-500" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5H0zm11.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM0 11v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1z"/>
                                </svg>
                            </span>

                            <input
                                type="text" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                                x-model="selected.reservation_id"
                            >
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill text-gray-500" viewBox="0 0 16 16">
                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                                </svg>
                            </span>

                            <input
                                type="text" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                                x-bind:value="selected?.user?.first_name + ' ' + selected?.user?.last_name"
                            >
                        </div>
                    </div>

                    {{-- Accommodation --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Accommodation</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill text-gray-500" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                                </svg>
                            </span>

                            <input
                                type="text" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                                x-bind:value="selected?.accommodation?.name"
                            >
                        </div>
                    </div>

                    {{-- Current Date --}}
                    <template x-show="request_status === 'PENDING'">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Current Date</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-fill text-gray-500" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </span>

                            <input
                                type="text" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                                x-model="selected.check_in_out_date_time"
                            >
                        </div>
                    </template>

                    {{-- New Date --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">New Date</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-fill text-gray-500" viewBox="0 0 16 16">
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </span>

                            <input
                                type="text" readonly
                                class="w-full bg-gray-100 border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                                x-model="selected.new_date_time"
                            >
                        </div>
                    </div>

                    {{-- Reason --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Reason</label>
                        <textarea
                            readonly
                            rows="2"
                            class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 resize-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 outline-none"
                            x-text="selected?.reason"
                        ></textarea>
                    </div>
                </div>

                <template x-if="selected.request_status === 'PENDING' || selected.request_status === 'DENIED'">
                    <div
                        class="flex justify-end space-x-2 pt-4"
                    >
                        <x-button
                            @click="confirmAction()"
                            variant="danger"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </x-button>
                    </div>
                </template>
            </div>
        </div>

        {{-- Deletetion Confirm Modal --}}
        <div
            x-show="showConfirmModal"
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
            x-transition.opacity
            x-cloak
        >
            <div class="bg-white p-6 rounded-lg max-w-sm w-full space-y-5 shadow-lg mx-5">
                <div class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill text-red-600" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>

                    <h3 class="text-lg font-semibold text-gray-900">Confirmation Action</h3>
                </div>

                <p class="text-gray-700 text-sm">
                    Are you sure you want to delete this reschedule request? This action cannot be undone.
                </p>

                <form
                    class="flex justify-end space-x-3 pt-2"
                    :action="`/user/reschedule-request/delete/${selected.id}`"
                    method="POST"
                >
                    @csrf
                    @method('DELETE')

                    <x-button
                        type="button"
                        variant="secondary"
                        @click="showConfirmModal = false; showRequestModal = true"
                    >Back</x-button>

                    <x-button
                        type="submit"
                        variant="danger"
                    >Delete</x-button>
                </form>
            </div>
        </div>

        {{-- Available Reservation for Reschedule Request Modal --}}
        <div
            x-show="showAvailableReservationsForReschedule"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
            x-cloak
        >
            <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-6 relative">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="bi bi-calendar-check text-blue-600"></i>
                        Available Reservations
                    </h2>

                    <x-button
                        variant="transparent"
                        class="absolute top-3 right-3"
                        @click="showAvailableReservationsForReschedule = false"
                    ><i class="bi bi-x-lg text-xl"></i></x-button>
                </div>

                {{-- Reservation List --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
                    @forelse ($available_reservation_for_reschedule as $reservation)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm hover:shadow-md transition duration-200">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="flex items-center gap-1 text-gray-800 font-semibold">
                                    <i class="bi bi-credit-card-fill text-purple-600"></i>
                                    <span>Reservation ID:</span>
                                </div>

                                <span class="truncate">{{ $reservation->id }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="flex items-center gap-1 text-gray-700">
                                    <i class="bi bi-house-fill text-amber-600"></i>
                                    <span class="font-medium">Accommodation:</span>
                                </div>

                                <span class="truncate">{{ $reservation->accommodation->name }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm mb-2">
                                <div class="flex items-center gap-1 text-gray-700">
                                    <i class="bi bi-arrow-repeat text-blue-500"></i>
                                    <span class="font-medium">Current Date:</span>
                                </div>

                                <span class="truncate">
                                    {{ \Carbon\Carbon::parse($reservation->check_in_date_time)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservation->check_out_date_time)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="mt-4 text-right">
                                <x-button
                                    variant="primary"
                                    class="text-sm px-4 py-1.5"
                                    @click="
                                        selectedReservation = {
                                            id: '{{ $reservation->id }}',
                                            accommodation_id: {{ $reservation->accommodation_id }},
                                            accommodation: {
                                                name: '{{ $reservation->accommodation->name }}',
                                                check_in_time: '{{ \Carbon\Carbon::parse($reservation->accommodation->check_in_time) }}',
                                                check_out_time: '{{ \Carbon\Carbon::parse($reservation->accommodation->check_out_time) }}',
                                            },
                                            no_of_days_stay: {{ $reservation->no_of_days_stay }},
                                        };

                                        showRescheduleRequestModal = true;
                                        showAvailableReservationsForReschedule = false;
                                    "
                                >Reschedule</x-button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-10">
                            <i class="bi bi-calendar-x text-4xl"></i>
                            <p class="mt-2 font-medium">No available reservations for reschedule</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Reschedule request modal --}}
        <div
            x-show="showRescheduleRequestModal"
            x-cloak
            x-transition.opacity
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 overflow-hidden"
        >
            <div
                class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-6"
            >
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Request Reschedule</h2>

                <form
                    :action="`/user/reschedule-request/store`"
                    method="POST"
                    class="space-y-4"
                    x-data="{
                        reason: '',
                        maxChars: 255,
                    }"
                    @submit.prevent="
                        if (confirm('Are you sure you want to request reschedule?')) {
                            $el.submit();
                        }
                    "
                >
                    @csrf

                    <div class="grid grid-cols-2 gap-2">
                        <x-text-field
                            label="Reservation ID"
                            name="reservation_id"
                            type="text"
                            id="reservation_id"
                            value="selectedReservation ? selectedReservation.id : ''"
                            x-bind:value="selectedReservation ? selectedReservation.id : ''"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                                    <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                                    <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
                                </svg>
                            '
                            readonly
                        />

                        <x-text-field
                            label="Accommodation"
                            name="accommodation"
                            type="text"
                            id="accommodation"
                            value="selectedReservation?.accommodation?.name ?? 'N/A'"
                            x-bind:value="selectedReservation?.accommodation?.name ?? 'N/A'"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z"/>
                                    <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z"/>
                                </svg>
                            '
                            readonly
                        />
                    </div>

                    <x-text-field
                        label="New Date"
                        name="new_date"
                        type="text"
                        id="date"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        required
                        x-ref="newDate"
                    />

                    <x-text-field
                        label="Check-in"
                        name="check_in_date_time"
                        type="text"
                        id="check_in_date_time"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        readonly
                        x-ref="checkIn"
                    />

                    <input type="hidden" name="new_check_in_date_time" id="new_check_in_date_time" x-ref="newCheckInHidden">

                    <x-text-field
                        label="Check-out"
                        name="check_out_date_time"
                        type="text"
                        id="check_out_date_time"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5"/>
                            </svg>
                        '
                        readonly
                        x-ref="checkOut"
                    />

                    <input type="hidden" name="new_check_out_date_time" id="new_check_out_date_time" x-ref="newCheckOutHidden">

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">State your reason</label>
                        <textarea
                            id="reason"
                            name="reason"
                            rows="5"
                            x-model="reason"
                            maxlength="255"
                            placeholder="Please explain why you want to reschedule..."
                            required
                            class="w-full border border-gray-300 rounded-2xl px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none"
                        ></textarea>

                        <p class="text-sm text-gray-500 text-right">
                            <span x-text="reason.length"></span> / <span x-text="maxChars"></span> characters
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <x-button
                            type="button"
                            variant="secondary"
                            @click="
                                showRescheduleRequestModal = false;
                                showAvailableReservationsForReschedule = true

                                $refs.newDate.value = '';
                                $refs.checkIn.value = '';
                                $refs.checkOut.value = '';

                                $refs.newCheckInHidden.value = '';
                                $refs.newCheckOutHidden.value = '';
                            "
                        >Cancel</x-button>

                        <x-button
                            type="submit"
                            variant="primary"
                        >Submit</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-user-sidebar>
