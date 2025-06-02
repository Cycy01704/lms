<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-7xl mx-auto py-8 px-4">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Librarian Dashboard</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        

                       


                       
                       <div class="bg-cyan-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Borrowed Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $borrowedCount }}</p>
                        </div>

                        <div class="bg-cyan-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">OverDue Books</h5>
                            <p class="text-3xl font-bold mt-2">{{ $OverDueCount }}</p>
                        </div>

                        <div class="bg-red-600 text-white rounded-lg shadow p-6">
                            <h5 class="text-lg font-semibold">Unpaid Fines (₱)</h5>
                            <p class="text-3xl font-bold mt-2">₱ {{ number_format($unpaidFines, 2) }}</p>
                        </div>



                        
                      
                        

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
