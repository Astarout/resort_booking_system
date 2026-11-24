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
        'resources/js/support/clock.js',
        'resources/js/documents/transactionExport.js',
    ])
</head>

<body class="flex-1 px-10 max-h-screen overflow-y-auto py-10 lg:pt-6">
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">My Payment Transactions</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <main
        x-data="{
            transactions: @js($transactions->items()),
            pagination: @js($transactions->toArray()),

            selectedTransaction: null,

            search: localStorage.getItem('transactionSearchQuery') || '',

            payment_method: localStorage.getItem('payment_method') || 'all',

            get filteredTransactions() {
                return this.transactions.filter(t => {
                    let methodMatch = this.payment_method === 'all' || t.payment_method === this.payment_method;
                    let searchText = this.search.toLowerCase();

                    let searchMatch = !this.search
                        || (t.transaction_id && t.transaction_id.toString().toLowerCase().includes(searchText))
                        || (t.reservation_id && t.reservation_id.toString().toLowerCase().includes(searchText))
                        || (t.reservation.accommodation?.name && t.reservation.accommodation.name.toLowerCase().includes(searchText))
                        || (t.reservation.user && (
                            t.reservation.user.first_name?.toLowerCase().includes(searchText) ||
                            t.reservation.user.last_name?.toLowerCase().includes(searchText) ||
                            (t.reservation.user.first_name + ' ' + t.reservation.user.last_name).toLowerCase().includes(searchText)
                        ));

                    return methodMatch && searchMatch;
                });
            }
        }"
        x-init="
            $watch('payment_method', v => localStorage.setItem('payment_method', v));
            $watch('search', v => localStorage.setItem('transactionSearchQuery', v));
        "
    >
        {{-- Controls --}}
        <section class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            {{-- Search --}}
            <div class="w-full sm:w-1/2">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search T-ID, R-ID, Name, accommodation..."
                        x-model="search"
                        class="w-full pl-4 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                    >

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>
            </div>

            {{-- Filter --}}
            <div class="flex gap-5 flex-wrap">
                <button
                    @click="payment_method = 'all'"
                    :class="payment_method === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="payment_method = 'gcash'"
                    :class="payment_method === 'gcash' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Gcash</button>
                <button
                    @click="payment_method = 'walk-in'"
                    :class="payment_method === 'walk-in' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Walk-in</button>
            </div>
        </section>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $transactions->links() }}
        </section>

        {{-- Transactions Cards --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-5 pb-10">
            <template x-for="transaction in filteredTransactions" :key="transaction.id">
                <div class="bg-white border rounded-lg shadow p-4 hover:shadow-lg transition cursor-pointer" @click="selectedTransaction = transaction">
                    <p class="text-sm text-gray-500">Transaction ID</p>
                    <p class="font-semibold text-gray-800 mb-2" x-text="transaction.transaction_id"></p>

                    <p class="text-sm text-gray-500">Customer</p>
                    <p class="font-semibold text-gray-800 mb-2" x-text="transaction.reservation?.user?.first_name + ' ' + transaction.reservation?.user?.last_name"></p>

                    <p class="text-sm text-gray-500">Accommodation</p>
                    <p class="font-semibold text-gray-800 mb-2" x-text="transaction.reservation.accommodation.name ?? 'N/A'"></p>

                    <div class="flex justify-between items-center mt-2">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                              :class="{
                                  'bg-green-100 text-green-700': transaction.payment_status === 'paid',
                                  'bg-yellow-100 text-yellow-700': transaction.payment_status === 'partial',
                                  'bg-red-100 text-red-700': transaction.payment_status === 'failed'
                              }"
                              x-text="transaction.payment_status">
                        </span>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                            x-text="transaction.payment_method"
                            :class="{
                                'bg-blue-100 text-blue-700': transaction.payment_method === 'gcash',
                                'bg-green-100 text-green-700': transaction.payment_method === 'walk-in',
                            }"
                        ></span>
                    </div>
                </div>
            </template>

            <template x-if="filteredTransactions.length === 0">
                <div class="col-span-full text-center text-gray-500 py-10">
                    No transactions found.
                </div>
            </template>
        </section>

        {{-- Transaction Details Modal --}}
        <div
            x-show="selectedTransaction"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 py-10 overflow-y-auto"
        >
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-6 relative" @click.away="selectedTransaction = null">
                {{-- Close --}}
                <x-button @click="selectedTransaction = null" class="absolute top-4 right-4" variant="transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-2xl font-bold text-gray-800 mb-4">Transaction Details</h2>

                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Transaction ID:</span>
                        <span class="font-semibold" x-text="selectedTransaction?.transaction_id"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Customer:</span>
                        <span class="font-semibold" x-text="selectedTransaction?.reservation?.user?.first_name + ' ' + selectedTransaction?.reservation?.user?.last_name"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Reservation ID:</span>
                        <span class="font-semibold" x-text="selectedTransaction?.reservation_id"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Accommodation:</span>
                        <span class="font-semibold" x-text="selectedTransaction?.reservation?.accommodation?.name ?? 'N/A'"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Payment Method:</span>
                        <span class="font-semibold capitalize" x-text="selectedTransaction?.payment_method"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Amount Paid:</span>
                        <span class="font-semibold text-green-500" x-text="new Intl.NumberFormat('en-PH', {style:'currency', currency:'PHP'}).format(selectedTransaction?.amount)"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Change:</span>
                        <span class="font-semibold" x-text="new Intl.NumberFormat('en-PH', {style:'currency', currency:'PHP'}).format(selectedTransaction?.change_amount)"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold capitalize"
                              :class="{
                                  'bg-green-100 text-green-700': selectedTransaction?.payment_status === 'paid',
                                  'bg-yellow-100 text-yellow-700': selectedTransaction?.payment_status === 'partial',
                                  'bg-red-100 text-red-700': selectedTransaction?.payment_status === 'failed'
                              }"
                              x-text="selectedTransaction?.payment_status"
                        ></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Recorded By:</span>
                        <span class="font-semibold" x-text="selectedTransaction?.user ? selectedTransaction.user.first_name + ' ' + selectedTransaction.user.last_name : 'N/A'"></span>
                    </div>

                    <div class="flex justify-between">
                        <span>Date:</span>
                        <span class="font-semibold" x-text="new Date(selectedTransaction?.created_at).toLocaleString()"></span>
                    </div>
                </div>

                <div
                    class="mt-6 flex justify-end gap-4"
                >
                    {{-- Print --}}
                    <x-button
                        class="px-4 py-2 rounded-lg flex items-center gap-3"
                        variant="primary"
                        @click="if(selectedTransaction) window.dispatchEvent(new CustomEvent('generate-transaction-pdf', { detail: { transaction: selectedTransaction, type: 'print' } }))"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                        </svg>
                        Print
                    </x-button>

                    {{-- Download --}}
                    <x-button
                        class="px-4 py-2 rounded-lg flex items-center gap-3"
                        variant="primary"
                        @click="if(selectedTransaction) window.dispatchEvent(new CustomEvent('generate-transaction-pdf', { detail: { transaction: selectedTransaction, type: 'download' } }))"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                        </svg>
                        Download
                    </x-button>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
