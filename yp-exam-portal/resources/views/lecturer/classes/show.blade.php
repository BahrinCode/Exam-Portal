{{-- resources/views/lecturer/classes/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $class->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('lecturer.classes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                    ← Back to Classes
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
                <!-- Class Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Class Details Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Class Information</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Basic details about this class</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Class Name</h4>
                                    <p class="mt-1 text-gray-900 dark:text-white">{{ $class->name }}</p>
                                </div>

                                @if($class->description)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h4>
                                        <p class="mt-1 text-gray-900 dark:text-white">{{ $class->description }}</p>
                                    </div>
                                @endif

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</h4>
                                    <p class="mt-1 text-gray-900 dark:text-white">{{ $class->created_at->format('F d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Students</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $students->count() }} students enrolled</p>
                                </div>
                            </div>

                            @if($students->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No students enrolled</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add students to this class to get started.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($students as $student)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->student_id }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <form action="{{ route('lecturer.classes.remove-student', ['class' => $class, 'student' => $student]) }}" 
                                                              method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    onclick="return confirm('Are you sure you want to remove this student from the class?')"
                                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                                Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Add Student Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Student</h3>
                            
                            <form action="{{ route('lecturer.classes.add-student', $class) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="student_id" :value="__('Select Student')" />
                                        <select id="student_id" name="student_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                            <option value="">Choose a student...</option>
                                            @foreach($availableStudents as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('student_id')" />
                                    </div>

                                    @if($availableStudents->isEmpty())
                                        <p class="text-sm text-gray-500 dark:text-gray-400">All available students are already enrolled in this class.</p>
                                    @else
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Add Student
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Class Stats</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Students</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $students->count() }}</span>
                                </div>
                                @php
                                    $examsCount = $class->exams->count();
                                    $activeExams = $class->exams->where('is_published', true)->count();
                                @endphp
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Exams</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $examsCount }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Active Exams</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $activeExams }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>