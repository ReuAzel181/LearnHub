/**
 * Debug Helper
 * This file provides debugging tools to identify why notes aren't displaying
 */

console.log('====== DEBUG HELPER LOADED ======');

// Check storage contents directly on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        console.log('======= STORAGE DEBUG =======');
        
        // 1. Check all localStorage keys
        console.log('All localStorage keys:');
        Object.keys(localStorage).forEach(function(key) {
            console.log(`- ${key}: ${localStorage.getItem(key).substring(0, 50)}...`);
        });
        
        // 2. Check NoteStorage specifically
        if (typeof NoteStorage !== 'undefined') {
            console.log('\nNoteStorage module:');
            console.log('- Storage key:', NoteStorage.STORAGE_KEY);
            
            const notes = NoteStorage.getNotes();
            console.log('- Notes count:', notes.length);
            if (notes.length > 0) {
                console.log('- First note:', notes[0]);
            }
            
            // Create a test note to verify storage is working
            const testId = 'debug_' + Date.now();
            console.log('- Creating test note with ID:', testId);
            NoteStorage.addNote({
                id: testId,
                title: 'Debug Test Note',
                content: 'This is a debug test note created at ' + new Date().toLocaleString()
            });
            
            // Verify the test note was created
            const updatedNotes = NoteStorage.getNotes();
            console.log('- Updated notes count:', updatedNotes.length);
            console.log('- Test note exists:', updatedNotes.some(n => n.id === testId));
        } else {
            console.error('NoteStorage module not found!');
        }
        
        // 3. Check if old notes format exists
        const oldNotes = localStorage.getItem('notes');
        if (oldNotes) {
            console.log('\nOld notes format:');
            try {
                const parsed = JSON.parse(oldNotes);
                console.log('- Old notes count:', parsed.length);
                if (parsed.length > 0) {
                    console.log('- First old note:', parsed[0]);
                }
            } catch (e) {
                console.error('- Error parsing old notes:', e);
            }
        } else {
            console.log('\nNo old notes format found');
        }
        
        // 4. Check DOM elements
        console.log('\nDOM elements:');
        const savedNotesContainer = document.getElementById('savedNotesList');
        if (savedNotesContainer) {
            console.log('- savedNotesList element found');
            console.log('- Current content:', savedNotesContainer.innerHTML);
            
            // Directly inject a test note to verify rendering
            savedNotesContainer.innerHTML = `
                <div class="saved-note-item" style="background: #ffeeee;">
                    <div class="saved-note-title">Debug Test Note (Direct DOM)</div>
                    <div class="saved-note-preview">This is a direct DOM insertion test</div>
                    <div class="saved-note-date">${new Date().toLocaleString()}</div>
                </div>
            ` + savedNotesContainer.innerHTML;
            
            console.log('- Test note injected directly to DOM');
        } else {
            console.error('- savedNotesList element NOT found!');
        }
        
        // 5. Force update event
        console.log('\nForcing notes-updated event');
        document.dispatchEvent(new CustomEvent('notes-updated'));
        
        console.log('====== DEBUG COMPLETE ======');
    }, 2000); // Wait 2 seconds to ensure everything is loaded
});

// Monitor for events
const originalDispatchEvent = document.dispatchEvent;
document.dispatchEvent = function(event) {
    if (event.type === 'notes-updated') {
        console.log('notes-updated event dispatched');
    }
    return originalDispatchEvent.apply(this, arguments);
};

// Monitor loadSavedNotes function if it exists
if (typeof window.loadSavedNotes === 'function') {
    const originalLoadSavedNotes = window.loadSavedNotes;
    window.loadSavedNotes = function() {
        console.log('loadSavedNotes called');
        return originalLoadSavedNotes.apply(this, arguments);
    };
} 