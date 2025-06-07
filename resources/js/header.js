// Debug flag
const DEBUG = true;

// Logger function
function log(type, message, data = null) {
    if (DEBUG) {
        const logData = {
            timestamp: new Date().toISOString(),
            type,
            message,
            data
        };
        console.log(JSON.stringify(logData, null, 2));
    }
}

// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    try {
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('aside');
        const mainContent = document.querySelector('main');
        
        if (!menuToggle || !sidebar || !mainContent) {
            throw new Error('Required DOM elements not found for sidebar toggle');
        }
        
        menuToggle.addEventListener('click', () => {
            log('info', 'Sidebar toggle clicked');
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            log('info', 'Sidebar state saved', { isCollapsed });
        });
        
        // Restore sidebar state on page load
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            log('info', 'Sidebar state restored', { isCollapsed });
        }
    } catch (error) {
        log('error', 'Sidebar initialization failed', { error: error.message });
    }
});

// Search Functionality
try {
    const searchInput = document.querySelector('.search-input');
    const searchResults = document.createElement('div');
    searchResults.classList.add('search-results');
    
    if (!searchInput) {
        throw new Error('Search input element not found');
    }
    
    searchInput.parentNode.appendChild(searchResults);

    searchInput.addEventListener('input', debounce(async (e) => {
        const query = e.target.value.trim();
        log('info', 'Search input detected', { query });
        
        if (query.length < 2) {
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            log('info', 'Search results received', { resultsCount: data.results.length });
            
            if (data.results.length > 0) {
                searchResults.innerHTML = data.results.map(result => `
                    <a href="${result.url}" class="search-result-item">
                        <i class="fas ${result.icon}"></i>
                        <div class="search-result-content">
                            <div class="search-result-title">${result.title}</div>
                            <div class="search-result-description">${result.description}</div>
                        </div>
                    </a>
                `).join('');
                searchResults.style.display = 'block';
            } else {
                searchResults.innerHTML = '<div class="search-no-results">No results found</div>';
                searchResults.style.display = 'block';
            }
        } catch (error) {
            log('error', 'Search request failed', { error: error.message });
            searchResults.innerHTML = '<div class="search-error">An error occurred while searching</div>';
            searchResults.style.display = 'block';
        }
    }, 300));
} catch (error) {
    log('error', 'Search initialization failed', { error: error.message });
}

// Close search results when clicking outside
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Notifications
try {
    const notificationBtn = document.querySelector('.header-icon:first-of-type');
    const notificationDropdown = document.createElement('div');
    notificationDropdown.classList.add('notification-dropdown');
    
    if (!notificationBtn) {
        throw new Error('Notification button not found');
    }
    
    notificationDropdown.innerHTML = `
        <div class="dropdown-header">
            <h3>Notifications</h3>
            <button class="mark-all-read">Mark all as read</button>
        </div>
        <div class="notification-list"></div>
    `;

    notificationBtn.after(notificationDropdown);

    notificationBtn.addEventListener('click', async () => {
        log('info', 'Notification button clicked');
        notificationDropdown.classList.toggle('show');
        
        if (notificationDropdown.classList.contains('show')) {
            try {
                const response = await fetch('/api/notifications');
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                log('info', 'Notifications received', { count: data.notifications.length });
                
                const notificationList = notificationDropdown.querySelector('.notification-list');
                notificationList.innerHTML = data.notifications.map(notification => `
                    <div class="notification-item ${notification.read ? 'read' : ''}">
                        <div class="notification-icon">
                            <i class="fas ${notification.icon}"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-text">${notification.message}</div>
                            <div class="notification-time">${notification.time}</div>
                        </div>
                        ${!notification.read ? '<div class="notification-dot"></div>' : ''}
                    </div>
                `).join('');
            } catch (error) {
                log('error', 'Failed to fetch notifications', { error: error.message });
                notificationDropdown.querySelector('.notification-list').innerHTML = 
                    '<div class="notification-error">Failed to load notifications</div>';
            }
        }
    });

    // Mark all as read functionality
    const markAllReadBtn = notificationDropdown.querySelector('.mark-all-read');
    markAllReadBtn.addEventListener('click', async (e) => {
        e.stopPropagation();
        log('info', 'Mark all as read clicked');
        
        try {
            const response = await fetch('/api/notifications/mark-read', {
                method: 'POST'
            });
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            log('info', 'Notifications marked as read', { success: data.success });
            
            // Update UI to reflect all notifications as read
            document.querySelectorAll('.notification-item').forEach(item => {
                item.classList.add('read');
                const dot = item.querySelector('.notification-dot');
                if (dot) dot.remove();
            });
        } catch (error) {
            log('error', 'Failed to mark notifications as read', { error: error.message });
        }
    });
} catch (error) {
    log('error', 'Notifications initialization failed', { error: error.message });
}

