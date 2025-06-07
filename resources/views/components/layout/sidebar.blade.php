<aside class="app-sidebar">
    <!-- Background overlay for collapse detection -->
    <div class="sidebar-background"></div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <h3>Menu</h3>
            <ul>
                <li class="active">
                    <a href="#" onclick="showDashboard()">
                        <i class="fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="switchContent('notes')">
                        <i class="fas fa-sticky-note"></i>
                        <span class="nav-text">Notes</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="switchContent('draw')">
                        <i class="fas fa-paint-brush"></i>
                        <span class="nav-text">Draw</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="switchContent('calculator')">
                        <i class="fas fa-calculator"></i>
                        <span class="nav-text">Calculator</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="switchContent('dictionary')">
                        <i class="fas fa-book"></i>
                        <span class="nav-text">Dictionary</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="nav-section">
            <h3>Recent</h3>
            <ul id="recent-items">
                <!-- Recent items will be populated dynamically -->
            </ul>
        </div>

        <div class="nav-section">
            <h3>Categories</h3>
            <ul id="categoryList">
                <!-- Categories will be populated dynamically -->
            </ul>
            <button class="add-category-btn" onclick="showAddCategoryModal()">
                <i class="fas fa-plus"></i> <span class="nav-text">Add Category</span>
            </button>
        </div>
    </nav>

    <!-- Resizer handles -->
    <div class="sidebar-resizer sidebar-resizer-x"></div>
    <div class="sidebar-resizer sidebar-resizer-y"></div>
    <div class="sidebar-resizer sidebar-resizer-corner"></div>
</aside>

<style>
:root {
    --sidebar-width: 200px;
    --sidebar-min-width: 200px;
    --sidebar-max-width: 500px;
    --sidebar-collapsed-width: 70px;
    --sidebar-min-height: 400px;
    --sidebar-max-height: 100vh;
    --header-height: 60px;
    --transition-speed: 0.2s;
}

.app-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--bg-sidebar);
    border-right: 1px solid var(--border-color);
    overflow: hidden;
    z-index: 1000;
    will-change: width;
    transition: width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
}

.app-sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

.app-sidebar.collapsed .nav-text,
.app-sidebar.collapsed .nav-section h3 {
    opacity: 0;
    visibility: hidden;
}

.app-sidebar .nav-text,
.app-sidebar .nav-section h3 {
    opacity: 1;
    visibility: visible;
    transition: opacity var(--transition-speed) ease,
                visibility var(--transition-speed) ease;
}

.sidebar-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    cursor: pointer;
}

.sidebar-nav {
    position: relative;
    padding: 1.5rem 1rem;
    z-index: 1;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Resizer styles */
.sidebar-resizer {
    position: absolute;
    z-index: 1001;
    transition: background-color 0.2s ease;
}

.sidebar-resizer:hover,
.sidebar-resizer.dragging {
    background: var(--text-active);
    opacity: 0.2;
}

.sidebar-resizer-x {
    right: -3px;
    top: 0;
    width: 6px;
    height: 100%;
    cursor: ew-resize;
}

.sidebar-resizer-y {
    bottom: -3px;
    left: 0;
    height: 6px;
    width: 100%;
    cursor: ns-resize;
}

.sidebar-resizer-corner {
    right: -3px;
    bottom: -3px;
    width: 12px;
    height: 12px;
    cursor: nwse-resize;
}

/* Rest of the existing styles */
.nav-section {
    margin-bottom: 2rem;
    pointer-events: auto;
}

.nav-section h3 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    padding: 0 0.5rem;
    white-space: nowrap;
}

.nav-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-section li {
    margin-bottom: 0.25rem;
}

.nav-section a {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    color: var(--text-primary);
    text-decoration: none;
    border-radius: 8px;
    transition: all var(--transition-speed);
    white-space: nowrap;
    position: relative;
    z-index: 2;
}

.nav-section a:hover {
    background: var(--bg-hover);
}

.nav-section li.active a {
    background: var(--bg-active);
    color: var(--text-active);
}

.nav-section a i {
    width: 1.5rem;
    font-size: 1.1rem;
    margin-right: 0.75rem;
    text-align: center;
}

.collapsed .nav-text {
    display: none;
}

.collapsed .nav-section h3 {
    visibility: hidden;
}

