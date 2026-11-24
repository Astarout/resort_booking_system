<x-admin-sidebar>

    @vite([
        'resources/js/admin/graph.js',
    ])

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="space-y-10">

        {{-- Monthly Sales and Total Bookings --}}
        <section>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl shadow p-6 text-center">
                    <h2 class="text-gray-500 text-sm font-semibold uppercase">This Month Sales</h2>
                    <p class="text-3xl font-bold text-green-600 mt-2">
                        ₱{{ number_format($monthly_sales, 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-2xl shadow p-6 text-center md:col-span-2">
                    <h2 class="text-gray-500 text-sm font-semibold uppercase">Total Bookings This Month</h2>
                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $reservationStatsPerMonth->sum('monthly_bookings') }}
                    </p>
                </div>
            </div>
        </section>

        {{-- Chart.js Bar Graph --}}
        @php $stats = $reservationStatsPerMonth; @endphp

        @if ($stats->isNotEmpty())
            <section>
                <div class="bg-white shadow rounded-2xl p-6">
                    <h2 class="text-lg font-bold mb-4 text-gray-700">Accommodations</h2>

                    <canvas
                        id="salesChart"
                        data-stats="{{ $stats->toJson() }}"
                        style="height: 50vh; max-height: 600px; min-height: 300px; width: 100%;"
                    ></canvas>
                </div>
            </section>
        @endif

        {{-- Table with Color Legend --}}
        <section>
            <div class="bg-white shadow rounded-2xl p-6 overflow-x-auto">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Detailed Booking Count</h2>
                <table class="w-full text-sm text-left border border-gray-200">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="p-3">Color</th>
                            <th class="p-3">Accommodation</th>
                            <th class="p-3">Monthly Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $colors = [
                                "rgba(200, 50, 80, 0.9)", "rgba(25, 105, 185, 0.9)", "rgba(220, 170, 40, 0.9)",
                                "rgba(110, 70, 220, 0.9)", "rgba(210, 100, 20, 0.9)", "rgba(40, 150, 150, 0.9)",
                                "rgba(200, 70, 140, 0.9)", "rgba(100, 150, 50, 0.9)", "rgba(220, 160, 20, 0.9)",
                                "rgba(70, 90, 100, 0.9)", "rgba(190, 30, 80, 0.9)", "rgba(0, 150, 180, 0.9)",
                                "rgba(170, 180, 40, 0.9)", "rgba(90, 60, 50, 0.9)", "rgba(50, 60, 150, 0.9)"
                            ];
                        @endphp
                        @forelse ($stats as $index => $stat)
                            @php
                                $color = $colors[$index % count($colors)];
                            @endphp
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3">
                                    <span class="inline-block w-4 h-4 rounded-full" style="background-color: {{ $color }};"></span>
                                </td>
                                <td class="p-3">{{ $stat->accommodation_name }}</td>
                                <td class="p-3 font-semibold" style="color: {{ $color }};">{{ $stat->monthly_bookings }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-3 text-center text-gray-500">
                                    No data available for this month.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</x-admin-sidebar>
