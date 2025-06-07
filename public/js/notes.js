// Global variables for the notes component
let currentNote = null;
let notes = [];
let categories = [];
let activeFilter = 'all';

// Make toggle archive function available globally
window.toggleArchiveNote = function() {
    if (!currentNote) return;
    
    // Toggle archive status using NoteStorage
    NoteStorage.toggleArchive(currentNote.id);
    console.log('Toggled archive status for note:', currentNote.title);
    
    // Update the currentNote
    currentNote = NoteStorage.getNote(currentNote.id);
    
    // Update UI
    const archiveButtonText = document.getElementById('archiveButtonText');
    if (archiveButtonText) {
        archiveButtonText.textContent = currentNote.archived ? 'Unarchive' : 'Archive';
    }
    
    // Re-render notes list
    loadNotes();
};

function initializeNotes() {
    console.log('Initializing notes component');
    // Load notes from NoteStorage
    loadNotes();
    
    // Set up word count
    const noteContent = document.getElementById('noteContent');
    if (noteContent) {
        noteContent.addEventListener('input', updateWordCount);
    }
    
    // Check if we should edit a specific note (from dashboard)
    const editNoteId = localStorage.getItem('editNoteId');
    if (editNoteId) {
        console.log('Found note to edit:', editNoteId);
        const noteToEdit = NoteStorage.getNote(editNoteId);
        if (noteToEdit) {
            editNote(noteToEdit);
        }
        localStorage.removeItem('editNoteId');
    }
    
    // Listen for the edit-note event
    document.addEventListener('edit-note', function(e) {
        console.log('Edit note event received:', e.detail.noteId);
        const noteId = e.detail.noteId;
        const noteToEdit = NoteStorage.getNote(noteId);
        if (noteToEdit) {
            editNote(noteToEdit);
        }
    });

    // Add save button functionality
    const saveButton = document.querySelector('.primary-btn');
    if (saveButton) {
        saveButton.addEventListener('click', saveNote);
    }

    // Add new note button functionality
    const newButton = document.querySelector('.secondary-btn');
    if (newButton) {
        newButton.addEventListener('click', createNewNote);
    }

    // Add delete button functionality
    const deleteButton = document.querySelector('.delete-btn');
    if (deleteButton) {
        deleteButton.addEventListener('click', deleteNoteWithConfirmation);
    }
}

function loadNotes() {
    // Get notes from NoteStorage
    notes = NoteStorage.getNotes();
    console.log('Loaded notes from NoteStorage:', notes.length);
    
    // Render notes in the notes list if it exists
    const notesList = document.getElementById('notesList');
    if (notesList) {
        renderNotesList();
    }
}

function renderNotesList() {
    const notesListEl = document.getElementById('notesList');
    if (!notesListEl) return;
    
    notesListEl.innerHTML = '';
    
    // Filter notes based on active filter
    const filteredNotes = activeFilter === 'all' 
        ? notes.filter(note => !note.archived)
        : notes.filter(note => note.archived);
    
    if (filteredNotes.length === 0) {
        notesListEl.innerHTML = `<div class="empty-notes">No ${activeFilter === 'archived' ? 'archived' : ''} notes found</div>`;
        return;
    }
    
    filteredNotes.forEach(note => {
        const noteEl = document.createElement('div');
        noteEl.className = 'note-item';
        if (currentNote && note.id === currentNote.id) {
            noteEl.classList.add('active');
        }
        
        // Find category if it exists
        const category = note.categoryId ? categories.find(c => c.id === note.categoryId) : null;
        
        noteEl.innerHTML = `
            <div class="note-item-header">
                <span class="note-title">${note.title || 'Untitled'}</span>
                ${category ? `<span class="note-category-tag" style="background-color: ${category.color}">${category.name}</span>` : ''}
            </div>
            <div class="note-preview">${truncateText(note.content, 60)}</div>
            <div class="note-date">${note.date}</div>
        `;
        
        noteEl.addEventListener('click', () => editNote(note));
        notesListEl.appendChild(noteEl);
    });
}

function editNote(note) {
    console.log('Editing note:', note.title);
    currentNote = note;
    
    const titleInput = document.getElementById('noteTitle');
    const contentArea = document.getElementById('noteContent');
    
    if (titleInput) titleInput.value = note.title || '';
    if (contentArea) contentArea.innerHTML = note.content || '';
    
    // Update archive button text if it exists
    const archiveButtonText = document.getElementById('archiveButtonText');
    if (archiveButtonText) {
        archiveButtonText.textContent = note.archived ? 'Unarchive' : 'Archive';
    }

    // Update word count
    updateWordCount();
}

function saveNote() {
    const titleInput = document.getElementById('noteTitle');
    const contentArea = document.getElementById('noteContent');
    
    if (!titleInput || !contentArea) return;
    
    const title = titleInput.value.trim();
    const content = contentArea.innerHTML;
    
    // Don't save empty notes
    if (!title && !content) {
        alert('Please add a title or content to save the note');
        return;
    }
    
    if (currentNote) {
        // Update existing note
        NoteStorage.updateNote(currentNote.id, {
            title,
            content
        });
        console.log('Updated note:', title);
    } else {
        // Create new note
        const newNoteId = NoteStorage.addNote({
            title,
            content
        });
        console.log('Created new note with ID:', newNoteId);
        // Get the new note to set as current
        currentNote = NoteStorage.getNote(newNoteId);
    }
    
    // Update UI
    loadNotes();
    const lastSavedEl = document.getElementById('lastSaved');
    if (lastSavedEl) {
        lastSavedEl.textContent = `Last saved: ${new Date().toLocaleString()}`;
    }
}

function createNewNote() {
    currentNote = null;
    
    const titleInput = document.getElementById('noteTitle');
    const contentArea = document.getElementById('noteContent');
    const lastSavedEl = document.getElementById('lastSaved');
    const archiveButtonText = document.getElementById('archiveButtonText');
    
    if (titleInput) titleInput.value = '';
    if (contentArea) contentArea.innerHTML = '';
    if (lastSavedEl) lastSavedEl.textContent = 'Last saved: Never';
    if (archiveButtonText) archiveButtonText.textContent = 'Archive';
    
    updateWordCount();
}

function deleteNoteWithConfirmation() {
    if (!currentNote) return;
    
    if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
        deleteNote();
    }
}

function deleteNote() {
    if (!currentNote) return;
    
    // Delete the note
    NoteStorage.deleteNote(currentNote.id);
    console.log('Deleted note:', currentNote.title);
    
    // Clear the editor and update UI
    createNewNote();
    loadNotes();
}

function updateWordCount() {
    const contentArea = document.getElementById('noteContent');
    const wordCountEl = document.getElementById('wordCount');
    
    if (contentArea && wordCountEl) {
        const content = contentArea.innerText || '';
        const words = content.trim() ? content.trim().split(/\s+/).length : 0;
        wordCountEl.textContent = `Words: ${words}`;
    }
}

// Helper function to truncate text and remove HTML tags
function truncateText(html, maxLength) {
    // Remove HTML tags
    const div = document.createElement('div');
    div.innerHTML = html;
    const text = div.textContent || div.innerText || '';
    
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

// Initialize notes when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the notes page
    const notesContent = document.getElementById('notes-content');
    if (notesContent && notesContent.style.display !== 'none') {
        initializeNotes();
    }
}); 