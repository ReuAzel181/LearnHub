<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LearnHub</title>
        <!-- Font Awesome for consistent icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
            :root {
                /* Color Variables */
                --primary-color: #2196F3;
                --success-color: #4CAF50;
                --warning-color: #FFC107;
                --danger-color: #F44336;
                --gray-100: #f8f9fa;
                --gray-200: #e9ecef;
                --gray-300: #dee2e6;
                --gray-400: #ced4da;
                --gray-500: #adb5bd;
                --gray-600: #6c757d;
                --gray-700: #495057;
                --gray-800: #343a40;
                --gray-900: #212529;
                
                /* Spacing Variables */
                --spacing-xs: 0.25rem;
                --spacing-sm: 0.5rem;
                --spacing-md: 1rem;
                --spacing-lg: 1.5rem;
                --spacing-xl: 2rem;

                /* Border Radius */
                --border-radius-sm: 4px;
                --border-radius-md: 8px;
                --border-radius-lg: 12px;

                /* Shadows */
                --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
                --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
                --shadow-lg: 0 10px 15px rgba(0,0,0,0.1);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            body {
                background-color: var(--gray-100);
                color: var(--gray-800);
                line-height: 1.5;
            }

            /* Navigation */
            .nav {
                display: flex;
                align-items: center;
                padding: var(--spacing-md) var(--spacing-xl);
                background: white;
                border-bottom: 1px solid var(--gray-200);
                box-shadow: var(--shadow-sm);
                height: 64px;
            }

            .menu-toggle {
                font-size: 1.25rem;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: var(--border-radius-md);
                margin-right: var(--spacing-md);
                cursor: pointer;
                border: none;
                background: transparent;
                color: var(--gray-700);
                transition: background-color 0.2s;
            }

            .menu-toggle:hover {
                background-color: var(--gray-100);
            }

            .logo {
                font-size: 1.5rem;
                font-weight: bold;
                margin-right: var(--spacing-xl);
                color: var(--gray-900);
            }

            .search-container {
                flex-grow: 1;
                margin-right: var(--spacing-xl);
                position: relative;
            }

            .search-bar {
                width: 100%;
                padding: var(--spacing-sm) var(--spacing-xl);
                padding-left: 2.5rem;
                border-radius: 50px;
                border: 1px solid var(--gray-300);
                background: var(--gray-100);
                font-size: 0.95rem;
                transition: all 0.2s;
            }

            .search-bar:focus {
                outline: none;
                border-color: var(--primary-color);
                background: white;
                box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
            }

            .search-icon {
                position: absolute;
                left: var(--spacing-md);
                top: 50%;
                transform: translateY(-50%);
                color: var(--gray-500);
            }

            .nav-icons {
                display: flex;
                gap: var(--spacing-md);
            }

            .nav-icon {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: var(--border-radius-md);
                cursor: pointer;
                color: var(--gray-700);
                transition: all 0.2s;
            }

            .nav-icon:hover {
                background-color: var(--gray-100);
                color: var(--gray-900);
            }

            /* Main Layout */
            .container {
                display: flex;
                height: calc(100vh - 64px);
            }

            /* Sidebar */
            .sidebar {
                width: 280px;
                background: white;
                padding: var(--spacing-lg);
                border-right: 1px solid var(--gray-200);
                overflow-y: auto;
            }

            .section-title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--gray-700);
                margin-bottom: var(--spacing-md);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .recent-item, .reminder-item {
                padding: var(--spacing-md);
                margin-bottom: var(--spacing-sm);
                background: var(--gray-100);
                border-radius: var(--border-radius-md);
                cursor: pointer;
                transition: all 0.2s;
            }

            .recent-item:hover, .reminder-item:hover {
                background: var(--gray-200);
            }

            .active-users {
                margin-top: var(--spacing-xl);
            }

            .user-avatars {
                display: flex;
                gap: var(--spacing-sm);
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--gray-200);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                color: var(--gray-600);
            }

            .user-avatar.online::after {
                content: '';
                position: absolute;
                width: 10px;
                height: 10px;
                background: var(--success-color);
                border-radius: 50%;
                bottom: 0;
                right: 0;
                border: 2px solid white;
            }

            /* Main Content */
            .main-content {
                flex-grow: 1;
                padding: var(--spacing-xl);
                overflow-y: auto;
            }

            /* Action Buttons */
            .action-buttons {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: var(--spacing-md);
                margin-bottom: var(--spacing-xl);
            }

            .action-button {
                aspect-ratio: 2/1;
                border-radius: var(--border-radius-lg);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s;
                font-size: 1.5rem;
                box-shadow: var(--shadow-md);
            }

            .action-button:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }

            .action-button.documents { background: #FFD54F; }
            .action-button.edit { background: #81C784; }
            .action-button.calculator { background: #64B5F6; }
            .action-button.chart { background: #FF8A65; }

            /* Notes Section */
            .notes-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: var(--spacing-lg);
            }

            .notes-controls {
                display: flex;
                gap: var(--spacing-sm);
            }

            .control-button {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: var(--border-radius-md);
                cursor: pointer;
                color: var(--gray-600);
                background: white;
                border: 1px solid var(--gray-200);
                transition: all 0.2s;
            }

            .control-button:hover {
                background: var(--gray-100);
                color: var(--gray-900);
            }

            .notes-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: var(--spacing-md);
            }

            .note-card {
                background: white;
                padding: var(--spacing-lg);
                border-radius: var(--border-radius-lg);
                box-shadow: var(--shadow-md);
                transition: all 0.2s;
            }

            .note-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }

            .note-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: var(--spacing-md);
            }

            .note-date {
                color: var(--primary-color);
                font-weight: 500;
            }

            .note-actions {
                display: flex;
                gap: var(--spacing-xs);
            }

            .note-action {
                padding: var(--spacing-xs);
                cursor: pointer;
                color: var(--gray-600);
                border-radius: var(--border-radius-sm);
                transition: all 0.2s;
            }

            .note-action:hover {
                color: var(--gray-900);
                background: var(--gray-100);
            }

            .note-content {
                color: var(--gray-700);
                font-size: 0.95rem;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .sidebar {
                    display: none;
                }
                
                .action-buttons {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 480px) {
                .nav-icons {
                    display: none;
                }
                
                .search-container {
                    margin-right: 0;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="nav">
            <button class="menu-toggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">LOGO</div>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="search" class="search-bar" placeholder="Search">
            </div>
            <div class="nav-icons">
                <div class="nav-icon">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="recent">
                    <h2 class="section-title">
                        Recent
                        <i class="fas fa-clock"></i>
                    </h2>
                    <div class="recent-item">
                        <i class="fas fa-file-alt"></i>
                        January 1
                    </div>
                    <div class="recent-item">
                        <i class="fas fa-file-alt"></i>
                        January 1
                    </div>
                    <div class="recent-item">
                        <i class="fas fa-file-alt"></i>
                        January 1
                    </div>
                </div>

                <div class="reminders">
                    <h2 class="section-title">
                        Reminders
                        <i class="fas fa-bell"></i>
                    </h2>
                    <div class="reminder-item">
                        <i class="fas fa-calendar"></i>
                        No reminders
                    </div>
                </div>

                <div class="active-users">
                    <h2 class="section-title">
                        Active
                        <i class="fas fa-users"></i>
                    </h2>
                    <div class="user-avatars">
                        <div class="user-avatar online">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-avatar online">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-avatar online">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="main-content">
                <div class="action-buttons">
                    <div class="action-button documents">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="action-button edit">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="action-button calculator">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="action-button chart">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>

                <div class="notes-section">
                    <div class="notes-header">
                        <h2 class="section-title">Notes</h2>
                        <div class="notes-controls">
                            <button class="control-button">
                                <i class="fas fa-sort"></i>
                            </button>
                            <button class="control-button">
                                <i class="fas fa-th-large"></i>
                            </button>
                        </div>
                    </div>

                    <div class="notes-grid">
                        <div class="note-card">
                            <div class="note-header">
                                <div class="note-date">January 1</div>
                                <div class="note-actions">
                                    <span class="note-action"><i class="fas fa-thumbtack"></i></span>
                                    <span class="note-action"><i class="fas fa-edit"></i></span>
                                    <span class="note-action"><i class="fas fa-ellipsis-v"></i></span>
                                </div>
                            </div>
                            <div class="note-content">Lorem ipsum dolor sit amet</div>
                        </div>

                        <div class="note-card">
                            <div class="note-header">
                                <div class="note-date">January 1</div>
                                <div class="note-actions">
                                    <span class="note-action"><i class="fas fa-thumbtack"></i></span>
                                    <span class="note-action"><i class="fas fa-edit"></i></span>
                                    <span class="note-action"><i class="fas fa-ellipsis-v"></i></span>
                                </div>
                            </div>
                            <div class="note-content">Lorem ipsum dolor sit amet</div>
                        </div>

                        <div class="note-card">
                            <div class="note-header">
                                <div class="note-date">January 1</div>
                                <div class="note-actions">
                                    <span class="note-action"><i class="fas fa-thumbtack"></i></span>
                                    <span class="note-action"><i class="fas fa-edit"></i></span>
                                    <span class="note-action"><i class="fas fa-ellipsis-v"></i></span>
                                </div>
                            </div>
                            <div class="note-content">Lorem ipsum dolor sit amet</div>
                        </div>

                        <div class="note-card">
                            <div class="note-header">
                                <div class="note-date">January 1</div>
                                <div class="note-actions">
                                    <span class="note-action"><i class="fas fa-thumbtack"></i></span>
                                    <span class="note-action"><i class="fas fa-edit"></i></span>
                                    <span class="note-action"><i class="fas fa-ellipsis-v"></i></span>
                                </div>
                            </div>
                            <div class="note-content">Lorem ipsum dolor sit amet</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/welcome.blade.php ENDPATH**/ ?>