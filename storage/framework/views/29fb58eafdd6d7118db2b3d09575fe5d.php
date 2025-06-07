<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LearnHub</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            :root {
                /* Light theme */
                --bg-main: #f8f9fa;
                --bg-card: #ffffff;
                --bg-sidebar: #ffffff;
                --bg-hover: #f0f2f5;
                --bg-active: #e7f3ff;
                --text-primary: #1a1a1a;
                --text-secondary: #65676b;
                --text-active: #0d6efd;
                --border-color: #e4e6eb;
                --shadow-color: rgba(0, 0, 0, 0.1);
                
                /* Sidebar dimensions */
                --sidebar-min-width: 280px;
                --sidebar-max-width: 500px;
                --sidebar-collapsed-width: 70px;
                --header-height: 60px;
                --transition-speed: 0.3s;
            }

            /* Dark theme */
            [data-theme="dark"] {
                --bg-main: #18191a;
                --bg-card: #242526;
                --bg-sidebar: #242526;
                --bg-hover: #3a3b3c;
                --bg-active: #263951;
                --text-primary: #e4e6eb;
                --text-secondary: #b0b3b8;
                --border-color: #3e4042;
                --shadow-color: rgba(0, 0, 0, 0.3);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                background: var(--bg-main);
                color: var(--text-primary);
                line-height: 1.5;
            }

            .main-content {
                margin-left: var(--sidebar-min-width);
                margin-top: var(--header-height);
                padding: 2rem;
                min-height: calc(100vh - var(--header-height));
                transition: margin-left var(--transition-speed) ease;
                background: var(--bg-main);
            }

            .main-content.sidebar-collapsed {
                margin-left: var(--sidebar-collapsed-width);
            }

            .welcome-text {
                font-size: 1.5rem;
                color: var(--text-primary);
                margin-bottom: 2rem;
            }

            .tools-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .tool-card {
                background: var(--bg-card);
                border-radius: 12px;
                padding: 1.5rem;
                text-align: center;
                cursor: pointer;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                border: 1px solid var(--border-color);
            }

            .tool-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px var(--shadow-color);
            }

            .tool-icon {
                font-size: 2rem;
                color: var(--text-active);
                margin-bottom: 1rem;
            }

            .tool-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: var(--text-primary);
            }

            .tool-description {
                color: var(--text-secondary);
                font-size: 0.9rem;
            }

            .saved-notes {
                background: var(--bg-card);
                border-radius: 12px;
                padding: 1.5rem;
                border: 1px solid var(--border-color);
            }

            .saved-notes h2 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
                color: var(--text-primary);
            }

            .saved-note-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid var(--border-color);
                cursor: pointer;
                transition: background-color 0.2s ease;
            }

            .saved-note-item:last-child {
                border-bottom: none;
            }

            .saved-note-item:hover {
                background: var(--bg-hover);
            }

            .saved-note-title {
                font-weight: 500;
                color: var(--text-primary);
            }

            .saved-note-date {
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            @media (max-width: 768px) {
                .main-content {
                    margin-left: 0;
                    padding: 1rem;
                }

                .main-content.sidebar-collapsed {
                    margin-left: 0;
                }

                .tools-grid {
                    grid-template-columns: 1fr;
                }
            }

            .tool-content {
                display: none;
                background: var(--bg-card);
                border-radius: 12px;
                padding: 1.5rem;
                margin-top: 1rem;
                border: 1px solid var(--border-color);
            }

            #section-title {
                font-size: 1.5rem;
                margin-bottom: 1rem;
                color: var(--text-primary);
            }

            #exit-button {
                position: fixed;
                top: calc(var(--header-height) + 1rem);
                right: 1rem;
                padding: 0.5rem 1rem;
                background: var(--bg-card);
                border: 1px solid var(--border-color);
                border-radius: 8px;
                color: var(--text-primary);
                cursor: pointer;
                display: none;
                align-items: center;
                gap: 0.5rem;
                z-index: 10;
                transition: all 0.2s ease;
            }

            #exit-button:hover {
                background: var(--bg-hover);
                color: var(--text-active);
            }
        </style>
    </head>
    <body>
        <?php echo $__env->make('components.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('components.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main class="main-content">
            <div id="dashboard-content">
                <h1 class="welcome-text">Welcome to LearnHub! Select a tool to get started.</h1>
                
                <div class="tools-grid">
                    <div class="tool-card" onclick="switchContent('notes')">
                        <i class="fas fa-sticky-note tool-icon"></i>
                        <h2 class="tool-title">Notes</h2>
                        <p class="tool-description">Create and manage your study notes</p>
                    </div>
                    
                    <div class="tool-card" onclick="switchContent('draw')">
                        <i class="fas fa-paint-brush tool-icon"></i>
                        <h2 class="tool-title">Draw</h2>
                        <p class="tool-description">Sketch and illustrate your ideas</p>
                    </div>
                    
                    <div class="tool-card" onclick="switchContent('calculator')">
                        <i class="fas fa-calculator tool-icon"></i>
                        <h2 class="tool-title">Calculator</h2>
                        <p class="tool-description">Solve mathematical problems</p>
                    </div>
                    
                    <div class="tool-card" onclick="switchContent('dictionary')">
                        <i class="fas fa-book tool-icon"></i>
                        <h2 class="tool-title">Dictionary</h2>
                        <p class="tool-description">Look up word definitions</p>
                    </div>
                </div>

                <div class="saved-notes">
                    <h2>Saved Notes</h2>
                    <div id="savedNotesList"></div>
                </div>
            </div>

            <!-- Include component content directly -->
            <?php echo $__env->make('components.notes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.draw', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.calculator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('components.dictionary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <button id="exit-button" onclick="showDashboard()">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </button>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Theme handling
                const themeToggle = document.getElementById('theme-toggle');
                const themeIcon = themeToggle.querySelector('i');
                
                // Load saved theme
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.body.setAttribute('data-theme', savedTheme);
                updateThemeIcon(savedTheme);
                
                themeToggle.addEventListener('click', () => {
                    const currentTheme = document.body.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    document.body.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeIcon(newTheme);
                });
                
                function updateThemeIcon(theme) {
                    themeIcon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
                }
                
                // Sidebar resize handling
                const sidebar = document.querySelector('.app-sidebar');
                const resizer = document.querySelector('.sidebar-resizer');
                let isResizing = false;
                
                // Load saved sidebar width
                const savedWidth = localStorage.getItem('sidebarWidth');
                const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (savedWidth && !isSidebarCollapsed) {
                    sidebar.style.width = savedWidth + 'px';
                }
                
                resizer.addEventListener('mousedown', initResize);
                
                function initResize(e) {
                    isResizing = true;
                    resizer.classList.add('dragging');
                    document.addEventListener('mousemove', resize);
                    document.addEventListener('mouseup', stopResize);
                }
                
                function resize(e) {
                    if (!isResizing) return;
                    
                    const minWidth = parseInt(getComputedStyle(document.documentElement)
                        .getPropertyValue('--sidebar-min-width'));
                    const maxWidth = parseInt(getComputedStyle(document.documentElement)
                        .getPropertyValue('--sidebar-max-width'));
                        
                    let newWidth = e.clientX;
                    newWidth = Math.max(minWidth, Math.min(maxWidth, newWidth));
                    
                    sidebar.style.width = newWidth + 'px';
                    localStorage.setItem('sidebarWidth', newWidth);
                }
                
                function stopResize() {
                    isResizing = false;
                    resizer.classList.remove('dragging');
                    document.removeEventListener('mousemove', resize);
                    document.removeEventListener('mouseup', stopResize);
                }
                
                // Mobile menu handling
                const menuToggle = document.querySelector('.menu-toggle');
                
                menuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
                
                // Handle sidebar width changes
                const mainContent = document.querySelector('.main-content');
                const header = document.querySelector('.app-header');
                
                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                            const sidebarWidth = sidebar.style.width;
                            if (sidebarWidth && !sidebar.classList.contains('collapsed')) {
                                mainContent.style.marginLeft = sidebarWidth;
                                header.style.left = sidebarWidth;
                            }
                        }
                    });
                });
                
                observer.observe(sidebar, { attributes: true });
            });

            function showDashboard() {
                document.querySelectorAll('.tool-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.getElementById('dashboard-content').style.display = 'block';
                document.getElementById('section-title').textContent = 'Dashboard';
                document.getElementById('exit-button').style.display = 'none';
                updateSavedNotesList();

                // Update active state in sidebar
                document.querySelectorAll('.nav-section li').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector('.nav-section li:first-child').classList.add('active');
            }

            function switchContent(contentType) {
                document.querySelectorAll('.tool-content, #dashboard-content').forEach(content => {
                    content.style.display = 'none';
                });

                const contentElement = document.getElementById(`${contentType}-content`);
                if (contentElement) {
                    contentElement.style.display = 'block';
                }

                const titles = {
                    notes: 'Notes',
                    draw: 'Drawing Board',
                    calculator: 'Calculator',
                    dictionary: 'Dictionary'
                };
                document.getElementById('section-title').textContent = titles[contentType];
                document.getElementById('exit-button').style.display = 'flex';

                // Update active state in sidebar
                document.querySelectorAll('.nav-section li').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector(`.nav-section a[onclick*="${contentType}"]`).parentElement.classList.add('active');

                // Close mobile menu
                document.querySelector('.app-sidebar').classList.remove('show');

                if (contentType === 'draw') {
                    initializeDrawingCanvas();
                } else if (contentType === 'notes') {
                    loadSavedNotes();
                } else if (contentType === 'dictionary') {
                    document.getElementById('word-search').focus();
                } else if (contentType === 'calculator') {
                    initializeCalculator();
                }
            }

            function updateSavedNotesList() {
                const savedNotes = JSON.parse(localStorage.getItem('learnhub_notes') || '[]');
                const savedNotesList = document.getElementById('savedNotesList');
                
                if (savedNotesList) {
                    savedNotesList.innerHTML = '';
                    
                    savedNotes.sort((a, b) => new Date(b.updatedAt) - new Date(a.updatedAt))
                        .forEach(note => {
                            const noteElement = document.createElement('div');
                            noteElement.className = 'saved-note-item';
                            noteElement.innerHTML = `
                                <div class="saved-note-title">${note.title || 'Untitled'}</div>
                                <div class="saved-note-date">${new Date(note.updatedAt).toLocaleDateString()}</div>
                            `;
                            
                            noteElement.addEventListener('click', () => {
                                switchContent('notes');
                                setTimeout(() => loadNote(note.id), 100);
                            });
                            
                            savedNotesList.appendChild(noteElement);
                        });

                    if (savedNotes.length === 0) {
                        savedNotesList.innerHTML = '<div class="text-muted text-center p-4">No saved notes yet</div>';
                    }
                }
            }
        </script>
    </body>
</html> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/welcome.blade.php ENDPATH**/ ?>