<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-4">
                    <div class="max-w-4xl mx-auto px-4">
                        <a href="{{ route('categories.index') }}"
                            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-6">
                            Back to Category Page
                        </a>

                        <hr class="my-6 border-gray-300">

                        <div class="bg-white shadow rounded py-4">
                            <h2 class="text-xl font-semibold mb-4">Update Category</h2>

                            <form action="{{ route('categories.update', $category->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Enter Category Name
                                    </label>
                                    <input type="text" name="category_name" id="category_name"
                                        value="{{ $category->category_name }}"
                                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>

                                <button type="submit"
                                    class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 transition">
                                    Save Entry
                                </button>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>





</x-app-layout>