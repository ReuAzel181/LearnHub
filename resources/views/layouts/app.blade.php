<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LearnHub') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/noteStorage.js') }}"></script>
    <script src="{{ asset('js/notes.js') }}"></script>
    <script src="{{ asset('js/debugHelper.js') }}"></script>
    <script src="{{ asset('js/forceDisplayNotes.js') }}"></script>
    
    <!-- Test note creation script - can be removed in production -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if any notes exist, if not create a test note
        setTimeout(function() {
            if (typeof NoteStorage !== 'undefined') {
                const notes = NoteStorage.getNotes();
                
                if (notes.length === 0) {
                    console.log('Creating test note for demo purposes');
                    NoteStorage.addNote({
                        title: 'Welcome to LearnHub Notes',
                        content: '<p>This is your first note. You can edit this note or create new ones using the Notes tool.</p><p>Notes you create will appear on the dashboard for quick access.</p>',
                        date: new Date().toLocaleString()
                    });
                    
                    // Trigger notes-updated event to refresh the dashboard
                    document.dispatchEvent(new CustomEvent('notes-updated'));
                }
            }
        }, 1000); // Wait a second to ensure everything is loaded
    });
    </script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50">
    <div class="min-h-screen flex">
        @include('components.header')
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white/80 backdrop-blur-sm fixed h-full border-r border-gray-100/50 transition-all duration-300">
            <div class="flex flex-col h-full">
                <!-- Logo Section -->
                <div class="p-6 flex items-center space-x-4">
                    <button class="menu-toggle p-2 hover:bg-gray-100/80 rounded-xl transition-all duration-200">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">LearnHub</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 space-y-2 py-4">
                    <div class="space-y-1">
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                            <i class="fas fa-home w-5 h-5 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                            <i class="fas fa-book w-5 h-5 mr-3"></i>
                            <span>Courses</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                            <i class="fas fa-tasks w-5 h-5 mr-3"></i>
                            <span>Assignments</span>
                        </a>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                            <i class="fas fa-calendar w-5 h-5 mr-3"></i>
                            <span>Schedule</span>
                        </a>
                    </div>

                    <div class="mt-8">
                        <h3 class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Resources</h3>
                        <div class="mt-4 space-y-1">
                            <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                                <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                                <span>Documents</span>
                            </a>
                            <a href="#" class="flex items-center px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-100/80 transition-all duration-200">
                                <i class="fas fa-video w-5 h-5 mr-3"></i>
                                <span>Videos</span>
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- User Section -->
                <div class="p-4 mt-auto">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50/80 rounded-xl">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center text-white">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Guest' }}</h3>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
                        </div>
                        <button class="p-1.5 hover:bg-gray-200/80 rounded-lg transition-colors duration-200">
                            <i class="fas fa-cog text-gray-500"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html> 