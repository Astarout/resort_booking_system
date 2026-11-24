
{{-- Admin Backup and Recovery Page --}}
<x-admin-sidebar>

    {{-- Header --}}
    <header class="mb-6">
        <div class="flex items-center justify-between relative pb-3">
            <div class="flex items-center">
                <div class="absolute bottom-0 left-0 w-full h-[2px] bg-gradient-to-r from-gray-800 via-gray-600 to-gray-500"></div>
                <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-700 rounded mr-3"></div>
                <h1 class="text-3xl font-bold text-gray-800">Backup and Recovery</h1>
            </div>

            <div x-data="clock()" x-init="start()" class="hidden sm:block text-right font-semibold text-gray-700">
                <p x-text="date"></p>
                <p x-text="time" class="text-lg text-blue-600"></p>
            </div>
        </div>
    </header>

    <div x-data="backupPage()">
        {{-- Create Backup --}}
        <div class="bg-white p-6 shadow-md rounded-lg border border-gray-200 mb-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Create Backup</h2>

                <x-button
                    variant="primary"
                    @click="openModal('create')"
                    class="flex items-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                    </svg>
                    Create Backup
                </x-button>
            </div>

            <p class="text-gray-600 mt-2">
                Generate a new system backup to safeguard your data.
            </p>
        </div>

        {{-- Backup List --}}
        <div class="bg-white p-6 shadow-md rounded-lg border border-gray-200">
            <h2 class="text-xl font-semibold mb-4">Available Backups</h2>

            @if (count($backups) === 0)
                <p class="text-gray-600">No backups found.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-gray-700 border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="py-3 px-2 text-center">File Name</th>
                                <th class="py-3 px-2 text-center">Size</th>
                                <th class="py-3 px-2 text-center">Created At</th>
                                <th class="py-3 px-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($backups as $backup)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-3 px-2 font-medium text-center">{{ $backup['file_name'] }}</td>
                                    <td class="py-3 px-2 text-center">{{ $backup['size'] }}</td>
                                    <td class="py-3 px-2 text-center">{{ $backup['date'] }}</td>
                                    <td class="py-3 px-2 text-right space-x-2 flex justify-center items-center">

                                        {{-- Download --}}
                                        <x-button
                                            variant="secondary"
                                            @click="openModal('download', '{{ $backup['file_name'] }}')"
                                            class="flex gap-2 items-center justify-center"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-download-fill" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.5a.5.5 0 0 1 1 0V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0m-.354 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V11h-1v3.293l-2.146-2.147a.5.5 0 0 0-.708.708z"/>
                                            </svg>
                                            Download
                                        </x-button>

                                        {{-- Restore --}}
                                        <x-button
                                            variant="primary"
                                            @click="openModal('restore', '{{ $backup['file_name'] }}')"
                                            class="flex gap-2 items-center justify-center"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-recycle" viewBox="0 0 16 16">
                                                <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.5.5 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244z"/>
                                            </svg>
                                            Restore
                                        </x-button>

                                        {{-- Delete --}}
                                        <x-button
                                            variant="danger"
                                            @click="openModal('delete', '{{ $backup['file_name'] }}')"
                                            class="flex gap-2 items-center justify-center"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                            </svg>
                                            Delete
                                        </x-button>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Confirmation Modal --}}
        <div
            x-show="showModal"
            x-cloak
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
        >
            <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl mx-5">
                <h3 class="text-xl font-bold mb-4" x-text="modalTitle"></h3>
                <p class="text-gray-700 mb-6" x-text="modalMessage"></p>

                <div class="flex justify-end gap-3">
                    <x-button
                        @click="showModal = false"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded"
                        variant="secondary"
                    >Cancel</x-button>

                    <x-button
                        @click="confirmAction()"
                        variant="tertiary"
                        x-text="modalButtonText"
                    ></x-button>
                </div>
            </div>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script>
        function backupPage() {
            return {
                showModal: false,
                modalType: '',
                modalFile: '',
                modalTitle: '',
                modalMessage: '',
                modalButtonText: '',

                openModal(type, file = null) {
                    this.modalType = type;
                    this.modalFile = file;

                    switch(type) {
                        case 'restore':
                            this.modalTitle = 'Restore Backup';
                            this.modalMessage = 'Are you sure you want to restore this backup? This will overwrite your current database.';
                            this.modalButtonText = 'Restore';
                            break;
                        case 'delete':
                            this.modalTitle = 'Delete Backup';
                            this.modalMessage = 'Are you sure you want to delete this backup? This action cannot be undone.';
                            this.modalButtonText = 'Delete';
                            break;
                        case 'download':
                            this.modalTitle = 'Download Backup';
                            this.modalMessage = 'Do you want to download this backup file?';
                            this.modalButtonText = 'Download';
                            break;
                        case 'create':
                            this.modalTitle = 'Create Backup';
                            this.modalMessage = 'Are you sure you want to create a new backup?';
                            this.modalButtonText = 'Create';
                            break;
                    }

                    this.showModal = true;
                },

                confirmAction() {
                    switch(this.modalType) {
                        case 'restore':
                            window.location.href = `/admin/backup-and-recovery/restore/${this.modalFile}`;
                            break;
                        case 'delete':
                            window.location.href = `/admin/backup-and-recovery/delete/${this.modalFile}`;
                            break;
                        case 'download':
                            window.location.href = `/admin/backup-and-recovery/download/${this.modalFile}`;
                            break;
                        case 'create':
                            // Submit form for creating backup
                            const form = document.createElement('form');
                            form.action = "{{ route('admin.backup_and_recovery.run') }}";
                            form.method = 'POST';
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = "{{ csrf_token() }}";
                            form.appendChild(csrf);
                            document.body.appendChild(form);
                            form.submit();
                            break;
                    }
                }
            }
        }
    </script>

</x-admin-sidebar>
