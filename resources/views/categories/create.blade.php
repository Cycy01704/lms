<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mt-4 py-2 px-4">
                    <div class="w-full mx-auto">
                        <h2 class="text-2xl font-bold mb-4 dark:text-white">Create Category</h2>
                        <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="category_name" class="block text-sm font-medium text-gray-700 mb-1 dark:text-white">
                                    Enter Category Name
                                </label>
                                <input type="text" id="category_name" name="category_name"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    required>
                            </div>
                            <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                Save Entry
                            </button>

                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>





</x-app-layout>