{{-- resources/views/lecturer/exams/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Exam') }}
            </h2>
            <a href="{{ route('lecturer.exams.show', $exam) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                ← Back to Exam
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('lecturer.exams.update', $exam) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Exam Title -->
                            <div>
                                <x-input-label for="title" :value="__('Exam Title')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" 
                                              :value="old('title', $exam->title)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <!-- Description -->
                            <div>
                                <x-input-label for="description" :value="__('Description (Optional)')" />
                                <textarea id="description" name="description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Brief description of the exam content">{{ old('description', $exam->description) }}</textarea>
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
                                                    {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
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
                                            <option value="{{ $class->id }}" 
                                                    {{ old('class_id', $exam->class_id) == $class->id ? 'selected' : '' }}>
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
                                                  class="mt-1 block w-full" :value="old('duration_minutes', $exam->duration_minutes)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('duration_minutes')" />
                                </div>

                                <div>
                                    <x-input-label for="total_marks" :value="__('Total Marks')" />
                                    <x-text-input id="total_marks" name="total_marks" type="number" min="1" 
                                                  class="mt-1 block w-full" :value="old('total_marks', $exam->total_marks)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('total_marks')" />
                                </div>

                                <div>
                                    <x-input-label for="passing_marks" :value="__('Passing Marks')" />
                                    <x-text-input id="passing_marks" name="passing_marks" type="number" min="0" 
                                                  class="mt-1 block w-full" :value="old('passing_marks', $exam->passing_marks)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('passing_marks')" />
                                </div>
                            </div>

                            <!-- Time Range (Optional) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="start_time" :value="__('Start Time (Optional)')" />
                                    @php
                                        $startTime = old('start_time', $exam->start_time);
                                        $startTimeValue = $startTime ? $startTime->format('Y-m-d\TH:i') : '';
                                    @endphp
                                    <x-text-input id="start_time" name="start_time" type="datetime-local" 
                                                  class="mt-1 block w-full" :value="$startTimeValue" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Leave empty for immediate availability
                                    </p>
                                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                                </div>

                                <div>
                                    <x-input-label for="end_time" :value="__('End Time (Optional)')" />
                                    @php
                                        $endTime = old('end_time', $exam->end_time);
                                        $endTimeValue = $endTime ? $endTime->format('Y-m-d\TH:i') : '';
                                    @endphp
                                    <x-text-input id="end_time" name="end_time" type="datetime-local" 
                                                  class="mt-1 block w-full" :value="$endTimeValue" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Leave empty for indefinite availability
                                    </p>
                                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="is_published" :value="__('Status')" />
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center">
                                        <input type="radio" id="status_draft" name="is_published" value="0" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                               {{ old('is_published', $exam->is_published) == 0 ? 'checked' : '' }}>
                                        <label for="status_draft" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Draft (Not visible to students)
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="status_published" name="is_published" value="1" 
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                               {{ old('is_published', $exam->is_published) == 1 ? 'checked' : '' }}>
                                        <label for="status_published" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Published (Visible to students)
                                        </label>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('is_published')" />
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end space-x-4 pt-6">
                                <a href="{{ route('lecturer.exams.show', $exam) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Update Exam
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="mt-12 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-4">Danger Zone</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Deleting this exam will also delete all associated questions, student attempts, and answers.
                        This action cannot be undone.
                    </p>
                    <form action="{{ route('lecturer.exams.destroy', $exam) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this exam? This will also delete all student attempts and answers. This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Exam
                        </button>
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