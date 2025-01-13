<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup Management System</title>
    <!-- Load dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }

        .fade-in {
            transition: all 0.3s ease-in-out;
        }

        .processing-overlay {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
        }
    </style>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen"
    x-data="{
        showConfirmDelete: false,
        selectedBackup: null,
        processing: false,
        currentStep: '',
        progress: 0,

        async handleBackup(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            this.processing = true;
            this.progress = 0;
            this.currentStep = 'Initializing backup process...';

            const steps = [
                { message: 'Preparing files for backup...', progress: 20 },
                { message: 'Compressing data...', progress: 40 },
                { message: 'Encrypting backup...', progress: 60 },
                { message: 'Saving backup file...', progress: 80 },
                { message: 'Finalizing...', progress: 90 }
            ];

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                // Process steps
                for (const step of steps) {
                    await new Promise(resolve => {
                        setTimeout(() => {
                            this.currentStep = step.message;
                            this.progress = step.progress;
                            resolve();
                        }, 1000);
                    });
                }

                // Complete process
                await new Promise(resolve => {
                    setTimeout(() => {
                        this.progress = 100;
                        this.processing = false;
                        resolve();
                    }, 1000);
                });

                Swal.fire({
                    title: 'Success!',
                    text: 'Backup has been created successfully',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6'
                }).then(() => {
                    window.location.reload();
                });

            } catch (error) {
                this.processing = false;
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to create backup. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#EF4444'
                });
            }
        }
    }">

     <!-- Processing Overlay -->
     <div x-show="processing"
     x-cloak
     class="fixed inset-0 processing-overlay z-50 flex items-center justify-center"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
     <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
         <div class="flex flex-col items-center">
             <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
             <h3 class="text-lg font-semibold text-gray-900 mb-2">Creating Backup...</h3>
             <p x-text="currentStep" class="text-gray-600 text-center mb-4"></p>
             <div class="w-full bg-gray-200 rounded-full h-2.5">
                 <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300"
                     x-bind:style="'width: ' + progress + '%'"></div>
             </div>
         </div>
     </div>
 </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
       <!-- Header Section -->
       <div class="mb-10 slide-in">
        <h1 class="text-4xl font-bold text-gray-900 tracking-tight mb-2">Backup Management System</h1>
        <p class="text-gray-600">System backup control and monitoring dashboard</p>
    </div>


        <!-- Action Buttons -->
        <div class="mb-8 flex flex-wrap gap-4 slide-in">
            <form action="{{ route('backups.create') }}"
                method="POST"
                @submit="handleBackup">
                @csrf
                <button type="submit"
                    class="group relative inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
                    :disabled="processing">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 transition-all duration-200 transform group-hover:translate-x-2">
                        <svg class="h-5 w-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    <span class="pl-10" x-text="processing ? 'Processing...' : 'Create New Backup'"></span>
                </button>
            </form>
        </div>

        <!-- Backup Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden slide-in">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                File Name
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Size (KB)
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Modified
                            </th>
                            <th scope="col"
                                class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($backups as $backup)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ number_format($backup['size'] / 1024, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('backups.download', $backup['name']) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-blue-600 hover:text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                        <button
                                            @click="showConfirmDelete = true; selectedBackup = '{{ $backup['name'] }}'"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-md text-red-600 hover:text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p class="text-gray-500 text-lg font-medium">No backups found</p>
                                        <p class="text-gray-400 mt-1">Create your first backup to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showConfirmDelete" class="fixed inset-0 overflow-y-auto z-50"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="showConfirmDelete = false"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Delete Backup
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this backup? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form x-bind:action="'{{ route('backups.destroy', '') }}/' + selectedBackup" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" @click="showConfirmDelete = false"
                        class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification (Optional) -->
    <div x-data="{ showToast: false, message: '' }"
        @backup-success.window="showToast = true; message = $event.detail; setTimeout(() => showToast = false, 3000)"
        x-show="showToast" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-lg">
        <p x-text="message"></p>
    </div>

    <script>
        // Initialize Alpine.js store
        document.addEventListener('alpine:init', () => {
            Alpine.store('backups', {
                loading: false,
                setLoading(status) {
                    this.loading = status;
                }
            });
        });

        // Fungsi untuk menampilkan alert sebagai fallback jika SweetAlert2 gagal dimuat
        function showAlert(type, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: type,
                    confirmButtonText: 'OK',
                    confirmButtonColor: type === 'success' ? '#3B82F6' : '#EF4444'
                }).then(() => {
                    if (type === 'success') {
                        window.location.reload();
                    }
                });
            } else {
                alert(text);
                if (type === 'success') {
                    window.location.reload();
                }
            }
        }

        // Backup creation handler
        function handleBackupCreation(event) {
            const form = event.target;
            const formData = new FormData(form);

            // Set initial state
            this.processing = true;
            this.progress = 0;
            this.currentStep = 'Initializing backup process...';

            // Define backup process steps
            const steps = [{
                    message: 'Preparing files for backup...',
                    progress: 20
                },
                {
                    message: 'Compressing data...',
                    progress: 40
                },
                {
                    message: 'Encrypting backup...',
                    progress: 60
                },
                {
                    message: 'Saving backup file...',
                    progress: 80
                },
                {
                    message: 'Finalizing...',
                    progress: 90
                }
            ];

            // Send backup request
            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Process each step with delay
                    steps.forEach((step, index) => {
                        setTimeout(() => {
                            this.currentStep = step.message;
                            this.progress = step.progress;
                        }, index * 1000);
                    });

                    // Complete the process
                    setTimeout(() => {
                        this.progress = 100;
                        this.processing = false;
                        showAlert('success', 'Success!', 'Backup has been created successfully');
                    }, steps.length * 1000 + 500);
                })
                .catch(error => {
                    this.processing = false;
                    showAlert('error', 'Error!', 'Failed to create backup. Please try again.');
                });
        }
    </script>
</body>

</html>
