/**
 * EMERGENCY NOTES DISPLAY SCRIPT
 * This script directly injects notes into the DOM, bypassing all other code
 */

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        console.log('EMERGENCY DIRECT NOTES INJECTION');
        
        const container = document.getElementById('savedNotesList');
        if (!container) {
            console.error('EMERGENCY: Cannot find savedNotesList container');
            return;
        }
        
        // Create a direct note
        const demoNotes = [
            {
                title: 'Welcome to LearnHub',
                content: 'This is a sample note injected directly into the DOM.',
                date: new Date().toLocaleString()
            },
            {
                title: 'Getting Started with Notes',
                content: 'Click on any note to edit it, or use the Notes tool to create new notes.',
                date: new Date().toLocaleString()
            }
        ];
        
        // Inject notes directly
        let notesHTML = '';
        demoNotes.forEach(note => {
            notesHTML += `
                <div class="saved-note-item" style="border: 2px solid #4c9be8;">
                    <div class="saved-note-title">${note.title}</div>
                    <div class="saved-note-preview">${note.content}</div>
                    <div class="saved-note-date">${note.date}</div>
                </div>
            `;
        });
        
        // Add a message to indicate these are emergency notes
        notesHTML += `
            <div class="saved-note-item" style="background-color: #fff3cd; border: 1px solid #ffeeba;">
                <div class="saved-note-title">Note Display Fix</div>
                <div class="saved-note-preview">These notes are being displayed via the emergency direct injection method.</div>
                <div class="saved-note-date">${new Date().toLocaleString()}</div>
            </div>
        `;
        
        // Set content
        container.innerHTML = notesHTML;
        console.log('EMERGENCY: Direct notes injection complete');
        
    }, 3000); // Wait 3 seconds to ensure page is fully loaded
}); 