{{-- resources/views/lecturer/exams/create-questions.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Add Questions to Exam') }}
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
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $exam->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $exam->subject->name }} • {{ $exam->class->name }} • {{ $exam->total_marks }} marks
                        </p>
                    </div>

                    <form action="{{ route('lecturer.exams.questions.store', $exam) }}" method="POST" id="questions-form">
                        @csrf

                        <div id="questions-container">
                            <!-- Question template will be cloned here -->


                        </div>

                        <!-- Add Question Button -->
                        <div class="mt-6">
                            <button type="button" id="add-question-btn" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Question
                            </button>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-8 mt-8 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('lecturer.exams.show', $exam) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save All Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Template (Hidden) - MUST BE INSIDE x-app-layout -->
    <template id="question-template">
        <div class="question-card border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                    Question <span class="question-number">1</span>
                </h4>
                <button type="button" class="remove-question-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-6">
                <!-- Question Text -->
                <div>
                    <x-input-label for="question_text" :value="__('Question Text')" />
                    <textarea name="questions[0][question_text]" 
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              rows="3" required></textarea>
                </div>

                <!-- Question Type -->
                <div>
                    <x-input-label for="type" :value="__('Question Type')" />
                    <div class="mt-2 space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="questions[0][type]" value="multiple_choice" 
                                   class="question-type h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                   checked>
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Multiple Choice</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="questions[0][type]" value="open_text" 
                                   class="question-type h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Open Text</span>
                        </label>
                    </div>
                </div>

                <!-- Marks -->
                <div>
                    <x-input-label for="marks" :value="__('Marks')" />
                    <input type="number" name="questions[0][marks]" min="1" 
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           value="1" required>
                </div>

                <!-- Multiple Choice Options -->
                <div class="multiple-choice-options space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label :value="__('Options (for multiple choice)')" />
                        <button type="button" class="add-option-btn text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                            + Add Option
                        </button>
                    </div>
                    
                    <div class="options-container space-y-3">
                        <!-- Options will be added here by JavaScript -->
                    </div>

                    <!-- Correct Answer -->
                    <div>
                        <x-input-label for="correct_answer" :value="__('Correct Answer')" />
                        <select name="questions[0][correct_answer]" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">Select correct answer...</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Option Template (Hidden) -->
    <template id="option-template">
        <div class="option-item flex items-center space-x-3">
            <input type="text" name="questions[0][options][]" 
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                   placeholder="Enter option text" required>
            <button type="button" class="remove-option-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, checking elements...');
        console.log('Add button:', document.getElementById('add-question-btn'));
        console.log('Template:', document.getElementById('question-template'));
        
        let questionCount = 0;
        
        // Simple add question function
        document.getElementById('add-question-btn').addEventListener('click', function() {
            console.log('Button clicked!');
            
            questionCount++;
            const container = document.getElementById('questions-container');
            
            const newQuestion = 
                <div class="question-card border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                            Question ${questionCount}
                        </h4>
                        <button type="button" class="remove-question-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="this.closest('.question-card').remove()">
                            Remove
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question Text</label>
                            <textarea name="questions[${questionCount}][question_text]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question Type</label>
                            <div class="mt-2 space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="questions[${questionCount}][type]" value="multiple_choice" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm">Multiple Choice</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="questions[${questionCount}][type]" value="open_text" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm">Open Text</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marks</label>
                            <input type="number" name="questions[${questionCount}][marks]" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                    </div>
                </div>
            ;
            
            container.insertAdjacentHTML('beforeend', newQuestion);
            console.log('Question added successfully!');
        });
    });
</script>
@endpush
</x-app-layout>  {{-- MAKE SURE THIS IS THE ONLY CLOSING TAG --}}