// Settings Dropdown
try {
    const settingsBtn = document.querySelector('.header-icon:nth-of-type(2)');
    const settingsDropdown = document.createElement('div');
    
    if (!settingsBtn) {
        throw new Error('Settings button not found');
    }
    
    settingsDropdown.classList.add('settings-dropdown');
    settingsDropdown.innerHTML = `
        <div class="dropdown-header">
            <h3>Settings</h3>
        </div>
        <div class="settings-list">
            <a href="/profile" class="settings-item">
                <i class="fas fa-user"></i>
                <span>Profile Settings</span>
            </a>
            <a href="/preferences" class="settings-item">
                <i class="fas fa-cog"></i>
                <span>Preferences</span>
            </a>
            <a href="/appearance" class="settings-item">
                <i class="fas fa-paint-brush"></i>
                <span>Appearance</span>
            </a>
            <div class="settings-divider"></div>
            <a href="/help" class="settings-item">
                <i class="fas fa-question-circle"></i>
                <span>Help & Support</span>
            </a>
            <form action="/logout" method="POST" class="settings-item logout">
                <input type="hidden" name="_token" value="${window.csrf_token}">
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    `;

    settingsBtn.after(settingsDropdown);

    settingsBtn.addEventListener('click', () => {
        log('info', 'Settings button clicked');
        settingsDropdown.classList.toggle('show');
    });
} catch (error) {
    log('error', 'Settings initialization failed', { error: error.message });
}

// User Profile Dropdown
try {
    const userProfileBtn = document.querySelector('.user-avatar');
    const userDropdown = document.createElement('div');
    
    if (!userProfileBtn) {
        throw new Error('User profile button not found');
    }
    
    userDropdown.classList.add('user-dropdown');
    userDropdown.innerHTML = `
        <div class="user-info">
            <div class="user-avatar-large">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <h4>${userProfileBtn.dataset.username || 'Guest'}</h4>
                <p>${userProfileBtn.dataset.email || 'guest@example.com'}</p>
            </div>
        </div>
        <div class="user-actions">
            <a href="/profile" class="user-action-item">
                <i class="fas fa-user-circle"></i>
                <span>View Profile</span>
            </a>
            <a href="/dashboard" class="user-action-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <form action="/logout" method="POST" class="user-action-item logout">
                <input type="hidden" name="_token" value="${window.csrf_token}">
                <button type="submit">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    `;

    userProfileBtn.after(userDropdown);

    userProfileBtn.addEventListener('click', () => {
        log('info', 'User profile button clicked');
        userDropdown.classList.toggle('show');
    });
} catch (error) {
    log('error', 'User profile initialization failed', { error: error.message });
}

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    try {
        const notificationBtn = document.querySelector('.header-icon:first-of-type');
        const notificationDropdown = document.querySelector('.notification-dropdown');
        const settingsBtn = document.querySelector('.header-icon:nth-of-type(2)');
        const settingsDropdown = document.querySelector('.settings-dropdown');
        const userProfileBtn = document.querySelector('.user-avatar');
        const userDropdown = document.querySelector('.user-dropdown');

        if (!e.target.closest('.header-icon:first-of-type') && !e.target.closest('.notification-dropdown')) {
            notificationDropdown?.classList.remove('show');
        }
        if (!e.target.closest('.header-icon:nth-of-type(2)') && !e.target.closest('.settings-dropdown')) {
            settingsDropdown?.classList.remove('show');
        }
        if (!e.target.closest('.user-avatar') && !e.target.closest('.user-dropdown')) {
            userDropdown?.classList.remove('show');
        }
    } catch (error) {
        log('error', 'Error in click outside handler', { error: error.message });
    }
});

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
} 