
{{-- Admin Account Management --}}
<x-admin-sidebar>

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Account Management</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div
        x-data="{
            usertype: localStorage.getItem('usertype') || 'all',
            status: localStorage.getItem('status') || 'all',

            search: '',

            users: @js($users->items()),
            pagination: @js($users->toArray()),

            modalOpen: false,

            selectedUser: null,

            openModal(user) {
                this.selectedUser = user;
                this.modalOpen = true;
            }
        }"
        x-init="
            $watch('usertype', v => localStorage.setItem('usertype', v));
            $watch('status', v => localStorage.setItem('status', v));
        "
        x-cloak
    >
        {{-- Filter and Controls --}}
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                {{-- Search Bar --}}
                <div class="w-full sm:w-1/4">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Enter email, name, U-ID..."
                            x-model="search"
                            class="w-full pl-4 pr-8 py-2 border border-gray-300 rounded-lg shadow-sm text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                        >

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-8">
                    {{-- usertype --}}
                    <div class="flex gap-2 flex-wrap">
                        <button
                            @click="usertype = 'all'"
                            :class="usertype === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                            class="px-4 py-2 rounded-lg font-medium transition">All</button>
                        <button
                            @click="usertype = 'admin'"
                            :class="usertype === 'admin' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                            class="px-4 py-2 rounded-lg font-medium transition">Admin</button>
                        <button
                            @click="usertype = 'user'"
                            :class="usertype === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                             class="px-4 py-2 rounded-lg font-medium transition">User</button>
                    </div>

                    {{-- status --}}
                    <div class="flex gap-2 flex-wrap">
                        <button
                            @click="status = 'all'"
                            :class="status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                            class="px-4 py-2 rounded-lg font-medium transition">All</button>
                        <button
                            @click="status = 'verified'"
                            :class="status === 'verified' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-800'"
                            class="px-4 py-2 rounded-lg font-medium transition">Verified</button>
                        <button
                            @click="status = 'pending'"
                            :class="status === 'pending' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'"
                            class="px-4 py-2 rounded-lg font-medium transition">Pending</button>
                    </div>
                </div>

                {{-- Button --}}
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('admin.register') }}">
                        <x-button type="primary" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                                <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                            </svg>
                            Add
                        </x-button>
                    </a>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $users->links() }}
        </section>

        {{-- Accounts Grid --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 p-4 mt-6">
            @forelse ($users as $user)
                <div
                    x-show="
                        (usertype === 'all' || usertype === '{{ strtolower($user->usertype) }}') &&
                        (
                          status === 'all' ||
                          (status === 'verified' && {{ (int) $user->verified }} === 1) ||
                          (status === 'pending'  && {{ (int) $user->verified }} === 0)
                        ) &&
                        (
                            search === '' ||
                            '{{ strtolower($user->first_name) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($user->last_name) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower($user->email) }}'.includes(search.toLowerCase()) ||
                            '{{ strtolower((string) $user->id) }}'.includes(search.toLowerCase())
                        )
                    "
                    x-transition.opacity.scale
                    class="w-full h-56 bg-white shadow-md rounded-xl p-5 flex flex-col items-center justify-between border border-gray-200 hover:shadow-xl transition duration-200"
                >
                    <img class="w-16 h-16 rounded-full border border-gray-200 object-cover shadow-sm mb-2"
                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/icons/ProfileIcon.jpg') }}"
                        alt="Profile">

                    <div class="text-center">
                        <p class="font-semibold text-gray-900 text-lg tracking-wide leading-tight">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </p>

                        @if ($user->last_active_at)
                            @php
                                $lastActive = \Carbon\Carbon::parse($user->last_active_at);
                                $diffInMinutes = $lastActive->diffInMinutes(now());
                            @endphp

                            <p class="text-xs text-gray-500 mt-1">
                                @if ($diffInMinutes < 5)
                                    <span class="text-green-600 font-medium">Active now</span>
                                @else
                                    Last active {{ $user->last_active_at->diffForHumans() }}
                                @endif
                            </p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">No activity yet</p>
                        @endif
                    </div>

                    <div class="flex justify-center items-center gap-3">
                        <span class="mt-2 inline-block text-xs px-2 py-1 rounded-full font-semibold"
                            :class="{{ $user->verified }} == 1 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'">
                            {{ $user->verified ? 'Verified' : 'Pending' }}
                        </span>
                        <span class="mt-2 inline-block text-xs px-2 py-1 rounded-full font-semibold bg-green-100 text-green-700">
                            {{ $user->usertype === 'admin' ? 'Admin' : 'User' }}
                        </span>
                    </div>

                    <x-button variant="primary" class="mt-4 text-xs"
                        @click="openModal({
                            id: '{{ $user->id }}',
                            first_name: '{{ $user->first_name }}',
                            middle_name: '{{ $user->middle_name }}',
                            last_name: '{{ $user->last_name }}',
                            email: '{{ $user->email }}',
                            phone: '{{ $user->phone }}',
                            address: '{{ $user->address }}',
                            usertype: '{{ $user->usertype }}',
                            verified: {{ $user->verified }},
                            avatar: '{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/icons/ProfileIcon.jpg') }}'
                        })">View</x-button>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                    <p class="text-2xl mt-5 font-medium">No Accounts available</p>
                </div>
            @endforelse
        </section>

        {{-- Filter Fail Message --}}
        <div
            class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full"
            x-show="
                users.filter(u =>
                    (usertype === 'all' || usertype.toLowerCase() === u.usertype.toLowerCase()) &&
                    (
                        status === 'all' ||
                        (status === 'verified' && u.verified === 1) ||
                        (status === 'pending' && u.verified === 0)
                    ) &&
                    (
                        search === '' ||
                        u.first_name.toLowerCase().includes(search.toLowerCase()) ||
                        u.last_name.toLowerCase().includes(search.toLowerCase()) ||
                        u.email.toLowerCase().includes(search.toLowerCase()) ||
                        u.id.toString().toLowerCase().includes(search.toLowerCase())
                    )
                ).length === 0
            "
            x-transition
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
            </svg>
            <p class="text-lg mt-3 font-medium">No accounts match your filters</p>
        </div>

        {{-- User Info Modal --}}
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div @click.away="modalOpen = false" class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative mx-5">
                <x-button variant="transparent"
                    @click="modalOpen = false"
                    class="absolute top-3 right-3 text-gray-600 hover:text-black"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                    </svg>
                </x-button>

                <div class="flex flex-col items-center">
                    <img :src="selectedUser?.avatar" class="w-20 h-20 rounded-full border shadow mb-3" alt="User Avatar">
                    <h2 class="text-xl font-bold text-gray-800"
                        x-text="selectedUser?.first_name + ' ' + (selectedUser?.middle_name || '') + ' ' + selectedUser?.last_name">
                    </h2>
                    <p class="text-gray-600 text-sm" x-text="selectedUser?.email"></p>
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="flex flex-col p-3 bg-gray-50 rounded-lg shadow-sm">
                        <span class="text-xs font-medium text-gray-500">ID</span>
                        <span class="text-gray-900 font-semibold" x-text="selectedUser?.id"></span>
                    </div>

                    <div class="flex flex-col p-3 bg-gray-50 rounded-lg shadow-sm">
                        <span class="text-xs font-medium text-gray-500">Phone</span>
                        <span class="text-gray-900 font-semibold" x-text="selectedUser?.phone || 'N/A'"></span>
                    </div>

                    <div class="flex flex-col p-3 bg-gray-50 rounded-lg shadow-sm col-span-2">
                        <span class="text-xs font-medium text-gray-500">Address</span>
                        <span class="text-gray-900 font-semibold" x-text="selectedUser?.address || 'N/A'"></span>
                    </div>

                    <div class="flex flex-col p-3 bg-gray-50 rounded-lg shadow-sm">
                        <span class="text-xs font-medium text-gray-500">User Type</span>
                        <span class="text-gray-900 font-semibold" x-text="selectedUser?.usertype == 'admin' ? 'Admin' : 'User'"></span>
                    </div>

                    <div class="flex flex-col p-3 bg-gray-50 rounded-lg shadow-sm">
                        <span class="text-xs font-medium text-gray-500">Status</span>
                        <span :class="selectedUser?.verified == 1 ? 'text-blue-600 font-semibold' : 'text-red-600 font-semibold'" x-text="selectedUser?.verified == 1 ? 'Verified' : 'Pending'"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <template x-if="selectedUser?.verified == 1 || selectedUser?.verified == 0">
                        <form :action="`/admin/accounts/delete/${selectedUser.id}`" method="POST"
                            onsubmit="if (!confirm('Delete this account?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                        >
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">Delete</x-button>
                        </form>
                    </template>

                    <template x-if="selectedUser?.verified == 1">
                        <form :action="`/admin/accounts/deactivate/${selectedUser.id}`" method="POST"
                            onsubmit="if (!confirm('Deactivate this account?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                        >
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" variant="tertiary">Deactivate</x-button>
                        </form>
                    </template>

                    <template x-if="selectedUser?.verified == 0">
                        <form :action="`/admin/accounts/verify/${selectedUser.id}`" method="POST"
                            onsubmit="if (!confirm('Verify this account?')) { return false; } this.querySelector('button[type=submit]').disabled = true;"
                        >
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" variant="primary">Verify</x-button>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-admin-sidebar>
