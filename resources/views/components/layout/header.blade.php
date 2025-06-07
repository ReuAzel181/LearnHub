<header class="app-header">
    <div class="header-left">
        <button class="menu-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">LearnHub</div>
    </div>
    <div class="search-bar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search for anything...">
    </div>
    <div class="header-right">
        <button class="theme-toggle" id="theme-toggle" title="Toggle dark mode">
            <i class="fas fa-moon"></i>
        </button>
        <button class="notification-btn" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">2</span>
        </button>
        <button class="settings-btn" title="Settings">
            <i class="fas fa-cog"></i>
        </button>
        <button class="profile-btn" title="Profile">
            <i class="fas fa-user-circle"></i>
        </button>
    </div>
</header>

<style>
.app-header {
    position: fixed;
    top: 0;
    left: var(--sidebar-width);
    right: 0;
    height: var(--header-height);
    background: var(--bg-card);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    border-bottom: 1px solid var(--border-color);
    z-index: 100;
    transition: left var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--text-primary);
    cursor: pointer;
    padding: 0.5rem;
}

.logo {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.search-bar {
    flex: 1;
    max-width: 600px;
    position: relative;
    margin: 0 2rem;
}

.search-bar input {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.9rem;
    background: var(--bg-main);
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.search-bar input:focus {
    outline: none;
    border-color: var(--text-active);
    background: var(--bg-card);
}

.search-bar i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
}

.header-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-right button {
    background: none;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    color: var(--text-primary);
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-right button:hover {
    background: var(--bg-hover);
    color: var(--text-active);
}

.header-right button i {
    font-size: 1.25rem;
}

.notification-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: #e74c3c;
    color: white;
    font-size: 0.7rem;
    padding: 0.1rem 0.4rem;
    border-radius: 10px;
}

body[data-theme="dark"] .app-header {
    border-bottom: 1px solid var(--border-color);
}

@media (max-width: 768px) {
    .app-header {
        left: 0;
        padding: 0 1rem;
    }

    .menu-toggle {
        display: block;
    }

    .search-bar {
        margin: 0 1rem;
    }
}
</style> 