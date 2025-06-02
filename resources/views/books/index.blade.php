<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>

    <div x-data="{ open: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6 dark:text-white">Book List</h1>

                    @if(Auth::user()->usertype !== 'user')
                    <a href="{{ route('books.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm mb-4 inline-block">
                        Add New Book
                    </a>
                    @endif

                    @if (session('success'))
                    <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="overflow-x-auto bg-white rounded shadow">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2">ISBN</th>
                                    <th class="px-4 py-2">Category</th>
                                    <th class="px-4 py-2">Title</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 divide-y divide-gray-200">
                                @forelse ($books as $book)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $book->isbn }}</td>
                                    <td class="px-4 py-2">{{ $book->category->category_name ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $book->title }}</td>
                                    <td class="px-4 py-2 text-center space-x-1">
                                        @php $userType = Auth::user()->usertype; @endphp

                                        @if($userType === 'user')
                                        <button @click="open = {{ $book->id }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            View
                                        </button>
                                        <form action="{{ route('borrow.borrow', $book->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Borrow Book</button>
                                        </form>


                                        @else
                                        <a href="{{ route('books.edit', $book->id) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                            Edit
                                        </a>
                                        <button @click="open = {{ $book->id }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                            View
                                        </button>

                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this book?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Delete
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center px-4 py-6 text-gray-500">
                                        No books available.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Book Detail Modals -->
                    @foreach ($books as $book)
                    <div x-show="open === {{ $book->id }}" x-cloak x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]">
                            <button @click="open = null"
                                class="absolute top-2 right-2 text-gray-400 hover:text-black text-xl">
                                &times;
                            </button>
                            <h2 class="text-xl font-semibold mb-4">{{ $book->title }}</h2>

                            @if ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Book Cover"
                                class="w-1/2 mb-4 rounded shadow">
                            @endif

                            <div class="space-y-2 text-sm text-gray-700">
                                <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                                <p><strong>Category:</strong> {{ $book->category->category_name ?? 'N/A' }}</p>
                                <p><strong>Author:</strong> {{ $book->author ?? 'Unknown' }}</p>
                                <p><strong>Publisher:</strong> {{ $book->publisher ?? 'Unknown' }}</p>
                                <p><strong>Date Published:</strong> {{ $book->published_date ?? 'Unknown' }}</p>
                                <p><strong>Total Copies:</strong> {{ $book->quantity }}</p>
                                <p><strong>Available Copies:</strong> {{ $book->available_copies }}</p>
                                <p><strong>Description:</strong></p>
                                <p>{{ $book->description ?? 'No description provided.' }}</p>
                            </div>

                            <div class="mt-6 text-right">
                                <button @click="open = null"
                                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>