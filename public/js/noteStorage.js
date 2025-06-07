/**
 * Note Storage Module
 * 
 * A centralized module for managing notes in localStorage
 * to be used by both the homepage and notes component.
 */

const NoteStorage = {
    // Key used in localStorage
    STORAGE_KEY: 'learnhub_notes',
    
    /**
     * Get all notes from storage
     * @returns {Array} Array of note objects
     */
    getNotes: function() {
        try {
            const notes = localStorage.getItem(this.STORAGE_KEY);
            return notes ? JSON.parse(notes) : [];
        } catch (error) {
            console.error('Error retrieving notes from storage:', error);
            return [];
        }
    },
    
    /**
     * Save notes to storage
     * @param {Array} notes Array of note objects
     */
    saveNotes: function(notes) {
        try {
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(notes));
            // Dispatch an event that notes have changed
            document.dispatchEvent(new CustomEvent('notes-updated'));
            return true;
        } catch (error) {
            console.error('Error saving notes to storage:', error);
            return false;
        }
    },
    
    /**
     * Add a new note
     * @param {Object} note Note object
     * @returns {String} ID of the new note
     */
    addNote: function(note) {
        const notes = this.getNotes();
        
        // Create note object with defaults
        const newNote = {
            id: Date.now().toString(),
            title: note.title || 'Untitled',
            content: note.content || '',
            date: new Date().toLocaleString(),
            categoryId: note.categoryId || null,
            archived: false,
            ...note
        };
        
        // Add to beginning of array
        notes.unshift(newNote);
        this.saveNotes(notes);
        
        return newNote.id;
    },
    
    /**
     * Update an existing note
     * @param {String} id Note ID
     * @param {Object} updatedData Updated note data
     * @returns {Boolean} Success status
     */
    updateNote: function(id, updatedData) {
        const notes = this.getNotes();
        const index = notes.findIndex(note => note.id === id);
        
        if (index === -1) return false;
        
        // Update the note
        notes[index] = {
            ...notes[index],
            ...updatedData,
            date: new Date().toLocaleString() // Update the date
        };
        
        return this.saveNotes(notes);
    },
    
    /**
     * Delete a note
     * @param {String} id Note ID
     * @returns {Boolean} Success status
     */
    deleteNote: function(id) {
        const notes = this.getNotes();
        const filteredNotes = notes.filter(note => note.id !== id);
        
        if (filteredNotes.length === notes.length) return false;
        
        return this.saveNotes(filteredNotes);
    },
    
    /**
     * Get a single note by ID
     * @param {String} id Note ID
     * @returns {Object|null} Note object or null if not found
     */
    getNote: function(id) {
        const notes = this.getNotes();
        return notes.find(note => note.id === id) || null;
    },
    
    /**
     * Toggle archive status of a note
     * @param {String} id Note ID
     * @returns {Boolean} Success status
     */
    toggleArchive: function(id) {
        const notes = this.getNotes();
        const index = notes.findIndex(note => note.id === id);
        
        if (index === -1) return false;
        
        notes[index].archived = !notes[index].archived;
        return this.saveNotes(notes);
    },
    
    /**
     * Migrate notes from old storage
     * This is to handle the transition from the old storage format
     */
    migrateFromOldStorage: function() {
        const oldNotes = localStorage.getItem('notes');
        
        if (oldNotes) {
            try {
                const parsedOldNotes = JSON.parse(oldNotes);
                if (Array.isArray(parsedOldNotes) && parsedOldNotes.length > 0) {
                    // Only migrate if we don't already have notes in the new format
                    const currentNotes = this.getNotes();
                    if (currentNotes.length === 0) {
                        this.saveNotes(parsedOldNotes);
                        console.log('Migrated notes from old storage format');
                    }
                }
            } catch (error) {
                console.error('Error migrating notes from old storage:', error);
            }
        }
    }
};

// Run migration on script load
document.addEventListener('DOMContentLoaded', function() {
    NoteStorage.migrateFromOldStorage();
}); 