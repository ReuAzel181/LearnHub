// Import our custom CSS
import '../css/app.css';

// Import all of Alpine.js
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

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