{{-- resources/views/student/exams/results.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exam Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Results Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $attempt->exam->title }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $attempt->exam->subject->name }} • {{ $attempt->exam->class->name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="text-4xl font-bold {{ $passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $scorePercentage }}%
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Score</div>
                        </div>

                        <div class="text-center p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="text-4xl font-bold text-gray-900 dark:text-white">
                                {{ $attempt->total_marks_obtained }}/{{ $attempt->exam->total_marks }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Marks Obtained</div>
                        </div>

                        <div class="text-center p-6 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div class="text-4xl font-bold {{ $passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $passed ? 'PASSED' : 'FAILED' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">Result</div>
                        </div>
                    </div>

                    <!-- Exam Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Exam Information</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                    <span class="font-medium">{{ $attempt->exam->duration_minutes }} minutes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Passing Marks:</span>
                                    <span class="font-medium">{{ $attempt->exam->passing_marks }}/{{ $attempt->exam->total_marks }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Attempt Date:</span>
                                    <span class="font-medium">{{ $attempt->created_at->format('F d, Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Time Taken:</span>
                                    <span class="font-medium">
                                        @if($attempt->start_time && $attempt->end_time)
                                            {{ $attempt->start_time->diffInMinutes($attempt->end_time) }} minutes
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Performance Summary</h3>
                            <div class="space-y-2">
                                @php
                                    $correctAnswers = $attempt->answers->where('is_correct', true)->count();
                                    $totalQuestions = $attempt->exam->questions->count();
                                    $correctPercentage = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
                                @endphp
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Questions Answered:</span>
                                    <span class="font-medium">{{ $attempt->answers->count() }}/{{ $totalQuestions }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Correct Answers:</span>
                                    <span class="font-medium">{{ $correctAnswers }}/{{ $totalQuestions }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Accuracy:</span>
                                    <span class="font-medium">{{ number_format($correctPercentage, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Review -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Question Review</h3>
                    
                    <div class="space-y-8">
                        @foreach($attempt->exam->questions as $index => $question)
                            @php
                                $answer = $attempt->answers->where('question_id', $question->id)->first();
                                $isCorrect = $answer ? $answer->is_correct : false;
                            @endphp
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 
                                 {{ $isCorrect ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }}">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                            Question {{ $index + 1 }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $question->marks }} marks • 
                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                              {{ $isCorrect ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                                        </span>
                                        <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $answer ? $answer->marks_obtained : 0 }}/{{ $question->marks }} marks
                                        </span>
                                    </div>
                                </div>

                                <!-- Question -->
                                <div class="mb-4">
                                    <p class="text-gray-700 dark:text-gray-300">
                                        {{ $question->question_text }}
                                    </p>
                                </div>

                                <!-- Your Answer -->
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Your Answer:</h5>
                                    <div class="{{ $isCorrect ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }} p-3 rounded bg-white dark:bg-gray-800 border">
                                        {{ $answer ? $answer->answer : 'No answer provided' }}
                                    </div>
                                </div>

                                @if(!$isCorrect && $question->type === 'multiple_choice')
                                    <!-- Correct Answer -->
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Correct Answer:</h5>
                                        <div class="text-green-700 dark:text-green-300 p-3 rounded bg-white dark:bg-gray-800 border border-green-200 dark:border-green-800">
                                            {{ $question->correct_answer }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('student.dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                    ← Back to Dashboard
                </a>
                
                @if($attempt->exam->is_published)
                    <a href="{{ route('student.exams.show', $attempt->exam) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Retake Exam
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>