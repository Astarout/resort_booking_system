
{{-- Admin Accommodation Page --}}
<x-admin-sidebar>
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Accommodations</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div x-data="accommodationSearch()" x-init="init()">
        {{-- Controls --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            {{-- Search bar --}}
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

            {{-- Buttons --}}
            <div class="flex flex-wrap justify-center md:justify-evenly gap-3 md:gap-5">
                <x-button
                    type="button" variant="primary"
                    class="flex items-center gap-2 w-full sm:w-auto justify-center"
                    @click="entranceFeeModalOpen = true"
                >
                    <i class="bi bi-credit-card-2-front-fill"></i>
                    Entrance Fees
                </x-button>

                <x-button
                    type="button" variant="primary"
                    class="flex items-center gap-2 w-full sm:w-auto justify-center"
                    @click="petModalOpen = true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paw-fill" viewBox="0 0 16 16">
                        <path d="M11.25 8c-.547 0-1.048.135-1.5.375a3.5 3.5 0 0 0-3.5 0A2.995 2.995 0 0 0 4.75 8c-1.086 0-2.046.57-2.544 1.41C1.463 10.604 1 11.625 1 12.75 1 14.544 2.456 16 4.25 16c.834 0 1.587-.3 2.173-.787a3.996 3.996 0 0 1 3.154 0C10.163 15.7 10.916 16 11.75 16 13.544 16 15 14.544 15 12.75c0-1.125-.463-2.146-1.206-3.34C13.296 8.57 12.336 8 11.25 8zM5.5 6A1.5 1.5 0 1 0 5.5 3a1.5 1.5 0 0 0 0 3zm5 0A1.5 1.5 0 1 0 10.5 3a1.5 1.5 0 0 0 0 3zM2 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    Pets
                </x-button>

                <x-button
                    type="button" variant="primary"
                    class="flex items-center gap-2 w-full sm:w-auto justify-center"
                    @click="sheetModalOpen = true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-layers-fill" viewBox="0 0 16 16">
                        <path d="M8.309 1.387a1 1 0 0 1 .382 0l6 1.5a1 1 0 0 1 0 1.926l-6 1.5a1 1 0 0 1-.382 0l-6-1.5a1 1 0 0 1 0-1.926z"/>
                        <path d="M2.015 6.56 8 8.03l5.985-1.47L16 8l-8 2-8-2z"/>
                        <path d="M2.015 9.56 8 11.03l5.985-1.47L16 11l-8 2-8-2z"/>
                    </svg>
                    Bedsheets
                </x-button>

                <a href="{{ route('admin.accommodation.add') }}" class="w-full sm:w-auto">
                    <x-button class="flex items-center gap-2 w-full sm:w-auto justify-center" type="button" variant="primary">
                        <i class="bi bi-clipboard-plus-fill"></i>
                        New
                    </x-button>
                </a>
            </div>
        </div>

        {{-- Accommodation Grid --}}
        <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <template x-for="accommodation in filtered" :key="accommodation.id">
                <div class="relative bg-white rounded-xl shadow hover:shadow-lg overflow-hidden group">
                    <img :src="accommodation.image ? `{{ asset('storage') }}/${accommodation.image}` : `{{ asset('storage/icons/ProfileIcon.jpg') }}`" alt="image" class="w-full h-48 object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex justify-center items-center gap-3 transition">
                        <a :href="`/admin/accommodations/edit/${accommodation.id}`">
                            <x-button type="button" variant="primary">
                                <i class="bi bi-pen-fill"></i>
                            </x-button>
                        </a>

                        <form :action="`/admin/accommodations/delete/${accommodation.id}`" method="POST" @submit.prevent="if(confirm('Delete this accommodation?')) $el.submit();">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">
                                <i class="bi bi-trash3-fill"></i>
                            </x-button>
                        </form>
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

        {{-- Sheets Modal --}}
        <div
            x-show="sheetModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-hidden"
        >
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative mx-5">
                <x-button
                    @click="sheetModalOpen = false"
                    variant="transparent"
                     class="absolute top-3 right-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manage Bedsheets</h2>

                {{-- Add Sheet Form --}}
                <form action="{{ route('admin.sheets.store')  }}" method="POST" class="flex items-center gap-2 mb-6">
                    @csrf

                    <x-text-field
                        label=""
                        name="color"
                        type="color"
                        id="color"
                        placeholder=""
                        value="#000000"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-palette-fill" viewBox="0 0 16 16">
                                <path d="M12.433 10.07C14.133 10.585 16 11.15 16 8a8 8 0 1 0-8 8c1.996 0 1.826-1.504 1.649-3.08-.124-1.101-.252-2.237.351-2.92.465-.527 1.42-.237 2.433.07M8 5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m4.5 3a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3M5 6.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                            </svg>
                        '
                        required
                        class="h-20"
                    />

                    <x-button type="submit" variant="primary" class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                        Add
                    </x-button>
                </form>

                {{-- Sheets List --}}
                <div class="max-h-64 overflow-y-auto border rounded-lg">
                    @forelse ($available_sheets as $sheet)
                        <div class="flex justify-between items-center px-4 py-2 border-b last:border-b-0">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded border border-gray-300 shadow-sm" style="background-color: {{ $sheet->color }};"></div>
                                <span class="font-medium text-gray-700 capitalize">{{ $sheet->color }}</span>
                            </div>

                            <form action="{{ route('admin.sheets.destroy', ['id' => $sheet->id]) }}" method="POST" onsubmit="return confirm('Delete this sheet color?');">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="danger" class="flex items-center gap-1 text-sm py-1 px-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                    Delete
                                </x-button>
                            </form>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No sheets found</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Pets Modal --}}
        <div
            x-show="petModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-hidden"
        >
            <div
                class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative mx-5"
                x-data="{ editModalOpen: false, editPet: {} }"
            >
                <x-button
                    @click="petModalOpen = false"
                    variant="transparent"
                    class="absolute top-3 right-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Allowed Pets</h2>

                <form action="{{ route('admin.pets.store') }}" method="POST" class="flex flex-col gap-3 mb-6">
                    @csrf
                    <div class="flex items-start gap-2">
                        <x-text-field
                            label=""
                            name="name"
                            type="text"
                            id="name"
                            placeholder="Enter pet name"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paw-fill" viewBox="0 0 16 16">
                                    <path d="M11.25 8c-.547 0-1.048.135-1.5.375a3.5 3.5 0 0 0-3.5 0A2.995 2.995 0 0 0 4.75 8c-1.086 0-2.046.57-2.544 1.41C1.463 10.604 1 11.625 1 12.75 1 14.544 2.456 16 4.25 16c.834 0 1.587-.3 2.173-.787a3.996 3.996 0 0 1 3.154 0C10.163 15.7 10.916 16 11.75 16 13.544 16 15 14.544 15 12.75c0-1.125-.463-2.146-1.206-3.34C13.296 8.57 12.336 8 11.25 8zM5.5 6A1.5 1.5 0 1 0 5.5 3a1.5 1.5 0 0 0 0 3zm5 0A1.5 1.5 0 1 0 10.5 3a1.5 1.5 0 0 0 0 3zM2 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                </svg>
                            '
                            required
                        />

                        <x-text-field
                            label=""
                            name="capacity"
                            type="number"
                            id="capacity"
                            placeholder="Capacity"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5"/>
                                    <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635z"/>
                                </svg>
                            '
                            required
                            min="1"
                            max="10"
                        />
                    </div>

                    <x-button type="submit" variant="primary" class="flex items-center gap-1 self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                        Add
                    </x-button>
                </form>

                {{-- Allowed Pet List --}}
                <div class="max-h-64 overflow-y-auto border rounded-lg ">
                    @forelse ($allowed_pets as $pet)
                        <div class="flex justify-between items-center px-4 py-2 border-b last:border-b-0">
                            <div>
                                <p class="font-semibold text-gray-800 capitalize">{{ $pet->name }}</p>
                                <p class="text-sm text-gray-500">Capacity: {{ $pet->capacity }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Edit --}}
                                <x-button
                                    type="button"
                                    variant="secondary"
                                    class="flex items-center gap-1 text-sm py-1 px-2"
                                    @click="editPet = { id: '{{ $pet->id }}', name: '{{ $pet->name }}', capacity: '{{ $pet->capacity }}' }; editModalOpen = true;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                                    </svg>
                                    Edit
                                </x-button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.pets.destroy', ['id' => $pet->id]) }}" method="POST" onsubmit="return confirm('Delete this pet?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" class="flex items-center gap-1 text-sm py-1 px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                        Delete
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No pets allowed</p>
                    @endforelse
                </div>

                {{-- Edit Pet Modal --}}
                <div
                    x-show="editModalOpen"
                    x-cloak
                    class="fixed inset-0 z-60 flex items-center justify-center bg-black/50"
                >
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative mx-5">
                        <x-button
                            @click="editModalOpen = false"
                            variant="transparent"
                            class="absolute top-3 right-3"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                            </svg>
                        </x-button>

                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Pet</h2>

                        <form :action="`/admin/pets/update/${editPet.id}`" method="POST" class="flex flex-col gap-3">
                            @csrf
                            @method('PUT')

                            <x-text-field
                                label="Pet"
                                name="pet_name"
                                type="text"
                                id="name"
                                placeholder="Enter pet name"
                                icon='
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paw-fill" viewBox="0 0 16 16">
                                        <path d="M11.25 8c-.547 0-1.048.135-1.5.375a3.5 3.5 0 0 0-3.5 0A2.995 2.995 0 0 0 4.75 8c-1.086 0-2.046.57-2.544 1.41C1.463 10.604 1 11.625 1 12.75 1 14.544 2.456 16 4.25 16c.834 0 1.587-.3 2.173-.787a3.996 3.996 0 0 1 3.154 0C10.163 15.7 10.916 16 11.75 16 13.544 16 15 14.544 15 12.75c0-1.125-.463-2.146-1.206-3.34C13.296 8.57 12.336 8 11.25 8zM5.5 6A1.5 1.5 0 1 0 5.5 3a1.5 1.5 0 0 0 0 3zm5 0A1.5 1.5 0 1 0 10.5 3a1.5 1.5 0 0 0 0 3zM2 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm12 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                                    </svg>
                                '
                                required
                                readonly
                                x-model="editPet.name"
                            />

                            <x-text-field
                                label="Capacity"
                                name="capacity"
                                type="number"
                                id="capacity"
                                placeholder="Capacity"
                                icon='
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5"/>
                                        <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635z"/>
                                    </svg>
                                '
                                required
                                min="1"
                                max="10"
                                x-model="editPet.capacity"
                            />

                            <div class="flex justify-end gap-2 mt-3">
                                <x-button type="button" variant="secondary" @click="editModalOpen = false">Cancel</x-button>
                                <x-button type="submit" variant="primary">Update</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Entrance Fees Modal --}}
        <div
            x-show="entranceFeeModalOpen"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 overflow-hidden"
        >
            <div
                class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative mx-5"
                x-data="{ editModalOpen: false, editEntranceFee: {} }"
            >
                <x-button
                    @click="entranceFeeModalOpen = false"
                    variant="transparent"
                    class="absolute top-3 right-3"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Entrance Fees</h2>

                <form action="{{ route('admin.entrance_fees.store') }}" method="POST" class="flex flex-col gap-3 mb-6">
                    @csrf
                    <div class="flex items-start gap-2">
                        <x-select-field
                            label="Tour Type"
                            name="tour_type"
                            id="tour_type"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-day" viewBox="0 0 16 16">
                                    <path d="M6 7a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1z"/>
                                    <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2H2V1a1 1 0 0 1 2 0v1h8V1a1 1 0 0 1 2 0v1h.5A1.5 1.5 0 0 1 16 3.5V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zM1 5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                                </svg>
                            '
                            required
                        >
                            <option value="" selected class="text-gray-400 bg-white">Select</option>
                            <option value="day" {{ old('tour_type') == 'day' ? 'selected' : '' }}>Day</option>
                            <option value="night" {{ old('tour_type') == 'night' ? 'selected' : '' }}>Night</option>
                        </x-select-field>

                        <x-text-field
                            label="Amount"
                            name="amount"
                            type="number"
                            id="amount"
                            placeholder="amount"
                            icon='
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5"/>
                                    <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635z"/>
                                </svg>
                            '
                            required
                            min="100"
                            max="1000"
                        />
                    </div>

                    <x-button type="submit" variant="primary" class="flex items-center gap-1 self-end">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                        </svg>
                        Add
                    </x-button>
                </form>

                {{-- Allowed Pet List --}}
                <div class="max-h-64 overflow-y-auto border rounded-lg ">
                    @forelse ($entrance_fees as $entrance_fee)
                        <div class="flex justify-between items-center px-4 py-2 border-b last:border-b-0">
                            <div>
                                <p class="font-semibold text-gray-800 capitalize">{{ $entrance_fee->tour_type }}</p>
                                <p class="text-sm text-gray-500">Amount: ₱ {{ number_format($entrance_fee->amount, 2) }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Edit --}}
                                <x-button
                                    type="button"
                                    variant="secondary"
                                    class="flex items-center gap-1 text-sm py-1 px-2"
                                    @click="editEntranceFee = { id: '{{ $entrance_fee->id }}', tour_type: '{{ $entrance_fee->tour_type }}', amount: '{{ $entrance_fee->amount }}' }; editModalOpen = true;"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                                    </svg>
                                    Edit
                                </x-button>

                                {{-- Delete --}}
                                <form action="{{ route('admin.entrance_fees.destroy', ['id' => $entrance_fee->id]) }}" method="POST" onsubmit="return confirm('Delete this entrance fee?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="danger" class="flex items-center gap-1 text-sm py-1 px-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                        Delete
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">No entrance fees set</p>
                    @endforelse
                </div>

                {{-- Edit Entrance Fee Modal --}}
                <div
                    x-show="editModalOpen"
                    x-cloak
                    class="fixed inset-0 z-60 flex items-center justify-center bg-black/50"
                >
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative mx-5">
                        <x-button
                            @click="editModalOpen = false"
                            variant="transparent"
                            class="absolute top-3 right-3"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                            </svg>
                        </x-button>

                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Edit Entrance Fee</h2>

                        <form :action="`/admin/entrance_fees/update/${editEntranceFee.id}`" method="POST" class="flex flex-col gap-3">
                            @csrf
                            @method('PUT')

                            <x-text-field
                                label="Tour Type"
                                name="new_tour_type"
                                type="text"
                                id="tour_type"
                                placeholder=""
                                icon='
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-day" viewBox="0 0 16 16">
                                        <path d="M6 7a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1z"/>
                                        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2H2V1a1 1 0 0 1 2 0v1h8V1a1 1 0 0 1 2 0v1h.5A1.5 1.5 0 0 1 16 3.5V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zM1 5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                                    </svg>
                                '
                                required
                                readonly
                                x-model="editEntranceFee.tour_type"
                                class="capitalize"
                            />

                            <x-text-field
                                label="Amount"
                                name="amount"
                                type="number"
                                id="amount"
                                placeholder="amount"
                                icon='
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5"/>
                                        <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635z"/>
                                    </svg>
                                '
                                required
                                min="1"
                                max="1000"
                                x-model="editEntranceFee.amount"
                            />

                            <div class="flex justify-end gap-2 mt-3">
                                <x-button type="button" variant="secondary" @click="editModalOpen = false">Cancel</x-button>
                                <x-button type="submit" variant="primary">Update</x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function accommodationSearch() {
            return {
                query: '',
                accommodations: @json($accommodations),
                filtered: [],
                petModalOpen: false,
                sheetModalOpen: false,
                entranceFeeModalOpen: false,
                init() {
                    this.filtered = this.accommodations;

                    const petModalState = localStorage.getItem('petModalOpen');
                    const sheetModalState = localStorage.getItem('sheetModalOpen');
                    const entranceFeeModalState = localStorage.getItem('entranceFeeModalOpen');

                    if (petModalState === 'true') this.petModalOpen = true;
                    if (sheetModalState === 'true') this.sheetModalOpen = true;
                    if (entranceFeeModalState === 'true') this.entranceFeeModalOpen = true;

                    this.$watch('petModalOpen', value => {
                        localStorage.setItem('petModalOpen', value);
                        if (!value) localStorage.removeItem('petModalOpen');
                    });

                    this.$watch('sheetModalOpen', value => {
                        localStorage.setItem('sheetModalOpen', value);
                        if (!value) localStorage.removeItem('sheetModalOpen');
                    });

                    this.$watch('entranceFeeModalOpen', value => {
                        localStorage.setItem('entranceFeeModalOpen', value);
                        if (!value) localStorage.removeItem('entranceFeeModalOpen');
                    });
                },
                filter() {
                    const q = this.query.toLowerCase();
                    this.filtered = this.accommodations.filter(a => a.name.toLowerCase().includes(q));
                }
            }
        }
    </script>
</x-admin-sidebar>
