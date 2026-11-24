<!DOCTYPE html>

<html lang="en"
    x-data="{
        mainImage: '{{ $accommodation->image ? asset('storage/' . $accommodation->image) : null }}',
        gallery: [
            @foreach($gallery as $img)
                { url: '{{ asset('storage/' . $img->image) }}', file: null },
            @endforeach
        ]
    }"
    style="scroll-behavior: auto;"
>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} | Edit - {{ $accommodation->name }}</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 py-10 px-5">

    <form
        action="{{ route('admin.accommodation.update', ['id' => $accommodation->id]) }}" method="POST"
        class="w-full max-w-4xl bg-white p-10 rounded-2xl shadow-2xl space-y-8 not-smooth"
        enctype="multipart/form-data"
        @submit.prevent="
            if (!confirm('Save this accommodation?')) return;
            let dt = new DataTransfer();
            gallery.forEach(img => { if(img.file) dt.items.add(img.file) });
            $refs.galleryInput.files = dt.files;
            $el.querySelector('button[type=submit]').disabled = true;
            $el.submit();
        "
    >
        @csrf
        @method('PUT')

        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Edit Accommodation</h1>
            <p class="text-sm text-gray-500">Make changes to the accommodation details as needed.</p>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Details --}}
            <div class="space-y-6">
                <x-text-field
                    label="Name"
                    name="name"
                    type="text"
                    id="name"
                    value="{{ old('name', $accommodation->name) }}"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                        </svg>
                    '
                    required
                />

                <x-text-field
                    label="Pax"
                    name="pax"
                    type="number"
                    id="pax"
                    value="{{ old('pax', $accommodation->pax) }}"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 1.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-5 0A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5v1A1.5 1.5 0 0 1 9.5 4h-3A1.5 1.5 0 0 1 5 2.5zm-2 0h1v1A2.5 2.5 0 0 0 6.5 5h3A2.5 2.5 0 0 0 12 2.5v-1h1a2 2 0 0 1 2 2V14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V3.5a2 2 0 0 1 2-2"/>
                        </svg>
                    '
                    min="1"
                    required
                />

                <x-select-field
                    label="Tour Type (Optional)"
                    name="tour_type"
                    id="tour_type"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-day" viewBox="0 0 16 16">
                            <path d="M6 7a1 1 0 0 1 1-1h2a1 1 0 1 1 0 2H7a1 1 0 0 1-1-1z"/>
                            <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2H2V1a1 1 0 0 1 2 0v1h8V1a1 1 0 0 1 2 0v1h.5A1.5 1.5 0 0 1 16 3.5V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zM1 5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
                        </svg>
                    '
                >
                    <option selected value="" class="text-gray-400 bg-white" {{ old('tour_type', $accommodation->tour_type) == '' ? 'selected' : '' }}>Select</option>
                    <option value="day" {{ old('tour_type', $accommodation->tour_type) == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="night" {{ old('tour_type', $accommodation->tour_type) == 'night' ? 'selected' : '' }}>Night</option>
                </x-select-field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-text-field
                        label="Check-in"
                        name="check_in_time"
                        type="time"
                        id="check_in_time"
                        value="{{ old('check_in_time', $accommodation->check_in_time) }}"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                            </svg>
                        '
                        required
                    />

                    <x-text-field
                        label="Check-out"
                        name="check_out_time"
                        type="time"
                        id="check_out_time"
                        value="{{ old('check_out_time', $accommodation->check_out_time) }}"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
                            </svg>
                        '
                        required
                    />
                </div>

                <x-text-field
                    label="No. of accommodation available"
                    name="room_count"
                    type="number"
                    id="room_count"
                    value="{{ old('room_count', $accommodation->room_count) }}"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                        </svg>
                    '
                    required
                />

                <x-text-field
                    label="Price"
                    name="price"
                    type="number"
                    id="price"
                    value="{{ old('price', $accommodation->price) }}"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                            <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                        </svg>
                    '
                    required
                />

                <x-text-field
                    label="Discount (%)"
                    name="discount"
                    type="number"
                    id="discount"
                    value="{{ old('discount', $accommodation->discount) }}"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                            <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                            <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2z"/>
                        </svg>
                    '
                />

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="15" maxlength="255"
                        class="
                            w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm
                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                            resize-none
                        "
                    >{{ old('description', $accommodation->description) }}</textarea>
                </div>
            </div>

            {{-- Images --}}
            <div class="space-y-6">
                {{-- Main Image --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>

                    <label for="image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>

                            <p class="text-sm">Click to upload image</p>
                        </div>

                        <input
                            type="file"
                            name="image"
                            id="image"
                            accept="image/*"
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];

                                if (!file) {
                                    mainImage = null;
                                    return;
                                }

                                if (file.size > 4 * 1024 * 1024) {
                                    alert('Image must be 4MB or less');
                                    $event.target.value = '';
                                    mainImage = null;
                                    return;
                                }

                                mainImage = URL.createObjectURL(file);
                            "
                        >
                    </label>

                    <template x-if="mainImage">
                        <img :src="mainImage" alt="Preview" class="mt-3 w-full h-40 object-cover rounded-lg shadow">
                    </template>
                </div>

                {{-- Gallery --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery</label>
                    <label for="gallery" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>

                            <p class="text-sm">Click to upload image (Max: 12)</p>
                        </div>

                        <input
                            type="file"
                            name="gallery[]"
                            id="gallery"
                            multiple
                            accept="image/*"
                            class="hidden"
                            :disabled="gallery.length >= 12"
                            @change="
                                let files = Array.from($event.target.files);

                                // Max total images = 12
                                if (gallery.length + files.length > 12) {
                                    alert('You can only upload up to 12 images.');
                                    files = files.slice(0, 12 - gallery.length);
                                }

                                files.forEach(file => {
                                    // 4MB check
                                    if (file.size > 4 * 1024 * 1024) {
                                        alert(`'${file.name}' exceeds 4MB and was skipped.`);
                                        return;
                                    }

                                    gallery.push({
                                        file: file,
                                        url: URL.createObjectURL(file)
                                    });
                                });

                                // Reset input so same file can be reselected
                                $event.target.value = '';
                            "
                        >
                    </label>

                    <input type="file" name="gallery[]" x-ref="galleryInput" multiple hidden>

                    <div class="grid grid-cols-3 gap-3 mt-3">
                        <template x-for="(img, index) in gallery" :key="index">
                            <div class="relative">
                                <img :src="img.url" class="w-full h-24 object-cover rounded-lg shadow">

                                <button type="button" @click="gallery.splice(index, 1)"
                                    class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 text-xs hover:bg-red-700"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                </button>

                                <input type="hidden" name="old_gallery[]" :value="img.url" x-show="!img.file">
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </section>

        <div class="flex flex-row-reverse gap-5">

            <x-button type="submit" variant="primary" class="py-10">Save</x-button>

            <a href="{{ route('admin.accommodation') }}">
                <x-button type="button" variant="secondary" class="py-10">Back</x-button>
            </a>
        </div>

    </form>

</body>

</html>