.add-category-btn {
    width: 100%;
    padding: 0.75rem;
    margin-top: 0.5rem;
    border: 1px dashed var(--border-color);
    border-radius: 8px;
    background: none;
    color: var(--text-primary);
    cursor: pointer;
    transition: all var(--transition-speed);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    white-space: nowrap;
    position: relative;
    z-index: 2;
}

.add-category-btn:hover {
    border-color: var(--text-active);
    color: var(--text-active);
}

@media (max-width: 768px) {
    .app-sidebar {
        transform: translateX(-100%);
        transition: transform var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1), 
                    width var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1),
                    height var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
    }

    .app-sidebar.show {
        transform: translateX(0);
        width: var(--sidebar-min-width) !important;
        height: 100vh !important;
    }

    .app-sidebar.show .nav-text {
        display: inline;
    }

    .app-sidebar.show .nav-section h3 {
        visibility: visible;
    }

    .sidebar-resizer {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.app-sidebar');
    const sidebarNav = document.querySelector('.sidebar-nav');
    const mainContent = document.querySelector('.main-content');
    
    function updateLayout(width) {
        document.documentElement.style.setProperty('--sidebar-width', `${width}px`);
        if (mainContent) {
            mainContent.style.left = `${width}px`;
        }
    }
    
    // Load saved dimensions
    const savedWidth = localStorage.getItem('sidebarWidth');
    const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Initialize sidebar state
    if (isSidebarCollapsed) {
        sidebar.classList.add('collapsed');
        document.body.classList.add('sidebar-collapsed');
        updateLayout(70); // collapsed width
    } else if (savedWidth) {
        const width = Math.max(200, Math.min(500, parseInt(savedWidth)));
        updateLayout(width);
        sidebar.style.width = `${width}px`;
    }

    function toggleSidebar() {
        const isCollapsing = !sidebar.classList.contains('collapsed');
        
        if (isCollapsing) {
            const currentWidth = parseInt(getComputedStyle(sidebar).width);
            localStorage.setItem('sidebarWidth', currentWidth);
            updateLayout(70); // collapsed width
            requestAnimationFrame(() => {
                sidebar.style.width = '70px';
                mainContent.style.left = '70px';
            });
        } else {
            const savedWidth = localStorage.getItem('sidebarWidth') || '200';
            const width = Math.max(200, Math.min(500, parseInt(savedWidth)));
            updateLayout(width);
            requestAnimationFrame(() => {
                sidebar.style.width = `${width}px`;
                mainContent.style.left = `${width}px`;
            });
        }
        
        requestAnimationFrame(() => {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }

    // Handle collapse on whitespace click
    sidebarNav.addEventListener('click', (e) => {
        if (e.target === sidebarNav || 
            (e.target.closest('.nav-section') && 
             !e.target.closest('a') && 
             !e.target.closest('button'))) {
            toggleSidebar();
        }
    });

    // Resize handling
    const resizerX = document.querySelector('.sidebar-resizer-x');
    let isResizing = false;
    let startX = 0;
    let startWidth = 0;

    function initResize(e) {
        if (sidebar.classList.contains('collapsed')) return;
        
        isResizing = true;
        startX = e.clientX;
        startWidth = parseInt(getComputedStyle(sidebar).width, 10);
        
        resizerX.classList.add('dragging');
        document.addEventListener('mousemove', resize);
        document.addEventListener('mouseup', stopResize);
        sidebar.style.transition = 'none';
        mainContent.style.transition = 'none';
        e.preventDefault();
    }

    function resize(e) {
        if (!isResizing) return;
        
        const width = startWidth + (e.clientX - startX);
        const newWidth = Math.max(200, Math.min(500, width));
        
        requestAnimationFrame(() => {
            updateLayout(newWidth);
            sidebar.style.width = `${newWidth}px`;
        });
        localStorage.setItem('sidebarWidth', newWidth);
    }

    function stopResize() {
        isResizing = false;
        resizerX.classList.remove('dragging');
        document.removeEventListener('mousemove', resize);
        document.removeEventListener('mouseup', stopResize);
        sidebar.style.transition = '';
        mainContent.style.transition = '';
    }

    resizerX.addEventListener('mousedown', initResize);
});
</script> 