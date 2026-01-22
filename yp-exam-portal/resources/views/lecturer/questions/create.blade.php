<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Question for: {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Please fix the following errors:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('lecturer.exams.questions.store', $exam) }}" id="question-form">
                        @csrf
                        
                        <!-- Question Type -->
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Question Type <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="multiple_choice" id="type_multiple_choice" class="form-radio text-indigo-600" {{ old('type', 'multiple_choice') == 'multiple_choice' ? 'checked' : '' }} required>
                                    <span class="ml-2">Multiple Choice</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="open_text" id="type_open_text" class="form-radio text-indigo-600" {{ old('type') == 'open_text' ? 'checked' : '' }}>
                                    <span class="ml-2">Open Text (Essay/Short Answer)</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Question Text -->
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Question Text <span class="text-red-500">*</span>
                            </label>
                            <textarea name="question_text" id="question_text" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Marks -->
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Marks <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="marks" id="marks" class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('marks', 1) }}" min="1" max="100" required>
                            @error('marks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Multiple Choice Options -->
                        <div id="multiple-choice-section" class="mb-6 {{ old('type', 'multiple_choice') != 'multiple_choice' ? 'hidden' : '' }}">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Multiple Choice Options <span class="text-red-500">*</span>
                                <span class="text-sm font-normal text-gray-500">(Check the correct answer)</span>
                            </label>
                            
                            <div id="options-container">
                                @php
                                    $oldOptions = old('options', ['', '']);
                                @endphp
                                
                                @foreach($oldOptions as $index => $option)
                                <div class="option-item mb-3">
                                    <div class="flex items-center">
                                        <input type="radio" name="correct_option" value="{{ $index }}" class="mr-3" {{ old('correct_option') == $index ? 'checked' : '' }}>
                                        <input type="text" name="options[]" class="flex-grow border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm option-input" placeholder="Option {{ chr(65 + $index) }}" value="{{ $option }}">
                                        @if($index >= 2)
                                        <button type="button" class="ml-3 text-red-500 hover:text-red-700 remove-option">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @else
                                        <button type="button" class="ml-3 text-red-500 hover:text-red-700 remove-option hidden">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-option" class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:text-indigo-300 dark:bg-indigo-900 dark:hover:bg-indigo-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Another Option
                            </button>
                            
                            @error('options')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                            @error('correct_option')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Correct Answer for Open Text -->
                        <div id="open-text-section" class="mb-6 {{ old('type', 'multiple_choice') != 'open_text' ? 'hidden' : '' }}">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Model Answer (Optional)
                            </label>
                            <textarea name="correct_answer" id="correct_answer" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" placeholder="Enter the expected answer or key points...">{{ old('correct_answer') }}</textarea>
                            @error('correct_answer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <a href="{{ route('lecturer.exams.show', $exam) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Save Question
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
        document.addEventListener('DOMContentLoaded', function() {
            const multipleChoiceSection = document.getElementById('multiple-choice-section');
            const openTextSection = document.getElementById('open-text-section');
            const optionsContainer = document.getElementById('options-container');
            const addOptionBtn = document.getElementById('add-option');
            const typeMultipleChoice = document.getElementById('type_multiple_choice');
            const typeOpenText = document.getElementById('type_open_text');
            
            let optionCount = {{ count(old('options', ['', ''])) }};
            
            // Show/hide sections
            function updateQuestionTypeUI() {
                if (typeMultipleChoice.checked) {
                    multipleChoiceSection.classList.remove('hidden');
                    openTextSection.classList.add('hidden');
                    // Enable option inputs
                    document.querySelectorAll('.option-input').forEach(input => {
                        input.disabled = false;
                        input.classList.remove('bg-gray-100', 'text-gray-400');
                    });
                } else if (typeOpenText.checked) {
                    multipleChoiceSection.classList.add('hidden');
                    openTextSection.classList.remove('hidden');
                    // Disable option inputs
                    document.querySelectorAll('.option-input').forEach(input => {
                        input.disabled = true;
                        input.classList.add('bg-gray-100', 'text-gray-400');
                    });
                }
            }
            
            // Radio button events
            typeMultipleChoice.addEventListener('change', updateQuestionTypeUI);
            typeOpenText.addEventListener('change', updateQuestionTypeUI);
            
            // Add option
            addOptionBtn.addEventListener('click', function() {
                optionCount++;
                const optionIndex = optionCount - 1;
                const optionLetter = String.fromCharCode(65 + optionCount);
                
                const optionDiv = document.createElement('div');
                optionDiv.className = 'option-item mb-3';
                optionDiv.innerHTML = `
                    <div class="flex items-center">
                        <input type="radio" name="correct_option" value="${optionIndex}" class="mr-3">
                        <input type="text" name="options[]" class="flex-grow border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm option-input" placeholder="Option ${optionLetter}">
                        <button type="button" class="ml-3 text-red-500 hover:text-red-700 remove-option">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                `;
                
                optionsContainer.appendChild(optionDiv);
                updateQuestionTypeUI();
                
                // Show remove buttons
                if (optionCount > 2) {
                    document.querySelectorAll('.remove-option').forEach(btn => {
                        btn.classList.remove('hidden');
                    });
                }
            });
            
            // Remove option
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option') && optionCount > 2) {
                    e.target.closest('.option-item').remove();
                    optionCount--;
                    
                    // Re-index
                    document.querySelectorAll('.option-item').forEach((item, index) => {
                        item.querySelector('input[type="radio"]').value = index;
                    });
                    
                    // Hide remove buttons if only 2 left
                    if (optionCount === 2) {
                        document.querySelectorAll('.remove-option').forEach(btn => {
                            btn.classList.add('hidden');
                        });
                    }
                }
            });
            
            // Initialize
            updateQuestionTypeUI();
        });
    </script>
    @endpush
</x-app-layout>