<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LearnHub</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            :root {
                --primary-color: #3498db;
                --primary-dark: #2980b9;
                --success-color: #2ecc71;
                --warning-color: #f1c40f;
                --danger-color: #e74c3c;
                --text-dark: #2c3e50;
                --text-light: #95a5a6;
                --bg-light: #f8f9fa;
                --border-color: #eee;
                
                --spacing-xs: 0.25rem;
                --spacing-sm: 0.5rem;
                --spacing-md: 1rem;
                --spacing-lg: 1.5rem;
                --spacing-xl: 2rem;

                --radius-sm: 8px;
                --radius-md: 12px;
                --radius-lg: 16px;

                --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
                --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            body {
                background: var(--bg-light);
                color: var(--text-dark);
                line-height: 1.5;
            }

            /* Layout */
            .container {
                display: flex;
                margin-top: 64px;
                min-height: calc(100vh - 64px);
            }

            /* Sidebar */
            .sidebar {
                width: 280px;
                background: white;
                border-right: 1px solid var(--border-color);
                padding: var(--spacing-lg);
                height: calc(100vh - 64px);
                position: fixed;
                overflow-y: auto;
            }

            .section-title {
                font-size: 0.9rem;
                font-weight: 600;
                color: var(--text-light);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: var(--spacing-md);
            }

            /* Main Content */
            .main-content {
                flex: 1;
                padding: var(--spacing-xl);
                margin-left: 280px;
            }

            .content-header {
                margin-bottom: var(--spacing-xl);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            #section-title {
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--text-dark);
            }

            .exit-button {
                padding: var(--spacing-sm) var(--spacing-lg);
                border-radius: var(--radius-sm);
                border: 1px solid var(--border-color);
                background: white;
                color: var(--text-dark);
                cursor: pointer;
                display: none;
                align-items: center;
                gap: var(--spacing-sm);
                transition: all 0.2s;
            }

            .exit-button:hover {
                background: var(--bg-light);
            }

            /* Dashboard Cards */
            .action-buttons {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: var(--spacing-lg);
            }

            .action-button {
                background: white;
                border-radius: var(--radius-lg);
                padding: var(--spacing-xl);
                cursor: pointer;
                transition: all 0.3s;
                border: 1px solid var(--border-color);
                text-align: center;
            }

            .action-button:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-md);
            }

            .button-content {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: var(--spacing-md);
            }

            .button-content i {
                font-size: 2rem;
                color: var(--primary-color);
            }

            .button-text {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--text-dark);
            }

            .button-description {
                font-size: 0.9rem;
                color: var(--text-light);
            }

            /* Tool Content */
            .tool-content {
                display: none;
            }

            @media (max-width: 768px) {
                .sidebar {
                    display: none;
                }
                
                .main-content {
                    margin-left: 0;
                }
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        @include('components.header')

        <!-- Main Container -->
        <div class="container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="recent">
                    <h2 class="section-title">Recent</h2>
                    <div id="recent-items"></div>
                </div>

                <div class="reminders">
                    <h2 class="section-title">Reminders</h2>
                    <div id="reminder-items"></div>
                </div>

                <div class="active-users">
                    <h2 class="section-title">Active Users</h2>
                    <div id="active-users-list"></div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-header">
                    <div id="section-title">Dashboard</div>
                    <button id="exit-button" class="exit-button" onclick="showDashboard()">
                        <i class="fas fa-times"></i> Exit
                    </button>
                </div>

                <!-- Dashboard Content -->
                <div id="dashboard-content">
                    <div class="action-buttons">
                        <div class="action-button" onclick="switchContent('notes')">
                            <div class="button-content">
                                <i class="fas fa-sticky-note"></i>
                                <span class="button-text">Notes</span>
                                <span class="button-description">Create and manage your study notes</span>
                            </div>
                        </div>
                        <div class="action-button" onclick="switchContent('draw')">
                            <div class="button-content">
                                <i class="fas fa-paint-brush"></i>
                                <span class="button-text">Draw</span>
                                <span class="button-description">Sketch and illustrate your ideas</span>
                            </div>
                        </div>
                        <div class="action-button" onclick="switchContent('calculator')">
                            <div class="button-content">
                                <i class="fas fa-calculator"></i>
                                <span class="button-text">Calculator</span>
                                <span class="button-description">Solve mathematical problems</span>
                            </div>
                        </div>
                        <div class="action-button" onclick="switchContent('dictionary')">
                            <div class="button-content">
                                <i class="fas fa-book"></i>
                                <span class="button-text">Dictionary</span>
                                <span class="button-description">Look up word definitions</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tool Components -->
                @include('components.notes')
                @include('components.draw')
                @include('components.calculator')
                @include('components.dictionary')
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showDashboard();
            });

            function showDashboard() {
                document.querySelectorAll('.tool-content').forEach(content => {
                    content.style.display = 'none';
                });
                document.getElementById('dashboard-content').style.display = 'block';
                document.getElementById('section-title').textContent = 'Dashboard';
                document.getElementById('exit-button').style.display = 'none';
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

                if (contentType === 'draw') {
                    initializeDrawingCanvas();
                } else if (contentType === 'notes') {
                    loadSavedNotes();
                } else if (contentType === 'dictionary') {
                    document.getElementById('word-search').focus();
                }
            }

            // Your existing JavaScript functions for tools...
        </script>
    </body>
</html> 