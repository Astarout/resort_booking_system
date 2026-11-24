
{{-- Admin Register Page --}}

<!DOCTYPE html>

<html lang="en" class="not-smooth" style="scroll-behavior: auto;">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Register</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/icons/Logo.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-50 py-10 px-6"
    x-data="{
        password: '',
        confirm: '',
        get rules() {
            return {
                length: this.password.length >= 8 && this.password.length <= 25,
                upper: /[A-Z]/.test(this.password),
                lower: /[a-z]/.test(this.password),
                number: /[0-9]/.test(this.password),
                special: /[^A-Za-z0-9]/.test(this.password),
                match: this.password && this.password === this.confirm,
            }
        },
        get valid() {
            return Object.values(this.rules).every(Boolean);
        },
    }"
>

    <form
        action="{{ route('admin.register') }}"
        method="POST"
        class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-2xl space-y-6 h-full"
        @submit.prevent="
            if (!valid) return;
            if (!window.confirm('Confirm Registration?')) return;
            $el.querySelector('button[type=submit]').disabled = true;
            $el.submit();
        "
    >
        @csrf

        {{-- Header --}}
        <header class="text-center space-y-2">
            <div class="flex justify-center">
                <img src="{{ asset('storage/icons/Logo.jpg') }}" alt="Logo" class="w-16 h-16 rounded-full shadow-md">
            </div>

            <h1 class="text-2xl font-bold text-gray-800">Create Admin Account</h1>

            <p class="text-sm text-gray-500">Fill out the details to get started</p>
        </header>

        {{-- Fields --}}
        <section class="space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-text-field
                    label="First Name"
                    name="first_name"
                    type="text"
                    id="first_name"
                    value="{{ old('first_name') }}"
                    placeholder="First name"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                    '
                    required
                />

                <x-text-field
                    label="Middle Name"
                    name="middle_name"
                    type="text"
                    id="middle_name"
                    value="{{ old('middle_name') }}"
                    placeholder="Middle name"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                    '
                />

                <x-text-field
                    label="Last Name"
                    name="last_name"
                    type="text"
                    id="last_name"
                    value="{{ old('last_name') }}"
                    placeholder="Last name"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                    '
                    required
                />
            </div>

            <x-text-field
                label="Email"
                name="email"
                type="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="Enter your email"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                '
                required
            />

            <x-text-field
                label="Phone"
                name="phone"
                type="text"
                id="phone"
                value="{{ old('phone') }}"
                placeholder="Enter your phone number"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                    </svg>
                '
                required
            />

            <x-text-field
                label="Address"
                name="address"
                type="text"
                id="address"
                value="{{ old('address') }}"
                placeholder="Enter your address"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                    </svg>
                '
                required
            />

            <x-password-field
                label="Password"
                name="password"
                type="password"
                id="password"
                placeholder="Create a password"
                x-model="password"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                    </svg>
                '
                required
            />

            <x-password-field
                label="Confirm Password"
                name="password_confirmation"
                type="password"
                id="password_confirmation"
                placeholder="Confirm your password"
                x-model="confirm"
                icon='
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                    </svg>
                '
                required
            />

            <ul class="text-sm space-y-1">
                <li :class="rules.length ? 'text-green-600' : 'text-red-500'">• 8–25 characters</li>
                <li :class="rules.upper ? 'text-green-600' : 'text-red-500'">• At least one uppercase letter</li>
                <li :class="rules.lower ? 'text-green-600' : 'text-red-500'">• At least one lowercase letter</li>
                <li :class="rules.number ? 'text-green-600' : 'text-red-500'">• At least one number</li>
                <li :class="rules.special ? 'text-green-600' : 'text-red-500'">• At least one special character</li>
                <li :class="rules.match ? 'text-green-600' : 'text-red-500'">• Passwords match</li>
            </ul>

        </section>

        {{-- Submit + Cancel --}}
        <section class="flex justify-end flex-col sm:flex-row gap-4 mt-6">
            <a href="{{ route('admin.accounts') }}">
                <x-button type="button" variant="secondary" class="w-full sm:w-auto py-3 text-lg font-semibold">
                    Cancel
                </x-button>
            </a>

            <x-button type="submit" variant="primary" class="w-full sm:w-auto py-3 text-lg font-semibold">
                Create
            </x-button>
        </section>
    </form>

</body>

</html>
