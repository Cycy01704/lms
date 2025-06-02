<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All Borrowed Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6 dark:text-white">All Borrowed Books</h1>

                    <form method="GET" action="{{ route('borrow.books') }}" class="flex items-center space-x-4 mb-4">
            <input
                type="text"
                name="search"
                placeholder="Search book or borrower"
                value="{{ old('search', $search) }}"
                class="flex-grow px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <label class="flex items-center space-x-2 cursor-pointer">
                <input
                    type="checkbox"
                    name="showReturned"
                    value="true"
                    onchange="this.form.submit()"
                    {{ $showReturned ? 'checked' : '' }}
                    class="form-checkbox h-5 w-5 text-blue-600"
                />
                <span class="text-gray-700 select-none dark:text-white">Show Returned</span>
            </label>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded"
            >
                Filter
            </button>
        </form>

                    <div class="overflow-x-auto bg-white rounded shadow">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2">Book Title</th>
                                    @if(Auth::user()->usertype === 'admin')
                                        <th class="px-4 py-2">Borrower</th>
                                    @endif
                                    <th class="px-4 py-2">Quantity</th>
                                    <th class="px-4 py-2">Borrow Date</th>
                                    <th class="px-4 py-2">Due Date</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($borrowedBooks as $borrowed)
                                    <tr>
                                        <td class="px-4 py-2">{{ $borrowed->book->title }}</td>
                                        @if(Auth::user()->usertype === 'admin')
                                            <td class="px-4 py-2">{{ $borrowed->user->name }}</td>
                                        @endif
                                        <td class="px-4 py-2">{{ $borrowed->quantity }}</td>
                                        <td class="px-4 py-2">
                                            {{ \Carbon\Carbon::parse($borrowed->borrowTransaction->borrow_date)->format('m/d/Y') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ \Carbon\Carbon::parse($borrowed->borrowTransaction->due_date)->format('m/d/Y') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @php $status = $borrowed->borrowTransaction->status ?? '-'; @endphp
                                            @if ($status == 'borrowed')
                                                <span
                                                    class="inline-block bg-yellow-400 text-yellow-900 text-xs font-medium px-2 py-1 rounded">Borrowed</span>
                                            @elseif ($status == 'overdue')
                                                <span
                                                    class="inline-block bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">Overdue</span>
                                            @elseif ($status == 'returned')
                                                <span
                                                    class="inline-block bg-green-500 text-white text-xs font-medium px-2 py-1 rounded">Returned</span>
                                            @elseif ($status == 'lost')
                                                <span
                                                    class="inline-block bg-red-600 text-white text-xs font-medium px-2 py-1 rounded">Lost</span>
                                            @elseif ($status == 'damaged')
                                                <span
                                                    class="inline-block bg-gray-500 text-white text-xs font-medium px-2 py-1 rounded">Damaged</span>
                                            @else
                                                <span class="text-gray-500 text-xs">{{ $status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center px-4 py-6 text-gray-500">No borrowed books found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>