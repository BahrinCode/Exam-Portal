{{-- resources/views/student/exams/take.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $exam->title }}
            </h2>
            <div class="text-lg font-bold" id="timer">
                {{ floor($remainingTime / 60) }}:{{ str_pad($remainingTime % 60, 2, '0', STR_PAD_LEFT) }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="exam-form" action="{{ route('student.exams.submit', $exam) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Questions Navigation -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Questions</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Click to navigate</p>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach($questions as $index => $question)
                                        <a href="#question-{{ $question->id }}"
                                           class="flex items-center justify-center h-10 rounded-md border text-sm font-medium 
                                                  {{ $question->answers->isNotEmpty() ? 'bg-green-100 border-green-300 text-green-800 dark:bg-green-900 dark:border-green-700 dark:text-green-200' : 'bg-gray-100 border-gray-300 text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200' }}
                                                  hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            {{ $index + 1 }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" 
                                        class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Submit Exam
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                                    All answers are auto-saved
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="lg:col-span-3">
                        <div class="space-y-6">
                            @foreach($questions as $index => $question)
                                <div id="question-{{ $question->id }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6">
                                        <!-- Question Header -->
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                    Question {{ $index + 1 }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $question->marks }} marks • 
                                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                </p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                  {{ $question->answers->isNotEmpty() ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                {{ $question->answers->isNotEmpty() ? 'Answered' : 'Not Answered' }}
                                            </span>
                                        </div>

                                        <!-- Question Text -->
                                        <div class="mb-6">
                                            <p class="text-gray-700 dark:text-gray-300 text-lg">
                                                {{ $question->question_text }}
                                            </p>
                                        </div>

                                        <!-- Answer Options -->
                                        <div class="space-y-3">
                                            @if($question->type === 'multiple_choice')
                                                 @php
                                                    // Decode options from JSON string to array
                                                    $options = $question->options;
                                                    if (is_string($options)) {
                                                        $options = json_decode($options, true) ?? [];
                                                    }
                                                    $options = is_array($options) ? $options : [];
                                                @endphp
                                                @if(empty($options))
                                                    <p class="text-red-500 text-sm">No options available for this question.</p>
                                                @else    
                                                    @foreach($options as $key => $option)
                                                        <div class="flex items-center">
                                                            <input type="radio" 
                                                               id="question-{{ $question->id }}-option-{{ $key }}"
                                                                name="answers[{{ $question->id }}]"
                                                                value="{{ $option }}"
                                                                {{ $question->answers->isNotEmpty() && $question->answers->first()->answer === $option ? 'checked' : '' }}
                                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                                                                onchange="saveAnswer({{ $question->id }}, '{{ addslashes($option) }}')">
                                                        <label for="question-{{ $question->id }}-option-{{ $key }}" 
                                                               class="ml-3 block text-gray-700 dark:text-gray-300">
                                                            {{ $option }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif  
                                            @elseif($question->type === 'open_text')
                                                <textarea 
                                                    id="question-{{ $question->id }}-answer"
                                                    name="answers[{{ $question->id }}]"
                                                    rows="4"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                    placeholder="Type your answer here..."
                                                    oninput="saveAnswer({{ $question->id }}, this.value)"
                                                >{{ $question->answers->isNotEmpty() ? $question->answers->first()->answer : '' }}</textarea>
                                            @endif
                                        </div>

                                        <!-- Save Status -->
                                        <div id="save-status-{{ $question->id }}" class="mt-3 text-sm text-gray-500 dark:text-gray-400 hidden">
                                            <svg class="inline w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Saving...
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button (Mobile) -->
                        <div class="mt-6 lg:hidden">
                            <button type="submit" 
                                    class="w-full py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Submit Exam
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Auto-submit warning modal -->
    <div id="timeout-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-3">Time's Up!</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Your time for this exam has expired. The exam will be submitted automatically.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="submitExam()"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Submit Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let remainingTime = {{ $remainingTime }};
        let timerInterval;
        let isSubmitting = false;

        // Timer function
        function updateTimer() {
            if (remainingTime <= 0) {
                clearInterval(timerInterval);
                showTimeoutModal();
                return;
            }

            remainingTime--;
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            document.getElementById('timer').textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

        // Initialize timer
        timerInterval = setInterval(updateTimer, 1000);

        // Save answer function
        // Save answer function
function saveAnswer(questionId, answer) {
    const saveStatus = document.getElementById(`save-status-${questionId}`);
    saveStatus.classList.remove('hidden');
    
    // Use the correct route with dynamic question ID
    fetch(`/student/exams/{{ $exam->id }}/questions/${questionId}/answer`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ answer: answer })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                saveStatus.classList.add('hidden');
                // Update question status indicator
                const questionIndicator = document.querySelector(`a[href="#question-${questionId}"]`);
                if (questionIndicator) {
                    questionIndicator.classList.remove('bg-gray-100', 'border-gray-300', 'text-gray-800', 
                                                      'dark:bg-gray-700', 'dark:border-gray-600', 'dark:text-gray-200');
                    questionIndicator.classList.add('bg-green-100', 'border-green-300', 'text-green-800',
                                                   'dark:bg-green-900', 'dark:border-green-700', 'dark:text-green-200');
                }
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        saveStatus.textContent = 'Error saving. Please try again.';
        saveStatus.classList.add('text-red-500');
    });
}

        // Show timeout modal
        function showTimeoutModal() {
            document.getElementById('timeout-modal').classList.remove('hidden');
            // Auto-submit after 5 seconds
            setTimeout(submitExam, 5000);
        }

        // Submit exam
        function submitExam() {
            if (isSubmitting) return;
            
            isSubmitting = true;
            document.getElementById('exam-form').submit();
        }

        // Prevent accidental navigation
        window.addEventListener('beforeunload', function (e) {
            if (!isSubmitting) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });

        // Handle form submission
        document.getElementById('exam-form').addEventListener('submit', function(e) {
            if (!isSubmitting) {
                isSubmitting = true;
                return true;
            }
            e.preventDefault();
            return false;
        });
    </script>
    @endpush
</x-app-layout>