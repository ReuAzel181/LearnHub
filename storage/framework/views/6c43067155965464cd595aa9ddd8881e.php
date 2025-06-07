<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f6fa;
            min-height: 100vh;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 80px;
            background: white;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem 0;
        }

        .sidebar-link {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #2c3e50;
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }

        .sidebar-link:hover {
            background: #f8f9fa;
            color: #3498db;
        }

        .sidebar-link.active {
            background: #e3f2fd;
            color: #3498db;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .tool-content {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 1.8rem;
        }

        .search-bar {
            flex: 1;
            max-width: 600px;
            margin: 0 2rem;
        }

        .search-bar input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <nav class="sidebar">
            <a href="/notes" class="sidebar-link <?php echo e(request()->is('notes*') ? 'active' : ''); ?>" title="Notes">
                <i class="fas fa-sticky-note fa-lg"></i>
            </a>
            <a href="/calculator" class="sidebar-link <?php echo e(request()->is('calculator*') ? 'active' : ''); ?>" title="Calculator">
                <i class="fas fa-calculator fa-lg"></i>
            </a>
            <a href="/draw" class="sidebar-link <?php echo e(request()->is('draw*') ? 'active' : ''); ?>" title="Draw">
                <i class="fas fa-paint-brush fa-lg"></i>
            </a>
            <a href="/dictionary" class="sidebar-link <?php echo e(request()->is('dictionary*') ? 'active' : ''); ?>" title="Dictionary">
                <i class="fas fa-book fa-lg"></i>
            </a>
        </nav>

        <main class="main-content">
            <div class="header">
                <h1>LearnHub</h1>
                <div class="search-bar">
                    <input type="text" placeholder="Search for anything...">
                </div>
                <div class="user-actions">
                    <button class="theme-toggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    <button class="notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button class="settings">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/layouts/app.blade.php ENDPATH**/ ?>