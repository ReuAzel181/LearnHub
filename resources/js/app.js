// Import our custom CSS
import '../css/app.css';

// Import all of Alpine.js
import Alpine from 'alpinejs';

import './bootstrap';
import './header';

window.Alpine = Alpine;
Alpine.start();

// Add CSRF token to all AJAX requests
window.csrf_token = document.querySelector('meta[name="csrf-token"]').content;

// Add CSRF token to all fetch requests
let originalFetch = window.fetch;
window.fetch = function() {
    let args = Array.prototype.slice.call(arguments);
    if (args[1] && args[1].method && ['POST', 'PUT', 'DELETE', 'PATCH'].includes(args[1].method.toUpperCase())) {
        if (!args[1].headers) {
            args[1].headers = {};
        }
        args[1].headers['X-CSRF-TOKEN'] = window.csrf_token;
        args[1].headers['Accept'] = 'application/json';
    }
    return originalFetch.apply(this, args);
};

// Debug flag for development
window.DEBUG = true;

// Global error handler
window.addEventListener('error', function(event) {
    if (window.DEBUG) {
        console.error('Global error:', {
            message: event.message,
            filename: event.filename,
            lineno: event.lineno,
            colno: event.colno,
            error: event.error
        });
    }
});

// Notes functionality
document.addEventListener('DOMContentLoaded', function() {
    // Note actions (pin, edit, more)
    const noteActionButtons = document.querySelectorAll('.note-action-btn');
    noteActionButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Implement actions based on button clicked
            console.log('Note action clicked:', this.dataset.action);
        });
    });

    // Sort notes
    const sortButton = document.querySelector('.sort-notes-btn');
    if (sortButton) {
        sortButton.addEventListener('click', function() {
            console.log('Sort notes clicked');
        });
    }

    // View toggle
    const viewToggleButton = document.querySelector('.view-toggle-btn');
    if (viewToggleButton) {
        viewToggleButton.addEventListener('click', function() {
            console.log('View toggle clicked');
        });
    }
}); 