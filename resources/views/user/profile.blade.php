
{{-- User Profile --}}
<x-user-sidebar>

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Profile | User</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    {{-- Profile Information --}}
    <section
        class="border-gray-800 border-b pb-10 mb-6"
        x-data="{
            showDeleteModal: false,
            delete_password: '',
        }"
    >
        <form action="{{ route('user.profile.update') }}"
            enctype="multipart/form-data"
            method="POST"
            class="space-y-6"
            x-data="{ edit: {{ $errors->any() ? 'true' : 'false' }} }"
        >
            @csrf
            @method('PUT')

            <div class="flex flex-col sm:flex-row items-center sm:items-center sm:space-x-6 space-y-4 sm:space-y-0">
                <div class="relative" x-data="{ preview: null }">
                    <img :src="preview ? preview : '{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/icons/ProfileIcon.jpg') }}'"
                        alt="Profile"
                        class="rounded-full border-4 border-blue-500 shadow-md object-cover w-32 h-32"
                    >

                    <label for="avatar"
                        :class="!edit ? 'pointer-events-none opacity-80' : ''"
                        class="absolute bottom-0 right-2 bg-blue-600 p-2 rounded-full cursor-pointer shadow-md hover:bg-blue-700 transition text-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-camera-fill" viewBox="0 0 16 16">
                            <path d="M10.5 8.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                            <path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4zm.5 2a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m9 2.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0"/>
                        </svg>
                    </label>

                    <input
                        type="file"
                        id="avatar"
                        name="avatar"
                        accept="image/*"
                        class="hidden"
                        @change="
                            const file = $event.target.files[0];

                            if (!file) return;

                            if (file.size > 4 * 1024 * 1024) {
                                alert('Image must be 4MB or less');
                                $event.target.value = '';
                                preview = null;
                                return;
                            }

                            preview = URL.createObjectURL(file);
                        "
                    >

                </div>

                <div class="text-center sm:text-left">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </h2>

                    <p class="text-gray-500 text-sm sm:text-base">
                        ID: {{ Auth::id() }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                <x-text-field
                    label="First Name"
                    name="first_name"
                    type="text"
                    id="first_name"
                    value="{{ Auth::user()->first_name }}"
                    x-bind:readonly="!edit"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                    '
                />

                <x-text-field
                    label="Middle Name"
                    name="middle_name"
                    type="text"
                    id="middle_name"
                    value="{{ Auth::user()->middle_name }}"
                    x-bind:readonly="!edit"
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
                    value="{{ Auth::user()->last_name }}"
                    x-bind:readonly="!edit"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                        </svg>
                    '
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                <x-text-field
                    label="Complete Address"
                    name="address"
                    type="text"
                    id="address"
                    value="{{ Auth::user()->address }}"
                    x-bind:readonly="!edit"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg>
                    '
                />

                <x-text-field
                    label="Email"
                    name="email"
                    type="email"
                    id="email"
                    value="{{ Auth::user()->email }}"
                    :readonly="true"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                        </svg>
                    '
                />

                <x-text-field
                    label="Phone"
                    name="phone"
                    type="text"
                    id="phone"
                    value="{{ Auth::user()->phone }}"
                    :readonly="true"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                        </svg>
                    '
                />
            </div>

            <div class="flex flex-wrap gap-4 justify-end pt-6 border-t border-gray-200">
                <x-button variant="secondary" type="button"
                    class="flex items-center gap-1"
                    x-show="!edit"
                    @click="edit = true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                    </svg>
                    Edit
                </x-button>

                <x-button variant="primary" type="submit"
                    class="flex items-center gap-1"
                    x-show="edit"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill" viewBox="0 0 16 16">
                        <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5z"/>
                        <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5zM9 1h2v4H9z"/>
                    </svg>
                    Save
                </x-button>

                <x-button variant="secondary" type="button"
                    class="flex items-center gap-1"
                    x-show="edit"
                    @click="edit = false"
                    @click="window.location.reload()"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                    Cancel
                </x-button>

                <x-button variant="danger" type="button"
                    class="flex items-center gap-1"
                    x-show="!edit"
                    @click="showDeleteModal = true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                    </svg>
                    Delete
                </x-button>
            </div>
        </form>

        {{-- Delete Modal --}}
        <div
            x-show="showDeleteModal"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black/60 z-50"
            x-transition.opacity
        >
            <div
                x-transition.scale
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md relative mx-4"
            >
                <x-button
                    variant="transparent"
                    class="absolute top-3 right-3"
                    @click="showDeleteModal = false"
                ><i class="bi bi-x-lg"></i></x-button>

                <div class="flex flex-col items-center text-center space-y-4 mt-2">
                    <div class="bg-red-100 text-red-600 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-800">Confirm Account Deletion</h2>

                    <p class="text-gray-600 text-sm leading-relaxed">
                        Please confirm your password to permanently deactivate your account.
                        You will have <span class="font-semibold">30 days</span> to reactivate it by logging in again.
                    </p>
                </div>

                <x-password-field
                    label="Enter Password"
                    name="delete_password"
                    type="password"
                    id="delete_password"
                    placeholder=""
                    required
                    x-model="delete_password"
                    icon='
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                        </svg>
                    '
                />

                <div class="flex justify-center gap-3 mt-6">
                    <x-button variant="secondary" @click="showDeleteModal = false">
                        Cancel
                    </x-button>

                    <form
                        action="{{ route('user.profile.delete') }}" method="POST"
                        @submit.prevent="
                            if (delete_password.length >= 8 && delete_password.length <= 25) {
                                if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')) {
                                    $el.submit();
                                }
                            }
                        "
                    >
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="delete_password" :value="delete_password">

                        <x-button
                            variant="danger"
                            type="submit"
                            x-bind:disabled="delete_password.length < 8 || delete_password.length > 25"
                            x-bind:class="delete_password.length < 8 || delete_password.length > 25 ? 'opacity-50 cursor-not-allowed' : ''"
                        >Delete</x-button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section
        class="border-gray-800 border-b pb-6 flex flex-col items-center justify-center gap-3 mb-6"
        x-cloak
        x-data="{
            password_modal: {{
                $errors->has('current_password') ||
                $errors->has('password') ||
                session('password_error') ? 'true' : 'false'
            }}
        }"
    >
        <p class="text-gray-800 text-center">
            For your account security, use a strong password with at least 8 characters, including numbers and symbols.
        </p>

        <x-button variant="secondary" type="button"
            class="flex items-center gap-1"
            @click="password_modal = true"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
            </svg>
            Change Password
        </x-button>

        {{-- Change Password Modal --}}
        <div x-show="password_modal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
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
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md relative mx-8">
                <x-button variant="transparent"
                    class="absolute top-4 right-2 text-gray-500 hover:text-gray-800"
                    @click="password_modal = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-lg font-semibold mb-4">Change Password</h2>

                <form action="{{ route('user.profile.password.update') }}" method="POST" class="space-y-4"
                    @submit.prevent="
                        if (!valid) return;
                        if (!window.confirm('Update your password?')) return;
                        $el.querySelector('button[type=submit]').disabled = true;
                        $el.submit();
                    "
                >
                    @csrf
                    @method('PUT')

                    @if (session('password_error'))
                        <div class="p-3 rounded-xl bg-red-50 border border-red-300 text-red-600 text-sm text-center">
                            {{ session('password_error') }}
                        </div>
                    @endif

                    <x-password-field
                        label="Current Password"
                        name="current_password"
                        type="password"
                        id="current_password"
                        placeholder="Enter your current password"
                        required
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                            </svg>
                        '
                    />

                    <x-password-field
                        label="New Password"
                        name="password"
                        type="password"
                        id="password"
                        placeholder="Enter your new password"
                        required
                        x-model="password"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                            </svg>
                        '
                    />

                    <x-password-field
                        label="Confirm Password"
                        name="password_confirmation"
                        type="password"
                        id="password_confirmation"
                        placeholder="Re-enter your new password"
                        required
                        x-model="confirm"
                        icon='
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M8 0a4 4 0 0 1 4 4v2.05a2.5 2.5 0 0 1 2 2.45v5a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 2 13.5v-5a2.5 2.5 0 0 1 2-2.45V4a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v2h6V4a3 3 0 0 0-3-3"/>
                            </svg>
                        '
                    />

                    <ul class="text-sm space-y-1">
                        <li :class="rules.length ? 'text-green-600' : 'text-red-500'">• 8–25 characters</li>
                        <li :class="rules.upper ? 'text-green-600' : 'text-red-500'">• At least one uppercase letter</li>
                        <li :class="rules.lower ? 'text-green-600' : 'text-red-500'">• At least one lowercase letter</li>
                        <li :class="rules.number ? 'text-green-600' : 'text-red-500'">• At least one number</li>
                        <li :class="rules.special ? 'text-green-600' : 'text-red-500'">• At least one special character</li>
                        <li :class="rules.match ? 'text-green-600' : 'text-red-500'">• Passwords match</li>
                    </ul>

                    <div class="flex justify-end gap-2">
                        <x-button variant="secondary" type="button" @click="password_modal = false">
                            Cancel
                        </x-button>

                        <x-button variant="primary" type="submit">
                            Change
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="border-gray-800 border-b pb-6 flex flex-col items-center justify-center gap-3"
        x-data="{ rate_modal: false, rating: 0, comment: '', maxChars: 255 }"
    >

        <p class="text-gray-800 text-center">
            "We value your feedback! Please rate your experience and help us improve."
        </p>

        <x-button variant="tertiary" type="button"
            class="flex items-center gap-1"
            @click="rate_modal = true"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>
            Rate Us
        </x-button>

        <div x-show="rate_modal" x-transition x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md relative mx-6">
                <x-button variant="transparent"
                    @click="rate_modal = false"
                    class="absolute top-4 right-2 text-gray-500 hover:text-gray-800"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <h2 class="text-2xl font-semibold">Rate Us</h2>

                <form action="{{ route('user.profile.rate') }}" method="post" class="space-y-5"
                    onsubmit="if (!confirm('Submit your review?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                >
                    @csrf

                    <div class="flex justify-center gap-2">
                        <template x-for="star in 5" :key="star">
                            <svg @click="rating = star"
                                :class="rating >= star ? 'text-yellow-400' : 'text-gray-300'"
                                xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                                class="bi bi-star-fill cursor-pointer transition" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        </template>
                    </div>

                    <input type="hidden" name="rate" :value="rating" required>

                    <textarea
                        name="comment"
                        rows="7"
                        maxlength="255"
                        x-model="comment"
                        class="
                            border border-gray-300 rounded-lg
                            p-2 w-full
                            focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300
                            resize-none
                        "
                        placeholder="Leave your feedback (optional)..."
                    ></textarea>

                    <p class="text-sm text-gray-500 text-right">
                        <span x-text="comment.length"></span> / <span x-text="maxChars"></span> characters
                    </p>

                    <div class="flex justify-end gap-2">
                        <x-button type="button" variant="secondary" class="w-full" @click="rate_modal = false">
                            Cancel
                        </x-button>

                        <x-button type="submit" variant="tertiary" class="w-full">
                            Submit
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </section>

</x-user-sidebar>
