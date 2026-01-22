{{-- resources/views/lecturer/subjects/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Subject') }}
            </h2>
            <a href="{{ route('lecturer.subjects.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                ← Back to Subjects
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.subjects.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Subject Name -->
                            <div>
                                <x-input-label for="name" :value="__('Subject Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                              :value="old('name')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          >{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <!-- Classes Selection -->
                            <div>
                                <x-input-label for="classes" :value="__('Assign to Classes')" />
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    Select the classes that will study this subject. You can assign it to multiple classes.
                                </p>
                                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-4">
                                    @foreach($classes as $class)
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="class-{{ $class->id }}" 
                                                   name="classes[]" 
                                                   value="{{ $class->id }}"
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded"
                                                   {{ in_array($class->id, old('classes', [])) ? 'checked' : '' }}>
                                            <label for="class-{{ $class->id }}" 
                                                   class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $class->name }}
                                                @if($class->description)
                                                    <span class="text-gray-500 dark:text-gray-400 text-xs block">
                                                        {{ Str::limit($class->description, 50) }}
                                                    </span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if($classes->isEmpty())
                                    <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                                        You need to create classes first before creating subjects.
                                        <a href="{{ route('lecturer.classes.create') }}" class="underline">Create a class</a>
                                    </p>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('classes')" />
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('lecturer.subjects.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out"
                                        {{ $classes->isEmpty() ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Create Subject
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>