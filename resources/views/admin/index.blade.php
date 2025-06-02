<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-7xl mx-auto py-8 px-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Librarian Dashboard</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-blue-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Total Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $totalBooks }}</p>
                        </div>

                        <div class="bg-green-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Available Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $availableBooks }}</p>
                        </div>

                        <div class="bg-cyan-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Borrowed Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $borrowedBooks }}</p>
                        </div>

                        <div class="bg-red-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Overdue Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $overdueBooks }}</p>
                        </div>

                        <div class="bg-gray-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Lost Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $lostBooks }}</p>
                        </div>

                        <div class="bg-gray-900 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Damaged Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $damagedBooks }}</p>
                        </div>

                        <div class="bg-white border border-yellow-500 rounded-lg shadow p-6 mt-4">
                            <h5 class="text-lg font-semibold text-gray-700">Unpaid Fines (₱)</h5>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">₱ {{ number_format($unpaidFines, 2) }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>