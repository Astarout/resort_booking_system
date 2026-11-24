
{{-- Selectfield Component --}}

<div class="w-full space-y-1">

    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">
        {{ $label }}
    </label>

    <div class="relative flex items-center">

        @if ($icon)
            <span class="absolute left-3 text-gray-400">
                {!! $icon !!}
            </span>
        @endif

        <select
            id="{{ $id }}"
            name="{{ $name }}"
            {{ $attributes }}
            class="
                w-full
                rounded-2xl
                border border-gray-500 shadow-sm
                px-4 py-2
                @if($icon) pl-10 @endif
                text-gray-900
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition
            "
        >
            {{ $slot }}
        </select>
    </div>

    @error($name)
        <p class="text-sm text-red-600 mt-1 text-center">{{ $message }}</p>
    @enderror

</div>
