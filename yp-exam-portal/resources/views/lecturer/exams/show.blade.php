{{-- resources/views/lecturer/exams/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $exam->title }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('lecturer.exams.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                    ← Back to Exams
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Exam Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Exam Details Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Exam Information</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Basic details about this exam</p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('lecturer.exams.edit', $exam) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->title }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                              {{ $exam->is_published ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                            {{ $exam->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                </div>

                                @if($exam->description)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->description }}</p>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->subject->name }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Class</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->class->name }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->duration_minutes }} minutes</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Marks</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->total_marks }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Passing Marks</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->passing_marks }}</p>
                                    </div>
                                </div>

                                @if($exam->start_time || $exam->end_time)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if($exam->start_time)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Start Time</h4>
                                                <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->start_time->format('F d, Y H:i') }}</p>
                                            </div>
                                        @endif
                                        @if($exam->end_time)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">End Time</h4>
                                                <p class="mt-1 text-gray-900 dark:text-white">{{ $exam->end_time->format('F d, Y H:i') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Publish/Unpublish Actions -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                @if(!$exam->is_published)
                                    <form action="{{ route('lecturer.exams.publish', $exam) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Publish Exam
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('lecturer.exams.unpublish', $exam) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Unpublish Exam
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Questions Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Questions</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $questions->count() }} questions</p>
                                </div>
                                @if($questions->isEmpty())
                                    <a href="{{ route('lecturer.exams.questions.create', $exam) }}"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                        Add Questions
                                    </a>
                                @endif
                            </div>

                            @if($questions->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No questions yet</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add questions to make this exam ready.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('lecturer.exams.questions.create', $exam) }}"  
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                            Add Questions
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach($questions as $index => $question)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 dark:text-white">
                                                        Question {{ $index + 1 }}
                                                    </h4>
                                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                        {{ $question->question_text }}
                                                    </p>
                                                    <div class="mt-2 flex items-center space-x-3">
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            {{ $question->marks }} marks
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Stats Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Exam Statistics</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Attempts</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['total_attempts'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Completed</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['completed_attempts'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Average Score</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($stats['average_score'], 1) }}/{{ $exam->total_marks }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('lecturer.exams.questions.create', $exam) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add/Edit Questions
                                </a>
                                <a href="{{ route('lecturer.exams.edit', $exam) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Exam Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>