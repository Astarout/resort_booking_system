
{{-- Admin Calendar Page --}}

<x-admin-sidebar>

    @vite([
        'resources/js/admin/calendar.js',
    ])

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Calendar</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Legend --}}
    @if(!empty($accommodationColors))
        <div class="my-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3 text-center sm:text-left">
                Legend
            </h2>
            <div
                class="
                    flex flex-wrap sm:grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3
                    bg-white p-4 rounded-xl shadow-md border max-h-48 overflow-y-auto
                    sm:overflow-y-visible sm:max-h-none
                "
            >
                @foreach($accommodationColors as $name => $color)
                    <div class="flex items-center justify-center sm:justify-start gap-2 w-full sm:w-auto">
                        <span class="w-5 h-5 rounded-md flex-shrink-0 border border-gray-300" style="background-color: {{ $color }}"></span>
                        <span class="text-sm font-medium text-gray-800 text-center capitalize">{{ $name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Calendar --}}
    <section>
        <div id="calendar"
            class="bg-white p-4 rounded-xl shadow-lg border font-sans text-sm h-[500px] sm:h-[600px] md:h-[650px] w-full max-w-full">
        </div>
    </section>

    {{-- Modal for event details --}}
    <div
        x-data="{ open: false, event: {} }"
        x-on:open-modal.window="
            open = true;
            event = $event.detail
        "
        x-show="open"
        :class="{'overflow-hidden': open}"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-cloak
    >
        <div
            class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95"
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
        >
            {{-- Close button --}}
            <x-button variant="transparent"
                @click="open = false"
                class="absolute top-4 right-4"
            ><i class="bi bi-x-lg"></i></x-button>

            <div class="my-6 flex flex-col items-center">
                <img
                    :src="event?.extendedProps?.user_profile ? `/storage/${event.extendedProps.user_profile}` : '/storage/icons/ProfileIcon.jpg'"
                    alt="User Profile"
                    class="w-28 h-28 rounded-full object-cover border-4 border-blue-500 shadow-lg"
                >

                <h2 class="text-2xl font-bold mt-4 text-gray-800" x-text="event.title"></h2>
            </div>

            <div class="space-y-1.5">
                <p class="p-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex flex-col">
                    <strong class="font-medium text-gray-600 mb-1">Reservation ID:</strong>
                    <span class="text-gray-900 font-semibold ml-2" x-text="event.id"></span>
                </p>

                <p class="p-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex flex-col">
                    <strong class="font-medium text-gray-600 mb-1">Accommodation:</strong>
                    <span class="text-gray-900 font-semibold ml-2 capitalize" x-text="event?.extendedProps?.accommodation ?? 'N/A'"></span>
                </p>

                <p class="p-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex flex-col">
                    <strong class="font-medium text-gray-600 mb-1">No. of Guests:</strong>
                    <span class="text-gray-900 font-semibold ml-2" x-text="event?.extendedProps?.no_of_guest ?? 'N/A'"></span>
                </p>

                <p class="p-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex flex-col">
                    <strong class="font-medium text-gray-600 mb-1">Check-in:</strong>
                    <span class="text-gray-900 font-semibold" x-text="formatDate(event.start)"></span>
                </p>

                <p class="p-2 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 transition flex flex-col">
                    <strong class="font-medium text-gray-600 mb-1">Check-out:</strong>
                    <span class="text-gray-900 font-semibold" x-text="formatDate(event.end)"></span>
                </p>
            </div>
        </div>
    </div>

    <script>
        window.calendarEvents = @json($events);
    </script>

    <script>
        function formatDate(date) {
            if (!date) return "N/A";

                const d = new Date(date);

                const options = {
                    year: "numeric",
                    month: "long",
                    day: "numeric",
                    hour: "numeric",
                    minute: "2-digit",
                    hour12: true
            };

            return d.toLocaleString("en-US", options);
        }
    </script>
</x-admin-sidebar>
