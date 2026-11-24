
{{-- Admin Report Page --}}

<x-admin-sidebar>

    @vite([
        'resources/js/documents/salesReportExport.js',
        'resources/js/admin/reportFlatPickr.js',
    ])

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Reports</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <section class="bg-white rounded-xl shadow p-4 sm:p-6 border border-gray-200 space-y-8">
        {{-- Controls --}}
        <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Filter --}}
                <form
                    x-data="{ filter: '', range: '' }"
                    @submit.prevent="
                        localStorage.setItem('reportFilter', filter);
                        localStorage.setItem('reportRange', range);
                        $el.submit();
                    "
                    :action="filter ? (filter === 'range' && range ? `/admin/report/sales/${filter}/${range}` : `/admin/report/sales/${filter}`) : '#'"
                    method="GET"
                >
                    <div class="flex flex-row items-center justify-center gap-2">
                        <!-- Filter select -->
                        <select
                            name="sales_report_filter"
                            x-model="filter"
                            class="border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required
                        >
                            <option value="">Select</option>
                            <option value="weekly">This week</option>
                            <option value="monthly">This month</option>
                            <option value="yearly">This year</option>
                            <option value="range">Range</option>
                        </select>

                        <!-- Date input only for range -->
                        <div x-show="filter === 'range'" x-transition>
                            <input
                                type="text"
                                name="date_range"
                                id="date_range"
                                x-model="range"
                                x-bind:required="filter === 'range'"
                                placeholder="Select date"
                                class="border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <x-button type="submit" variant="primary">
                            Apply
                        </x-button>
                    </div>
                </form>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <x-button class="flex items-center gap-2" id="printChartBtn">
                    <i class="bi bi-printer-fill"></i>
                    Print
                </x-button>

                <x-button class="flex items-center gap-2" id="exportPdfBtn">
                    <i class="bi bi-file-earmark-arrow-down-fill"></i>
                    Export
                </x-button>
            </div>
        </section>

        {{-- Embed JSON data from backend --}}
        <script>
            const reportData = @json([
                'total_sales' => $sales,
                'accommodation_stats' => $accommodationStats
            ]);
        </script>

        {{-- Sales Summary Table --}}
        <div class="overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2 sales-summary-table">
                <i class="bi bi-graph-up"></i> Sales Summary
            </h2>

            <table class="min-w-full border-collapse text-left text-gray-700">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold">Period</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold">Date Range</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold">Total Sales</th>
                    </tr>
                </thead>
                <tbody id="sales-summary-body">
                    <!-- Rendered by JS -->
                </tbody>
            </table>
        </div>

        {{-- Accommodation Reservation Stats Table --}}
        <div class="overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2 accommodation-stats-table">
                <i class="bi bi-building"></i> Accommodation Stats
            </h2>

            <table class="min-w-full border-collapse text-left text-gray-700">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold">Accommodation</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold text-center">Total Reservations</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 font-semibold text-center">Total Sales</th>
                    </tr>
                </thead>

                <tbody id="accommodation-stats-body">
                    <!-- Rendered by JS -->
                </tbody>
            </table>
        </div>
    </section>

    {{-- Modal for Export/Print Confirmation --}}
    <div
        x-data="{ openConfirmation: false, action: null }"
        x-show="openConfirmation"
        x-on:open-confirmation-modal.window="openConfirmation = true; action = $event.detail.action"
        x-cloak
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div
            class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-95"
            x-show="openConfirmation"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
        >
            <h2 class="text-xl font-bold text-gray-800 mb-4">Confirm Action</h2>

            <p class="mb-6">Do you want to <span x-text="action"></span> the sales chart?</p>

            <div class="flex justify-end gap-4">
                <x-button variant="secondary" @click="openConfirmation = false">Cancel</x-button>

                <x-button
                    @click="
                        if(action === 'Export') $dispatch('generate-pdf', { type: 'download' });
                        if(action === 'Print') $dispatch('generate-pdf', { type: 'print' });
                        openConfirmation = false;
                    "
                >
                    Confirm
                </x-button>
            </div>
        </div>
    </div>

    {{-- JS to render tables from JSON and localStorage with readable dates --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const storedFilter = localStorage.getItem('reportFilter');
            const storedRange = localStorage.getItem('reportRange');

            const today = new Date();

            let periodLabel = 'N/A';
            let dateRangeLabel = 'N/A';

            function formatDateReadable(date) {
                const options = { month: 'short', day: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }

            function formatDateReadableWithYear(date) {
                const options = { month: 'short', day: 'numeric', year: 'numeric' };
                return date.toLocaleDateString('en-US', options);
            }

            if (storedFilter) {
                switch(storedFilter) {
                    case 'weekly':
                        periodLabel = 'This Week';
                        const sunday = new Date(today);
                        sunday.setDate(today.getDate() - today.getDay());
                        const saturday = new Date(sunday);
                        saturday.setDate(sunday.getDate() + 6);
                        dateRangeLabel = `${formatDateReadable(sunday)} – ${formatDateReadableWithYear(saturday)}`;
                        break;

                    case 'monthly':
                        periodLabel = 'This Month';
                        const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                        dateRangeLabel = `${formatDateReadable(firstDay)} – ${formatDateReadableWithYear(lastDay)}`;
                        break;

                    case 'yearly':
                        periodLabel = 'This Year';
                        const startYear = new Date(today.getFullYear(), 0, 1);
                        const endYear = new Date(today.getFullYear(), 11, 31);
                        dateRangeLabel = `${formatDateReadable(startYear)} – ${formatDateReadableWithYear(endYear)}`;
                        break;

                    case 'range':
                        periodLabel = 'Custom Range';

                        if(storedRange){
                            let [start, end] = storedRange.split(' - ');

                            console.log(start, end);

                            if (!end || end.trim() === '') {
                                end = start;
                            }

                            const startDate = new Date(start);
                            const endDate = new Date(end);

                            if(start === end) {
                                dateRangeLabel = `${formatDateReadableWithYear(endDate)}`;
                            } else {
                                dateRangeLabel = `${formatDateReadable(startDate)} – ${formatDateReadableWithYear(endDate)}`;
                            }
                        } else {
                            dateRangeLabel = 'N/A';
                        }
                        break;
                }
            }

            // Render Sales Summary Table
            const salesBody = document.getElementById('sales-summary-body');
            if (reportData.total_sales) {
                salesBody.innerHTML = `
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-2 px-3 sm:py-3 sm:px-4 capitalize">${periodLabel}</td>
                        <td class="py-2 px-3 sm:py-3 sm:px-4">${dateRangeLabel}</td>
                        <td class="py-2 px-3 sm:py-3 sm:px-4 font-medium text-green-600">
                            ₱ ${parseFloat(reportData.total_sales).toFixed(2)}
                        </td>
                    </tr>
                `;
            } else {
                salesBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">No data available</td>
                    </tr>
                `;
            }

            // Render Accommodation Stats Table
            const accBody = document.getElementById('accommodation-stats-body');
            if (reportData.accommodation_stats.length) {
                reportData.accommodation_stats.forEach(item => {
                    accBody.innerHTML += `
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="py-2 px-3 sm:py-3 sm:px-4">${item.name}</td>
                            <td class="py-2 px-3 sm:py-3 sm:px-4 text-center">${item.bookings}</td>
                            <td class="py-2 px-3 sm:py-3 sm:px-4 text-center text-green-600 font-medium">
                                ₱ ${parseFloat(item.sales).toFixed(2)}
                            </td>
                        </tr>
                    `;
                });
            } else {
                accBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">No data available</td>
                    </tr>
                `;
            }
        });
    </script>

    <script>
        window.reportData = @json([
            'total_sales' => $sales,
            'accommodation_stats' => $accommodationStats
        ]);
    </script>

</x-admin-sidebar>
