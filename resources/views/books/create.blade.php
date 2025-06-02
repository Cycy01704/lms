<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-4 py-2 px-4">
                    <div class="w-full mx-auto">
                        <h2 class="text-2xl font-bold mb-4 dark:text-white">Add Book</h2>


                        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label for="cover_image" class="block font-medium text-gray-700 mb-1 dark:text-white">Cover
                                    Image</label>
                                <input type="file" name="cover_image" id="cover_image" accept="image/*"
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-white" />
                            </div>

                            <div>
                                <label for="isbn" class="block font-medium text-gray-700 mb-1 dark:text-white">ISBN</label>
                                <input type="text" name="isbn" id="isbn" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('isbn')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block font-medium text-gray-700 mb-1 dark:text-white">Category</label>
                                <select name="category_id" id="category_id"
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="title" class="block font-medium text-gray-700 mb-1 dark:text-white">Title</label>
                                <input type="text" name="title" id="title" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="author" class="block font-medium text-gray-700 mb-1 dark:text-white">Author</label>
                                <input type="text" name="author" id="author" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('author')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="publisher" class="block font-medium text-gray-700 mb-1 dark:text-white">Publisher</label>
                                <input type="text" name="publisher" id="publisher" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('publisher')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="published_date" class="block font-medium text-gray-700 mb-1 dark:text-white">Published
                                    Date</label>
                                <input type="date" name="published_date" id="published_date" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    
                                </div>

                            <div>
                                <label for="quantity" class="block font-medium text-gray-700 mb-1 dark:text-white">Quantity</label>
                                <input type="number" name="quantity" id="quantity" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                </div>

                            <div>
                                <label for="available_copies" class="block font-medium text-gray-700 mb-1 dark:text-white">Available
                                    Copies</label>
                                <input type="number" name="available_copies" id="available_copies" value=""
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        @error('available_copies')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                </div>

                            <div>
                                <label for="description"
                                    class="block font-medium text-gray-700 mb-1 dark:text-white">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="block w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Save Book
                            </button>

                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>





</x-app-layout>