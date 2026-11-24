
{{-- Gallery Modal Component --}}

<div
    x-show="{{ $show }}"
    x-transition.opacity
    x-transition
    x-cloak
    x-effect="document.body.style.overflow = {{ $show }} ? 'hidden' : ''"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4"
>
    <div
        class="bg-white rounded-lg shadow-lg max-w-2xl w-full relative overflow-hidden"
        @click.away="{{ $closeAction }}"
    >
        <!-- Close Button -->
        <button
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 text-2xl"
            @click="{{ $closeAction }}"
        >
            &times;
        </button>

        <!-- Image -->
        <img
            :src="{{ $image }}"
            alt="Gallery Image"
            class="w-full max-h-[70vh] object-contain bg-black"
        >

        <!-- Description -->
        <div class="p-4 text-center">
            <p class="text-gray-700" x-text="{{ $description }}"></p>
        </div>
    </div>
</div>
