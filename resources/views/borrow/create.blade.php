<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Borrow Books') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">

                <div class="max-w-3xl mx-auto px-4 py-8">
                    <h1 class="text-2xl font-bold mb-6 text-gray-800">Borrow a Book</h1>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('borrow.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Book Select -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Select
                                Book</label>
                            <select name="book_id" id="book_id"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required>
                                <option value="">-- Select a Book --</option>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Borrower Select -->
                        @php $userType = Auth::user()->usertype; @endphp

                        @if($userType === 'admin')
                            <div class="mb-4">
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Borrower</label>
                                <select name="user_id" id="user_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Select a Borrower --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                
                            </div>
                        @else
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Borrower</label>
                                <input type="text" value="{{ Auth::user()->name }}" readonly
                                    class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-gray-700 cursor-not-allowed">
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            </div>
                        @endif


                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required>
                            @error('due_date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" id="quantity" min="1" value="1"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                required>
                            @error('quantity')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-between">
                            <a href="{{ route('borrow.index') }}"
                                class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                Back
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                Borrow Book
                            </button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>






</x-app-layout>