
{{-- PasswordField Component --}}
@vite([
    'resources/js/app.js',
    'resources/css/password.css',
])

<div class="w-full space-y-1" x-data="{ show: false }">

    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <div class="relative flex items-center">

        {{-- Left Icon --}}
        @if ($icon)
            <span class="absolute left-3 text-gray-400 flex items-center">
                {!! $icon !!}
            </span>
        @endif

        {{-- Password Input --}}
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            :type="show ? 'text' : 'password'"
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => "
                    w-full
                    rounded-2xl
                    border border-gray-500 shadow-sm
                    py-2
                    " . ($icon ? 'pl-10 ' : 'px-4 ') . "
                    pr-10
                    text-gray-900
                    focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                    transition
                "
            ]) }}
        >

        {{-- Eye Toggle --}}
        <button
            type="button"
            @click="show = !show"
            class="absolute right-4 flex items-center justify-center text-gray-400 hover:text-gray-700 focus:outline-none"
        >
            <template x-if="!show">
                <!-- Closed Eye -->
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16">
                    <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/>
                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/>
                </svg>
            </template>
            <template x-if="show">
                <!-- Open Eye -->
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>
            </template>
        </button>

    </div>

    @error($name)
        <p class="text-sm text-red-600 mt-1 text-center">{{ $message }}</p>
    @enderror

</div>
