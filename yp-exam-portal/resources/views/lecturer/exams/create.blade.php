{{-- resources/views/lecturer/exams/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Exam') }}
            </h2>
            <a href="{{ route('lecturer.exams.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                ← Back to Exams
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.exams.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Exam Title -->
                            <div>
                                <x-input-label for="title" :value="__('Exam Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
                                              :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Brief description of the exam content">{{ old('description') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <!-- Subject and Class -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="subject_id" :value="__('Subject')" />
                                    <select id="subject_id" name="subject_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                        <option value="">Select a subject...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    {{ (old('subject_id') == $subject->id || $selectedSubjectId == $subject->id) ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                                </div>

                                <div>
                                    <x-input-label for="class_id" :value="__('Class')" />
                                    <select id="class_id" name="class_id" 
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                        <option value="">Select a class...</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('class_id')" />
                                </div>
                            </div>

                            <!-- Duration and Marks -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="duration_minutes" :value="__('Duration (minutes)')" />
                                    <x-text-input id="duration_minutes" name="duration_minutes" type="number" min="1" 
                                                  class="mt-1 block w-full" :value="old('duration_minutes', 60)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                                </div>

                                <div>
                                    <x-input-label for="total_marks" :value="__('Total Marks')" />
                                    <x-text-input id="total_marks" name="total_marks" type="number" min="1" 
                                                  class="mt-1 block w-full" :value="old('total_marks', 100)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('total_marks')" />
                                </div>

                                <div>
                                    <x-input-label for="passing_marks" :value="__('Passing Marks')" />
                                    <x-text-input id="passing_marks" name="passing_marks" type="number" min="0" 
                                                  class="mt-1 block w-full" :value="old('passing_marks', 50)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('passing_marks')" />
                                </div>
                            </div>

                            <!-- Time Range (Optional) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="start_time" :value="__('Start Time (Optional)')" />
                                    <x-text-input id="start_time" name="start_time" type="datetime-local" 
                                                  class="mt-1 block w-full" :value="old('start_time')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Leave empty for immediate availability
                                    </p>
                                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                                </div>

                                <div>
                                    <x-input-label for="end_time" :value="__('End Time (Optional)')" />
                                    <x-text-input id="end_time" name="end_time" type="datetime-local" 
                                                  class="mt-1 block w-full" :value="old('end_time')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Leave empty for indefinite availability
                                    </p>
                                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4 pt-6">
                                <a href="{{ route('lecturer.exams.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Create Exam
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Simple validation for passing marks <= total marks
        document.getElementById('total_marks').addEventListener('input', function() {
            const totalMarks = parseInt(this.value) || 0;
            const passingMarksInput = document.getElementById('passing_marks');
            const passingMarks = parseInt(passingMarksInput.value) || 0;
            
            if (passingMarks > totalMarks) {
                passingMarksInput.setCustomValidity('Passing marks cannot exceed total marks');
            } else {
                passingMarksInput.setCustomValidity('');
            }
        });

        document.getElementById('passing_marks').addEventListener('input', function() {
            const passingMarks = parseInt(this.value) || 0;
            const totalMarks = parseInt(document.getElementById('total_marks').value) || 0;
            
            if (passingMarks > totalMarks) {
                this.setCustomValidity('Passing marks cannot exceed total marks');
            } else {
                this.setCustomValidity('');
            }
        });

        // End time should be after start time
        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = this.value;
            const endTimeInput = document.getElementById('end_time');
            
            if (startTime && endTimeInput.value) {
                if (endTimeInput.value <= startTime) {
                    endTimeInput.setCustomValidity('End time must be after start time');
                } else {
                    endTimeInput.setCustomValidity('');
                }
            }
        });

        document.getElementById('end_time').addEventListener('change', function() {
            const endTime = this.value;
            const startTime = document.getElementById('start_time').value;
            
            if (startTime && endTime) {
                if (endTime <= startTime) {
                    this.setCustomValidity('End time must be after start time');
                } else {
                    this.setCustomValidity('');
                }
            }
        });
    </script>
    @endpush
</x-app-layout>