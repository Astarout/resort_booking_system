
{{-- Admin Rating Page --}}

<x-admin-sidebar>

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Reviews</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div
        x-data="{
            ratingFilter: localStorage.getItem('ratingFilter') || 'all',
            ratings: {{ $ratings->map(fn($r) => [
                'id' => $r->id,
                'rate' => $r->rate,
                'status' => $r->status,
                'name' => $r->user ? $r->user->first_name . ' ' . $r->user->last_name : 'Not Available',
                'avatar' => $r->user ? ($r->user->avatar ? asset('storage/' . $r->user->avatar) : asset('storage/icons/ProfileIcon.jpg')) : asset('storage/icons/ProfileIcon.jpg'),
                'review' => $r->comment
            ])->toJson() }},
            openModal: false,
            selectedReview: null
        }"
        x-init="
            $watch('ratingFilter', v => localStorage.setItem('ratingFilter', v));
        "
        x-cloak
    >

        {{-- ⭐ Filters --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            {{-- Rating Filter --}}
            <div class="flex flex-wrap gap-2">
                <button @click="ratingFilter = 'all'"
                    :class="ratingFilter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <template x-for="star in [5,4,3,2,1]" :key="star">
                    <button @click="ratingFilter = star"
                        :class="ratingFilter == star ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-800'"
                        class="px-4 py-2 rounded-lg font-medium transition"
                        x-text="star + ' ★'">
                    </button>
                </template>
            </div>
        </div>

        <section class="my-4">
            {{ $ratings->links() }}
        </section>

        {{-- Reviews Grid --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4 mt-6">

            {{-- Rating Cards --}}
            <template x-for="rating in ratings" :key="rating.id">
                <div
                    x-show="
                        (ratingFilter === 'all' || ratingFilter == rating.rate)
                    "
                    x-transition.opacity.scale
                    class="w-full h-48 bg-white shadow-md rounded-xl p-5 flex flex-col items-center justify-center
                           border border-gray-200 hover:shadow-xl transition duration-200 cursor-pointer active:scale-95"
                    @click="selectedReview = rating; openModal = true"
                >
                    {{-- Avatar --}}
                    <img class="w-16 h-16 rounded-full border border-gray-200 object-cover shadow-sm mb-3"
                        :src="rating.avatar"
                        alt="Profile"
                    >

                    {{-- Content --}}
                    <div class="text-center">
                        <p class="font-semibold text-gray-900 text-lg tracking-wide leading-tight" x-text="rating.name"></p>

                        {{-- Stars --}}
                        <div class="flex justify-center mt-2">
                            <template x-for="i in 5">
                                <svg :class="i <= rating.rate ? 'w-6 h-6 text-yellow-400 fill-current drop-shadow-sm' : 'w-6 h-6 text-gray-300 fill-current'" viewBox="0 0 24 24">
                                    <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                                </svg>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

        </section>

        {{--  No reviews yet --}}
        <div
            x-show="ratings.length === 0"
            class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full"
            x-transition
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="opacity-70" viewBox="0 0 16 16">
                <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm4 7.793 1.146-1.147a.5.5 0 1 1 .708.708L8.707 10l1.147 1.146a.5.5 0 0 1-.708.708L8 10.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 10 6.146 8.854a.5.5 0 1 1 .708-.708z"/>
            </svg>
            <p class="text-2xl mt-5 font-medium">No reviews yet</p>
        </div>

        {{-- No match filter --}}
        <div
            x-show="
                ratings.length > 0 && ratings.filter(r => (ratingFilter === 'all' || ratingFilter == r.rate)).length === 0
            "
            class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full"
            x-transition
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="opacity-70" viewBox="0 0 16 16">
                <path d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm4 7.793 1.146-1.147a.5.5 0 1 1 .708.708L8.707 10l1.147 1.146a.5.5 0 0 1-.708.708L8 10.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 10 6.146 8.854a.5.5 0 1 1 .708-.708z"/>
            </svg>
            <p class="text-lg mt-3 font-medium">No reviews match your filters</p>
        </div>

        {{-- Modal --}}
        <div
            x-show="openModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-transition
        >
            <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative mx-8">

                <x-button variant="transparent" @click="openModal = false"
                    class="absolute top-2 right-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <template x-if="selectedReview">
                    <div class="text-center">
                        <img :src="selectedReview.avatar"
                            alt="Profile"
                            class="w-20 h-20 rounded-full border border-gray-300 object-cover mx-auto mb-3 shadow-sm"
                        >

                        <h2 class="text-xl font-bold text-gray-800 mb-1" x-text="selectedReview.name"></h2>

                        <div class="flex justify-center mb-3">
                            <template x-for="i in 5">
                                <svg :class="i <= selectedReview.rate ? 'text-yellow-400' : 'text-gray-300'" class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.868 1.48 8.257L12 18.896l-7.416 4.537 1.48-8.257L0 9.308l8.332-1.151z"/>
                                </svg>
                            </template>
                        </div>

                        <p class="text-gray-700 text-sm leading-relaxed border-t pt-3" x-text="selectedReview.review"></p>
                    </div>
                </template>
            </div>
        </div>
    </div>

</x-admin-sidebar>
