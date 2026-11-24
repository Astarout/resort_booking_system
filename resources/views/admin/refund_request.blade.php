{{-- Refund Request Page --}}
<x-admin-sidebar>

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Refund Requests</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main
        x-data="{
            refund_request_status: localStorage.getItem('refund_request_status') || 'all',

            search: localStorage.getItem('refundRequestSearchQuery') || '',

            get filteredReservations() {
                return this.reservations.filter(r => {
                    let statusMatch = this.refund_request_status === 'all' || r.refund_request_status === this.refund_request_status;

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
            },

            reservations: {{ $refund_requests->map(function($r) {
                return [
                    'id' => $r->id,
                    'reservation_id' => $r->reservation->id,
                    'refund_request_status' => $r->status,
                    'refund_amount' => number_format($r->refund_amount, 2),
                    'mode' => $r->mode,
                    'gcash_number' => $r->gcash_number ?? 'N/A',
                    'accommodation' => ['name' => $r->reservation->accommodation->name],
                    'remarks' => $r->remarks ?? 'COMPLETED',
                    'screen_shot' => $r->screen_shot,
                    'user' => [
                        'first_name' => $r->reservation->user->first_name,
                        'last_name' => $r->reservation->user->last_name,
                        'avatar' => $r->reservation->user->avatar,
                        'id' => $r->reservation->user->id,
                    ]
                ];
            })->toJson() }},

            showAvailableReservationsForRefund: false,
            showRefundRequestModal: false,
            selectedReservation: null,
            showRequestModal: false,
            showUpdateModal: false,

            selected: {},
            selectedUpdate: {},

            openModal(reservation) {
                this.selected = reservation;
                this.showRequestModal = true;
            },

            closeModal() {
                this.showRequestModal = false;
                this.selected = {};
            },

            openUpdateModal(reservation) {
                this.selectedUpdate = {...reservation, screen_shot_preview: null};
                this.showUpdateModal = true;
            },

            closeUpdateModal() {
                this.showUpdateModal = false;
                this.selectedUpdate = {};
            },

            handleFileChange(event) {
                const file = event.target.files[0];
                if(file){
                    if (file.size > 4 * 1024 * 1024) {
                        alert('Screenshot must be 4MB or less');
                        event.target.value = '';
                        this.selectedUpdate.screen_shot_preview = null;
                        return;
                    }
                    this.selectedUpdate.screen_shot_preview = URL.createObjectURL(file);
                } else {
                    this.selectedUpdate.screen_shot_preview = null;
                }
            },
        }"
    >
        {{-- Controls --}}
        <section class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
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
                    @click="refund_request_status = 'all'"
                    :class="refund_request_status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="refund_request_status = 'PENDING'"
                    :class="refund_request_status === 'PENDING' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">PENDING</button>
                <button
                    @click="refund_request_status = 'COMPLETED'"
                    :class="refund_request_status === 'COMPLETED' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">COMPLETED</button>
            </div>
        </section>

        <section class="my-4">
            {{ $refund_requests->links() }}
        </section>

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
                                'bg-red-100 text-red-800': r.refund_request_status === 'PENDING',
                                'bg-green-100 text-green-800': r.refund_request_status === 'COMPLETED',
                                'bg-gray-100 text-gray-800': !['PENDING','COMPLETED'].includes(r.request_status)
                            }"
                            x-text="r.refund_request_status"
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
                                <span class="font-medium text-gray-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-credit-card-fill text-purple-600" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0zm0 3v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7zm3 2h1a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-1a1 1 0 0 1 1-1"/>
                                    </svg>
                                    Reservation ID:
                                </span>
                                <span x-text="r.reservation_id"></span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-gray-900 flex items-center gap-2">
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

                    <div class="mt-5 flex justify-end gap-2">
                        <x-button
                            variant="secondary"
                            class="text-sm px-4 py-1.5"
                            @click="openModal(r)"
                        >Details</x-button>

                        <x-button
                            variant="primary"
                            class="text-sm px-4 py-1.5"
                            @click="openUpdateModal(r)"
                            x-show="r.refund_request_status === 'PENDING'"
                        >Update</x-button>
                    </div>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="filteredReservations.length === 0">
                <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
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
                class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg space-y-4 relative mx-5 max-h-[90vh] overflow-auto scrollbar-thin"
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

                <h2 class="text-xl font-bold text-gray-900">Refund Request Details</h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Reservation ID</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-model="selected.reservation_id">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-bind:value="selected?.user?.first_name + ' ' + selected?.user?.last_name">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Accommodation</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-bind:value="selected?.accommodation?.name">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Refund Amount</label>
                        <input
                            type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700"
                            x-bind:value="'₱ ' + selected?.refund_amount"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Mode</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 capitalize" x-bind:value="selected?.mode">
                    </div>

                    <div x-show="selected?.mode === 'gcash'">
                        <label class="block text-xs font-medium text-gray-500 mb-1">GCash Number</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-bind:value="selected?.gcash_number">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Remarks</label>
                        <textarea readonly rows="2" class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 resize-none" x-text="selected?.remarks"></textarea>
                    </div>

                    <div x-show="selected?.screen_shot">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Proof</label>
                        <img :src="selected.screen_shot ? `/storage/${selected.screen_shot}` : ''" alt="GCash Screenshot" class="w-full h-auto rounded-lg border border-gray-300" x-show="selected.screen_shot">
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Modal --}}
        <div
            x-show="showUpdateModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition.opacity
            x-cloak
        >
            <form
                :action="`/admin/refund_request/update/${selectedUpdate.id}`"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white rounded-xl shadow-lg p-6 w-full max-w-lg space-y-4 relative mx-5 overflow-y-auto max-h-[90vh]"
                x-transition.scale
                @submit.prevent="
                    if(confirm('Are you sure you want to update this refund request?')) {
                        $el.submit();
                    }
                "
            >
                @csrf
                @method('PUT')

                <x-button variant="transparent" class="absolute top-3 right-3" @click.prevent="closeUpdateModal()">✕</x-button>

                <h2 class="text-xl font-bold text-gray-900">Update Refund Request</h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Reservation ID</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-model="selectedUpdate.reservation_id">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-bind:value="selectedUpdate?.user?.first_name + ' ' + selectedUpdate?.user?.last_name">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Accommodation</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700" x-bind:value="selectedUpdate?.accommodation?.name">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Refund Amount</label>
                        <input
                            type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700"
                            x-bind:value="'₱ ' + selectedUpdate?.refund_amount"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Mode</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 capitalize" x-model="selectedUpdate.mode">
                    </div>

                    <div x-show="selectedUpdate.mode === 'gcash'">
                        <label class="block text-xs font-medium text-gray-500 mb-1">GCash Number</label>
                        <input type="text" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 capitalize" x-model="selectedUpdate.gcash_number">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Upload photo</label>
                        <input type="file" name="screen_shot" accept="image/*" class="w-full text-sm text-gray-700" @change="handleFileChange" required>
                        <template x-if="selectedUpdate.screen_shot_preview">
                            <img :src="selectedUpdate.screen_shot_preview" class="w-full h-auto rounded-lg border border-gray-300 mt-2">
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Remarks</label>
                        <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 resize-none" x-model="selectedUpdate.remarks"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 mt-3">
                        <x-button variant="secondary" class="px-4 py-2" @click.prevent="closeUpdateModal()">Cancel</x-button>
                        <x-button type="submit" variant="primary">Mark as Complete</x-button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</x-admin-sidebar>
