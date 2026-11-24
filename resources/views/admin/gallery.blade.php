
{{-- Admin Gallery Page --}}
<x-admin-sidebar>

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Gallery</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- body --}}
    <div
        x-data="{
            galleries: @js($galleries->items()),
            paginate: @js($galleries->toArray()),
            imageType: localStorage.getItem('imageType') || 'all',

            addImageModal: false,
            editImageModal: false,

            currentImage: { id: null, type: '', description: '', preview: '' },

            get filteredGalleries() {
                return this.galleries.filter(g => this.imageType === 'all' || g.type === this.imageType);
            }
        }"
        x-init="
            $watch('imageType', v => localStorage.setItem('imageType', v));
        "
        x-cloak
    >
        {{-- Controls and Filters --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            {{-- Filters [image type] --}}
            <div class="flex gap-2 flex-wrap">
                <button
                    @click="imageType = 'all'"
                    :class="imageType === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="imageType = 'home'"
                    :class="imageType === 'home' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Home</button>
                <button
                    @click="imageType = 'about'"
                    :class="imageType === 'about' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">About</button>
                <button
                    @click="imageType = 'gallery'"
                    :class="imageType === 'gallery' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Gallery</button>
            </div>

            {{-- Button add [image] --}}
            <div class="flex gap-2 flex-wrap">
                <x-button variant="tertiary" class="flex items-center gap-2" @click="addImageModal = true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                        <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                    </svg>
                    upload
                </x-button>
            </div>
        </div>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $galleries->links() }}
        </section>

        {{-- Gallery Grid --}}
        <section class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" x-ref="galleryContainer">

            <template x-for="gallery in filteredGalleries" :key="gallery.id">
                <div class="relative bg-white rounded-xl shadow hover:shadow-lg overflow-hidden group" x-transition>
                    <img :src="'/storage/' + gallery.image" :alt="gallery.description" class="w-full h-48 object-cover">

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex justify-center items-center gap-3 transition">
                        <x-button variant="primary" type="button"
                            @click="
                                editImageModal = true;
                                currentImage = {
                                    id: gallery.id,
                                    type: gallery.type,
                                    description: gallery.description,
                                    preview: '/storage/' + gallery.image
                                }
                        "><i class="bi bi-pen-fill"></i></x-button>

                        <form
                            :action="`/admin/gallery/destroy/${gallery.id}`" method="POST"
                            onsubmit="if (!confirm('Delete this image?')) return false; this.querySelector('button[type=submit]').disabled = true;">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">
                                <i class="bi bi-trash3-fill"></i>
                            </x-button>
                        </form>
                    </div>

                    <div class="p-3">
                        <p class="text-sm text-gray-600 capitalize font-medium text-center" x-text="gallery.type"></p>
                    </div>
                </div>
            </template>

            <div x-show="filteredGalleries.length === 0" x-transition class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242 0a5 5 0 1 1 0-10 5 5 0 0 1 0 10z"/>
                </svg>
                <p class="text-2xl mt-5 font-medium">No images found for this filter</p>
            </div>
        </section>

        {{-- Add Image Modal --}}
        <div
            x-show="addImageModal"
            x-cloak
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            x-transition
        >
            @php
                $home_count = $galleries->where('type', 'home')->count();
            @endphp

            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6"
                x-data="{ preview: null }"
            >
                <h2 class="text-xl font-bold mb-4">Upload Image</h2>

                <form action="{{ route('admin.gallery.upload') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-4"
                    onsubmit="if (!confirm('Upload this image?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                >
                    @csrf

                    {{-- Image Upload with Preview --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Image</label>

                        <!-- Hidden Input -->
                        <input
                            type="file"
                            name="image"
                            class="hidden"
                            id="imageUpload"
                            accept="image/*"
                            required
                            @change="
                                const file = $event.target.files[0];

                                if (!file) {
                                    preview = null;
                                    return;
                                }

                                if (file.size > 4 * 1024 * 1024) { // 4MB limit
                                    alert('Image must be 4MB or less');
                                    $event.target.value = '';
                                    preview = null;
                                    return;
                                }

                                preview = URL.createObjectURL(file);
                            "
                        >

                        <!-- Preview -->
                        <template x-if="preview">
                            <div class="mb-3">
                                <img :src="preview" alt="Preview" class="w-full h-48 object-cover rounded-lg shadow-md">
                            </div>
                        </template>

                        <!-- Button-like Label -->
                        <label for="imageUpload"
                            class="flex items-center justify-center px-4 py-2 gap-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600 transition w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                            </svg>
                            Upload
                        </label>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
                            <option value="about">About</option>
                            <option value="gallery">Gallery</option>
                            @if ($home_count === 0)
                                <option value="home">Home</option>
                            @endif
                        </select>
                    </div>

                    {{-- Description --}}
                    <div x-data="{ description: '', maxChars: 100 }">
                        <label class="block font-medium text-gray-700">Description</label>
                        <textarea
                            name="description"
                            x-model="description"
                            :maxlength="maxChars"
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-2 resize-none"
                            rows="4"
                        ></textarea>

                        <p class="text-sm text-gray-500 text-right">
                            <span x-text="description.length"></span> / <span x-text="maxChars"></span> characters
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2">
                        <x-button type="button" variant="secondary" @click="addImageModal = false">Cancel</x-button>
                        <x-button type="submit" variant="primary">Save</x-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Image Modal --}}
        <div
            x-show="editImageModal"
            x-cloak
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            x-transition
        >
            @php
                $home_count = $galleries->where('type', 'home')->count();
            @endphp

            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h2 class="text-xl font-bold mb-4">Edit Image</h2>

                <form :action="`/admin/gallery/update/${currentImage.id}`" method="POST"
                    enctype="multipart/form-data" class="space-y-4"
                    onsubmit="if (!confirm('Save this changes?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                >
                    @csrf
                    @method('PUT')

                    {{-- Image Upload with Preview --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Image</label>

                        <!-- Hidden Input -->
                        <input
                            type="file"
                            name="image"
                            class="hidden"
                            id="editImageUpload"
                            accept="image/*"
                            @change="
                                const file = $event.target.files[0];

                                if (!file) {
                                    currentImage.preview = null;
                                    return;
                                }

                                if (file.size > 4 * 1024 * 1024) { // 4MB limit
                                    alert('Image must be 4MB or less');
                                    $event.target.value = '';
                                    currentImage.preview = null;
                                    return;
                                }

                                currentImage.preview = URL.createObjectURL(file);
                            "
                        >

                        <!-- Preview -->
                        <template x-if="currentImage.preview">
                            <div class="mb-3">
                                <img :src="currentImage.preview" alt="Preview" class="w-full h-48 object-cover rounded-lg shadow-md">
                            </div>
                        </template>

                        <!-- Button-like Label -->
                        <label for="editImageUpload"
                            class="flex items-center justify-center px-4 py-2 gap-2 bg-blue-500 text-white rounded-lg cursor-pointer hover:bg-blue-600 transition w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16">
                                <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                <path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708z"/>
                            </svg>
                            Upload
                        </label>
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="block font-medium text-gray-700">Type</label>
                        <select name="type" x-model="currentImage.type" class="mt-1 block w-full border border-gray-300 rounded-lg p-2">
                            <option value="about">About</option>
                            <option value="gallery">Gallery</option>
                            @if ($home_count === 0)
                                <option value="home" x-show="currentImage.type === 'home'">Home</option>
                            @endif
                        </select>
                    </div>

                    {{-- Description --}}
                    <div x-data="{ maxChars: 100 }">
                        <label class="block font-medium text-gray-700">Description</label>
                        <textarea
                            name="description"
                            :maxlength="maxChars"
                            x-model="currentImage.description"
                            class="mt-1 block w-full border border-gray-300 rounded-lg p-2 resize-none"
                            rows="4"
                        ></textarea>

                        <p class="text-sm text-gray-500 text-right">
                            <span x-text="currentImage.description?.length || 0"></span> / <span x-text="maxChars"></span> characters
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end gap-2">
                        <x-button type="button" variant="secondary" @click="editImageModal = false">Cancel</x-button>
                        <x-button type="submit" variant="primary">Save</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-sidebar>
