
{{-- Admin Notification Page --}}
<x-admin-sidebar>

    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div
        x-data="{
            notification: localStorage.getItem('notification') || 'all',
            notifications: @js($notifications->items()),
            pagination: @js($notifications->toArray()),
            confirmModal: false,
            actionType: '',
            actionUrl: '',
            viewModal: false,
            selectedNote: {},
            showConfirm: false,
            confirmAction: '',
        }"
        x-init="$watch('notification', v => localStorage.setItem('notification', v))"
        x-cloak
    >
        {{-- Controls --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Filters --}}
            <div class="flex space-x-2">
                <button
                    @click="notification = 'all'"
                    :class="notification === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">All</button>
                <button
                    @click="notification = 'read'"
                    :class="notification === 'read' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Read</button>
                <button
                    @click="notification = 'unread'"
                    :class="notification === 'unread' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-800'"
                    class="px-4 py-2 rounded-lg font-medium transition">Unread</button>
            </div>

            <div class="flex space-x-2">
                {{-- Read All --}}
                <form action="{{ route('admin.notifications.readAll') }}" method="post" x-ref="readForm" @submit.prevent="showConfirm = true; confirmAction = 'read'">
                    @csrf
                    @method('PUT')
                    <x-button variant="tertiary" type="submit">
                        Mark all as Read
                    </x-button>
                </form>

                <form action="{{ route('admin.notifications.deleteAll') }}" method="post" x-ref="clearForm" @submit.prevent="showConfirm = true; confirmAction = 'clear'">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" type="submit">
                        Clear
                    </x-button>
                </form>
            </div>
        </div>

        {{-- Pagination --}}
        <section class="my-4">
            {{ $notifications->links() }}
        </section>

        {{-- Notification Grid --}}
        <section class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 p-4">
            @forelse ($notifications as $note)
                <div
                    x-show="
                        notification === 'all' ||
                        (notification === 'read' && '{{ $note->read_at }}' !== '') ||
                        (notification === 'unread' && '{{ $note->read_at }}' === '')
                    "
                    x-transition.opacity.scale
                    class="w-full min-h-[120px] sm:min-h-[150px] bg-white shadow-md rounded-xl p-4 sm:p-5 flex flex-col justify-between border border-gray-200 hover:shadow-xl transition duration-200"
                >
                    {{-- Header --}}
                    <div class="flex justify-between items-start">
                        <h2 class="font-semibold text-gray-900 text-lg leading-tight mb-1">
                            {{ $note->data['title'] ?? 'Notification' }}
                        </h2>
                        <p class="text-[10px] sm:text-xs text-gray-500 ml-3 whitespace-nowrap truncate max-w-[90px] sm:max-w-none">
                            {{ $note->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>

                    {{-- Message --}}
                    <p class="text-sm text-gray-600 line-clamp-3 mt-1">
                        {{ $note->data['message'] ?? '' }}
                    </p>

                    {{-- Footer --}}
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs px-2 py-1 rounded-full font-semibold
                            {{ $note->read_at ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                            {{ $note->read_at ? 'Read' : 'Unread' }}
                        </span>

                        <div class="flex gap-2">
                            <x-button variant="secondary"
                                @click="selectedNote = {
                                    title: '{{ $note->data['title'] ?? 'Notification' }}',
                                    message: '{{ $note->data['message'] ?? '' }}',
                                    date: '{{ $note->created_at->format('M d, Y h:i A') }}',
                                    status: '{{ $note->read_at ? 'Read' : 'Unread' }}'
                                }; viewModal = true"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                </svg>
                            </x-button>

                            @if (!$note->read_at)
                                <x-button variant="primary"
                                    @click="actionType = 'read'; actionUrl = '{{ route('admin.notifications.read', $note->id) }}'; confirmModal = true"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                </x-button>
                            @endif

                            @if ($note->read_at)
                                <x-button variant="danger"
                                    @click="actionType = 'delete'; actionUrl = '{{ route('admin.notifications.delete', $note->id) }}'; confirmModal = true"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                                </x-button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-bell-slash-fill" viewBox="0 0 16 16">
                        <path d="M5.164 14H15c-1.5-1-2-5.902-2-7q0-.396-.06-.776zm6.288-10.617A5 5 0 0 0 8.995 2.1a1 1 0 1 0-1.99 0A5 5 0 0 0 3 7c0 .898-.335 4.342-1.278 6.113zM10 15a2 2 0 1 1-4 0zm-9.375.625a.53.53 0 0 0 .75.75l14.75-14.75a.53.53 0 0 0-.75-.75z"/>
                    </svg>
                    <p class="text-2xl mt-5 font-medium">No Notifications available</p>
                </div>
            @endforelse
        </section>

        {{-- Filter Fail Message --}}
        <div
            class="flex flex-col items-center justify-center text-gray-500 py-10 h-full col-span-full"
            x-show="
                notifications.length > 0 &&
                notifications.filter(n =>
                  notification === 'all' ||
                  (notification === 'read' && n.read_at) ||
                  (notification === 'unread' && !n.read_at)
                ).length === 0
            "
            x-transition
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-bell-slash-fill" viewBox="0 0 16 16">
                <path d="M5.164 14H15c-1.5-1-2-5.902-2-7q0-.396-.06-.776zm6.288-10.617A5 5 0 0 0 8.995 2.1a1 1 0 1 0-1.99 0A5 5 0 0 0 3 7c0 .898-.335 4.342-1.278 6.113zM10 15a2 2 0 1 1-4 0zm-9.375.625a.53.53 0 0 0 .75.75l14.75-14.75a.53.53 0 0 0-.75-.75z"/>
            </svg>
            <p class="text-lg mt-3 font-medium">No notifications match your filters</p>
        </div>

        {{-- Modal --}}
        <div
            x-show="confirmModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            x-transition.opacity
            x-cloak
        >
            <div
                class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all mx-5"
                x-transition.scale
            >
                {{-- Header --}}
                <div class="flex items-center gap-3 p-5 border-b">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full"
                        :class="actionType === 'delete' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'"
                    >
                        <svg x-show="actionType === 'delete'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>

                        <svg x-show="actionType === 'read'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">
                        <span x-text="actionType === 'read' ? 'Mark as Read' : 'Delete Notification'"></span>
                    </h2>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <p class="text-gray-600 text-sm leading-relaxed"
                       x-text="actionType === 'read'
                                ? 'Are you sure you want to mark this notification as read?'
                                : 'This action cannot be undone. Do you really want to delete this notification?'">
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 p-5 border-t bg-gray-50">
                    <x-button variant="secondary" @click="confirmModal = false">Cancel</x-button>

                    <form :action="actionUrl" method="POST">
                        @csrf
                        <template x-if="actionType === 'read'">
                            <input type="hidden" name="_method" value="PATCH">
                        </template>
                        <template x-if="actionType === 'delete'">
                            <input type="hidden" name="_method" value="DELETE">
                        </template>
                        <x-button type="submit" variant="tertiary" class="px-5"
                            x-text="actionType === 'read' ? 'Yes, Mark as Read' : 'Yes, Delete'"></x-button>
                    </form>
                </div>
            </div>
        </div>

        {{-- View Modal --}}
        <div
            x-show="viewModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
            x-transition.opacity
            x-cloak
        >
            <div
                class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transform transition-all mx-5"
                x-transition.scale
            >
                {{-- Header --}}
                <div class="flex justify-between items-center p-5 border-b">
                    <h2 class="text-xl font-bold text-gray-800" x-text="selectedNote.title"></h2>
                    <x-button @click="viewModal = false" variant="transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    </x-button>
                </div>

                {{-- Body --}}
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-4" x-text="selectedNote.message"></p>
                    <div class="flex justify-between items-center text-xs text-gray-500">
                        <span>Status: <span class="font-semibold" x-text="selectedNote.status"></span></span>
                        <span x-text="selectedNote.date"></span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 p-5 border-t bg-gray-50">
                    <x-button variant="secondary" @click="viewModal = false">Close</x-button>
                </div>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <div
            x-show="showConfirm"
            x-cloak
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 mx-5">

                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Confirm Action
                </h2>

                <p class="text-gray-600 mb-6" x-text="confirmAction === 'read'
                    ? 'Are you sure you want to mark all notifications as read?'
                    : 'Are you sure you want to clear all notifications?'"></p>

                <div class="flex justify-end space-x-3">
                    <x-button
                        @click="showConfirm = false"
                        variant="secondary"
                    >Cancel</x-button>

                    <x-button
                        @click="$refs[confirmAction + 'Form'].submit()"
                        x-text="confirmAction === 'read' ? 'Yes, Mark as Read' : 'Yes, Clear'"
                    ></x-button>
                </div>
            </div>
        </div>
    </div>
</x-admin-sidebar>
