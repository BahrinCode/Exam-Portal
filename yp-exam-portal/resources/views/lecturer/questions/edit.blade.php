
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Question for: {{ $exam->title }}
            </h2>
            <a href="{{ route('lecturer.exams.questions.index', $exam) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                Back to Questions
            </a>
        </div>
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

                    <form method="POST" action="{{ route('lecturer.exams.questions.update', [$exam, $question]) }}" id="question-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Question Type -->
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Question Type <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="multiple_choice" id="type_multiple_choice" class="form-radio text-indigo-600" {{ old('type', $question->type) == 'multiple_choice' ? 'checked' : '' }} required>
                                    <span class="ml-2">Multiple Choice</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="type" value="open_text" id="type_open_text" class="form-radio text-indigo-600" {{ old('type', $question->type) == 'open_text' ? 'checked' : '' }}>
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
                            <textarea name="question_text" id="question_text" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Marks -->
                        <div class="mb-6">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Marks <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="marks" id="marks" class="w-32 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" value="{{ old('marks', $question->marks) }}" min="1" max="100" required>
                            @error('marks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Multiple Choice Options -->
                        <div id="multiple-choice-section" class="mb-6 {{ old('type', $question->type) != 'multiple_choice' ? 'hidden' : '' }}">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Multiple Choice Options <span class="text-red-500">*</span>
                                <span class="text-sm font-normal text-gray-500">(Check the correct answer)</span>
                            </label>
                            
                            <div id="options-container">
                                <!-- Options will be added here dynamically -->
                                @php
                                    $options = $question->options ? json_decode($question->options, true) : [];
                                    $correctAnswer = $question->correct_answer;
                                    $correctIndex = null;
                                    
                                    // Find which option matches the correct answer
                                    if ($options && $correctAnswer) {
                                        foreach ($options as $index => $option) {
                                            if ($option == $correctAnswer) {
                                                $correctIndex = $index;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    // Make sure we have at least 2 options for multiple choice
                                    if ($question->type === 'multiple_choice' && count($options) < 2) {
                                        // Add empty options to reach minimum
                                        while (count($options) < 2) {
                                            $options[] = '';
                                        }
                                    }
                                @endphp
                                
                                @foreach($options as $index => $option)
                                <div class="option-item mb-3">
                                    <div class="flex items-center">
                                        <input type="radio" name="correct_option" value="{{ $index }}" class="mr-3" {{ $index == $correctIndex ? 'checked' : '' }}>
                                        <input type="text" name="options[]" class="flex-grow border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm option-input" placeholder="Option {{ chr(65 + $index) }}" value="{{ old('options.' . $index, $option) }}">
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
                        <div id="open-text-section" class="mb-6 {{ old('type', $question->type) != 'open_text' ? 'hidden' : '' }}">
                            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                Model Answer (Optional)
                            </label>
                            <textarea name="correct_answer" id="correct_answer" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3" placeholder="Enter the expected answer or key points...">{{ old('correct_answer', $question->type == 'open_text' ? $question->correct_answer : '') }}</textarea>
                            @error('correct_answer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Hidden field for correct answer -->
                        <input type="hidden" name="correct_answer_final" id="correct_answer_final" value="{{ old('correct_answer_final', $question->correct_answer) }}">
                        
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <a href="{{ route('lecturer.exams.questions.index', $exam) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                            </div>
                            <div class="space-x-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Update Question
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
        const questionForm = document.getElementById('question-form');
        const correctAnswerFinal = document.getElementById('correct_answer_final');
        
        let optionCount = {{ count($question->options ? json_decode($question->options, true) : []) }};
        
        // Function to update required attributes
        function updateRequiredAttributes() {
            const optionInputs = document.querySelectorAll('.option-input');
            const isMultipleChoice = typeMultipleChoice.checked;
            
            optionInputs.forEach(input => {
                if (isMultipleChoice) {
                    input.disabled = false;
                    input.classList.remove('bg-gray-100', 'text-gray-400');
                } else {
                    input.disabled = true;
                    input.classList.add('bg-gray-100', 'text-gray-400');
                }
            });
        }
        
        // Show/hide sections based on question type
        function updateQuestionTypeUI() {
            if (typeMultipleChoice.checked) {
                multipleChoiceSection.classList.remove('hidden');
                openTextSection.classList.add('hidden');
                // Enable all option inputs
                updateRequiredAttributes();
                // Show remove buttons when we have more than 2 options
                if (optionCount > 2) {
                    document.querySelectorAll('.remove-option').forEach(btn => {
                        btn.classList.remove('hidden');
                    });
                }
            } else if (typeOpenText.checked) {
                multipleChoiceSection.classList.add('hidden');
                openTextSection.classList.remove('hidden');
                // Disable all option inputs
                updateRequiredAttributes();
            }
        }
        
        // Add event listeners to radio buttons
        typeMultipleChoice.addEventListener('change', updateQuestionTypeUI);
        typeOpenText.addEventListener('change', updateQuestionTypeUI);
        
        // Add new option
        addOptionBtn.addEventListener('click', function() {
            optionCount++;
            const optionIndex = optionCount - 1;
            const optionLetter = String.fromCharCode(65 + optionCount); // A, B, C, etc.
            
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
            updateRequiredAttributes();
            
            // Show remove buttons on all options when we have more than 2
            if (optionCount > 2) {
                document.querySelectorAll('.remove-option').forEach(btn => {
                    btn.classList.remove('hidden');
                });
            }
        });
        
        // Remove option (event delegation)
        optionsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const optionItem = e.target.closest('.option-item');
                if (optionCount > 2) {
                    optionItem.remove();
                    optionCount--;
                    
                    // Re-index radio buttons
                    document.querySelectorAll('.option-item').forEach((item, index) => {
                        const radio = item.querySelector('input[type="radio"]');
                        radio.value = index;
                    });
                    
                    // Hide remove buttons if we're back to 2 options
                    if (optionCount === 2) {
                        document.querySelectorAll('.remove-option').forEach(btn => {
                            btn.classList.add('hidden');
                        });
                    }
                }
            }
        });
        
        // Form submission - set correct answer
        // Form submission - set correct answer
questionForm.addEventListener('submit', function(e) {
    const questionType = document.querySelector('input[name="type"]:checked');
    
    if (!questionType) {
        e.preventDefault();
        alert('Please select a question type.');
        typeMultipleChoice.focus();
        return;
    }
    
    if (questionType.value === 'multiple_choice') {
        const optionInputs = document.querySelectorAll('.option-input:not([disabled])');
        const selectedOption = document.querySelector('input[name="correct_option"]:checked');
        
        // Check if at least 2 options are filled
        let filledOptions = 0;
        let hasEmptyOption = false;
        
        optionInputs.forEach((opt, index) => {
            if (opt.value.trim() === '') {
                hasEmptyOption = true;
                opt.classList.add('border-red-500');
            } else {
                filledOptions++;
                opt.classList.remove('border-red-500');
            }
        });
        
        if (hasEmptyOption) {
            e.preventDefault();
            alert('Please fill in all option fields for multiple choice question.');
            return;
        }
        
        if (filledOptions < 2) {
            e.preventDefault();
            alert('Please fill in at least 2 options for multiple choice question.');
            return;
        }
        
        if (!selectedOption) {
            e.preventDefault();
            alert('Please select the correct answer for multiple choice question.');
            return;
        }
        
        // Get the correct answer text
        const correctOptionIndex = parseInt(selectedOption.value);
        const correctOptionInput = optionInputs[correctOptionIndex];
        const correctAnswerText = correctOptionInput ? correctOptionInput.value : '';
        
        // Set the correct answer in hidden field
        if (correctAnswerText) {
            correctAnswerFinal.value = correctAnswerText;
        } else {
            e.preventDefault();
            alert('Could not determine the correct answer. Please check your options.');
            return;
        }
        
    } else if (questionType.value === 'open_text') {
        // For open text, use the textarea value
        const correctAnswerTextarea = document.getElementById('correct_answer');
        correctAnswerFinal.value = correctAnswerTextarea.value;
    }
    
    // Debug: Check if hidden field is set
    console.log('Correct answer final value:', correctAnswerFinal.value);
    
    // If hidden field is still empty for some reason, prevent submission
    if (!correctAnswerFinal.value && questionType.value === 'multiple_choice') {
        e.preventDefault();
        alert('Could not determine the correct answer. Please try again.');
        return;
    }
});
        // Initialize the UI
        updateQuestionTypeUI();
    });
</script>
@endpush
</x-app-layout>