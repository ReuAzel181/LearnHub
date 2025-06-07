<!-- Notes Component -->
<div id="notes-content" class="tool-content">
    <div class="notes-workspace">
        <div class="notes-sidebar">
            <div class="sidebar-header">
                <h3>Notes</h3>
                <div class="notes-filter">
                    <button id="showAllNotesBtn" class="filter-btn active" onclick="toggleNotesFilter('all')">All</button>
                    <button id="showArchivedNotesBtn" class="filter-btn" onclick="toggleNotesFilter('archived')">Archive</button>
                </div>
            </div>
            
            <div class="notes-categories">
                <h3>Categories</h3>
                <div class="category-list" id="categoryList">
                    <!-- Categories will be populated here -->
                </div>
                <button class="add-category-btn" onclick="showAddCategoryModal()">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>
            
            <div class="notes-list" id="notesList">
                <!-- Notes will be populated here -->
            </div>
        </div>

        <div class="notes-editor">
            <div class="editor-header">
                <input type="text" id="noteTitle" placeholder="Note Title" class="note-title-input">
                <select id="noteCategory" class="note-category-select">
                    <option value="">Select Category</option>
                </select>
            </div>

            <div class="editor-toolbar">
                <button onclick="execCommand('bold')" title="Bold">
                    <i class="fas fa-bold"></i>
                </button>
                <button onclick="execCommand('italic')" title="Italic">
                    <i class="fas fa-italic"></i>
                </button>
                <button onclick="execCommand('underline')" title="Underline">
                    <i class="fas fa-underline"></i>
                </button>
                <button onclick="execCommand('strikeThrough')" title="Strike Through">
                    <i class="fas fa-strikethrough"></i>
                </button>
                <div class="separator"></div>
                <button onclick="execCommand('justifyLeft')" title="Align Left">
                    <i class="fas fa-align-left"></i>
                </button>
                <button onclick="execCommand('justifyCenter')" title="Align Center">
                    <i class="fas fa-align-center"></i>
                </button>
                <button onclick="execCommand('justifyRight')" title="Align Right">
                    <i class="fas fa-align-right"></i>
                </button>
                <div class="separator"></div>
                <button onclick="execCommand('insertUnorderedList')" title="Bullet List">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button onclick="execCommand('insertOrderedList')" title="Numbered List">
                    <i class="fas fa-list-ol"></i>
                </button>
                <div class="separator"></div>
                <button onclick="addChecklistItem()" title="Add Checklist Item">
                    <i class="fas fa-check-square"></i>
                </button>
                <button onclick="insertLink()" title="Insert Link">
                    <i class="fas fa-link"></i>
                </button>
                <button onclick="showImageUploadModal()" title="Insert Image">
                    <i class="fas fa-image"></i>
                </button>
            </div>

            <div id="noteContent" class="note-content" contenteditable="true"></div>

            <div class="editor-footer">
                <div class="note-meta">
                    <span id="lastSaved">Last saved: Never</span>
                    <span id="wordCount">Words: 0</span>
                </div>
                <div class="editor-actions">
                    <button class="secondary-btn" id="newNoteBtn">
                        <i class="fas fa-plus"></i> New
                    </button>
                    <button class="archive-btn" id="archiveNoteBtn">
                        <i class="fas fa-archive"></i> <span id="archiveButtonText">Archive</span>
                    </button>
                    <button class="delete-btn" id="deleteNoteBtn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <button class="primary-btn" id="saveNoteBtn">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="modal">
    <div class="modal-content">
        <h3>Add New Category</h3>
        <input type="text" id="newCategoryName" placeholder="Category Name">
        <input type="color" id="newCategoryColor" value="#3498db">
        <div class="modal-actions">
            <button class="secondary-btn" onclick="closeAddCategoryModal()">Cancel</button>
            <button class="primary-btn" onclick="addNewCategory()">Add</button>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div id="imageUploadModal" class="modal">
    <div class="modal-content">
        <h3>Insert Image</h3>
        <div class="upload-methods">
            <div class="upload-method">
                <h4>Upload from device</h4>
                <input type="file" id="imageFileInput" accept="image/*">
            </div>
            <div class="upload-method">
                <h4>Paste image URL</h4>
                <input type="text" id="imageUrlInput" placeholder="https://example.com/image.jpg">
            </div>
            <div class="drag-drop-area" id="dragDropArea">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Drag & drop image here</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="secondary-btn" onclick="closeImageUploadModal()">Cancel</button>
            <button class="primary-btn" onclick="insertImage()">Insert</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content">
        <h3>Delete Note</h3>
        <p>Are you sure you want to delete this note? This action cannot be undone.</p>
        <div class="modal-actions">
            <button class="secondary-btn" onclick="closeDeleteConfirmModal()">Cancel</button>
            <button class="delete-btn" onclick="deleteNote()">Delete</button>
        </div>
    </div>
</div>

<script>
// Set up event listeners for the note buttons
document.addEventListener('DOMContentLoaded', function() {
    // Initialize note buttons when they exist
    const saveBtn = document.getElementById('saveNoteBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            if (typeof saveNote === 'function') saveNote();
        });
    }
    
    const newBtn = document.getElementById('newNoteBtn');
    if (newBtn) {
        newBtn.addEventListener('click', function() {
            if (typeof createNewNote === 'function') createNewNote();
        });
    }
    
    const deleteBtn = document.getElementById('deleteNoteBtn');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            if (typeof deleteNoteWithConfirmation === 'function') deleteNoteWithConfirmation();
        });
    }
    
    const archiveBtn = document.getElementById('archiveNoteBtn');
    if (archiveBtn) {
        archiveBtn.addEventListener('click', function() {
            if (typeof toggleArchiveNote === 'function') toggleArchiveNote();
        });
    }

    // Close modal buttons
    const closeDeleteModalBtn = document.querySelector('#deleteConfirmModal .secondary-btn');
    if (closeDeleteModalBtn) {
        closeDeleteModalBtn.addEventListener('click', function() {
            document.getElementById('deleteConfirmModal').style.display = 'none';
        });
    }
});

// This function will be implemented in notes.js
function toggleArchiveNote() {
    if (typeof window.toggleArchiveNote === 'function') {
        window.toggleArchiveNote();
    } else {
        console.error('toggleArchiveNote function not found');
    }
}

// Initialize when this component is shown
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('notes-content') && 
        document.getElementById('notes-content').style.display === 'block') {
        if (typeof initializeNotes === 'function') {
            initializeNotes();
        }
    }
});
</script>

<style>
.notes-workspace {
    display: flex;
    gap: 1.5rem;
    height: calc(100vh - 180px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.notes-sidebar {
    width: 300px;
    background: #f8f9fa;
    border-right: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 1.5rem 1.5rem 0.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.sidebar-header h3 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 1.3rem;
}

.notes-categories {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.notes-categories h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.1rem;
}

.category-list {
    margin-bottom: 1rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.category-item:hover {
    background: rgba(0,0,0,0.05);
}

.category-item.active {
    background: #e3f2fd;
}

.category-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.add-category-btn {
    width: 100%;
    padding: 0.75rem;
    border: 1px dashed #ccc;
    border-radius: 6px;
    background: transparent;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
}

.add-category-btn:hover {
    border-color: #3498db;
    color: #3498db;
}

.notes-filter {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    flex: 1;
    padding: 0.5rem 1rem;
    background: #f1f1f1;
    border: none;
    border-radius: 4px;
    color: #666;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.filter-btn.active {
    background: #3498db;
    color: white;
}

.notes-list {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
}

.note-item {
    padding: 1rem;
    border-radius: 8px;
    background: white;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.note-item:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.note-item.active {
    background: #e3f2fd;
}

.note-item.archived {
    opacity: 0.7;
    background: #f8f8f8;
    border-left: 3px solid #95a5a6;
}

.note-item-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    padding-right: 60px;
}

.note-item-meta {
    font-size: 0.8rem;
    color: #666;
    display: flex;
    justify-content: space-between;
}

.note-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.2s;
}

.note-item:hover .note-actions {
    opacity: 1;
}

.note-action-btn {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    border: none;
    background: transparent;
    color: #666;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.note-action-btn:hover {
    background: rgba(0,0,0,0.05);
}

.note-action-btn.archive-btn:hover {
    color: #7f8c8d;
}

.note-action-btn.delete-btn:hover {
    color: #e74c3c;
}

.notes-editor {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 1.5rem;
}

.editor-header {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.note-title-input {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #eee;
    border-radius: 6px;
    font-size: 1.2rem;
    font-weight: 600;
}

.note-category-select {
    padding: 0.75rem;
    border: 1px solid #eee;
    border-radius: 6px;
    min-width: 150px;
}

.editor-toolbar {
    display: flex;
    gap: 0.5rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.editor-toolbar button {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 4px;
    color: #666;
    cursor: pointer;
    transition: all 0.2s;
}

.editor-toolbar button:hover {
    background: #e9ecef;
    color: #333;
}

.editor-toolbar button.active {
    background: #e3f2fd;
    color: #3498db;
}

.separator {
    width: 1px;
    background: #eee;
    margin: 0 0.5rem;
}

.note-content {
    flex: 1;
    padding: 1rem;
    border: 1px solid #eee;
    border-radius: 6px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.note-content:focus {
    outline: none;
    border-color: #3498db;
}

.note-content img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 1rem 0;
}

.editor-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.note-meta {
    display: flex;
    gap: 1rem;
    color: #666;
    font-size: 0.9rem;
}

.editor-actions {
    display: flex;
    gap: 0.75rem;
}

.primary-btn, .secondary-btn, .delete-btn, .archive-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.primary-btn {
    background: #3498db;
    color: white;
}

.primary-btn:hover {
    background: #2980b9;
}

.secondary-btn {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #eee;
}

.secondary-btn:hover {
    background: #e9ecef;
}

.delete-btn {
    background: #e74c3c;
    color: white;
}

.delete-btn:hover {
    background: #c0392b;
}

.archive-btn {
    background: #95a5a6;
    color: white;
}

.archive-btn:hover {
    background: #7f8c8d;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 100%;
    max-width: 400px;
}

.modal-content h3 {
    margin-bottom: 1.5rem;
    color: #2c3e50;
}

.modal-content p {
    margin-bottom: 1.5rem;
    color: #666;
}

.modal-content input[type="text"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #eee;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.modal-content input[type="color"] {
    width: 100%;
    height: 40px;
    padding: 0;
    border: 1px solid #eee;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.modal-content input[type="file"] {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #eee;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.upload-methods {
    margin-bottom: 1.5rem;
}

.upload-method {
    margin-bottom: 1rem;
}

.upload-method h4 {
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-size: 1rem;
}

.drag-drop-area {
    padding: 2rem;
    border: 2px dashed #ccc;
    border-radius: 6px;
    text-align: center;
    color: #666;
    transition: all 0.2s;
    margin-top: 1rem;
}

.drag-drop-area.drag-over {
    background: #e3f2fd;
    border-color: #3498db;
}

.drag-drop-area i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

/* Checklist Styles */
.checklist-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0.5rem 0;
}

.checklist-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .notes-workspace {
        flex-direction: column;
    }

    .notes-sidebar {
        width: 100%;
        height: auto;
        border-right: none;
        border-bottom: 1px solid #eee;
    }

    .notes-editor {
        height: 0;
        flex: 1;
    }
}
</style>

<script>
let currentNote = null;
let notes = [];
let categories = [];
let currentFilter = 'all'; // 'all' or 'archived'

document.addEventListener('DOMContentLoaded', function() {
    loadFromLocalStorage();
    renderCategories();
    renderNotesList();
    initializeEditor();
    setupImageHandling();
});

function initializeEditor() {
    const noteContent = document.getElementById('noteContent');
    
    noteContent.addEventListener('input', function() {
        updateWordCount();
        autoSave();
    });

    // Handle paste events for images
    noteContent.addEventListener('paste', handlePaste);
    
    // Initialize with empty note
    createNewNote();
}

function setupImageHandling() {
    // Setup drag and drop for images
    const dragDropArea = document.getElementById('dragDropArea');
    const noteContent = document.getElementById('noteContent');
    
    // Setup file input
    const imageFileInput = document.getElementById('imageFileInput');
    imageFileInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            handleImageFile(this.files[0]);
        }
    });
    
    // Setup drag and drop
    dragDropArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('drag-over');
    });
    
    dragDropArea.addEventListener('dragleave', function(e) {
        this.classList.remove('drag-over');
    });
    
    dragDropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('drag-over');
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleImageFile(e.dataTransfer.files[0]);
        }
    });
    
    // Setup note content area to accept dropped images
    noteContent.addEventListener('dragover', function(e) {
        e.preventDefault();
    });
    
    noteContent.addEventListener('drop', function(e) {
        e.preventDefault();
        
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            handleImageFile(e.dataTransfer.files[0]);
        }
    });
}

function handlePaste(e) {
    // Check if clipboard contains image data
    const items = (e.clipboardData || e.originalEvent.clipboardData).items;
    
    for (let i = 0; i < items.length; i++) {
        if (items[i].type.indexOf('image') !== -1) {
            const file = items[i].getAsFile();
            handleImageFile(file);
            e.preventDefault();
            break;
        }
    }
}

function handleImageFile(file) {
    if (!file || !file.type.match(/image.*/)) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        insertImageAtCursor(e.target.result);
    };
    reader.readAsDataURL(file);
    
    // Close modal if open
    closeImageUploadModal();
}

function insertImageAtCursor(imageUrl) {
    const img = document.createElement('img');
    img.src = imageUrl;
    img.style.maxWidth = '100%';
    
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        range.insertNode(img);
        
        // Move cursor after image
        range.setStartAfter(img);
        range.setEndAfter(img);
        selection.removeAllRanges();
        selection.addRange(range);
    } else {
        document.getElementById('noteContent').appendChild(img);
    }
}

function showImageUploadModal() {
    document.getElementById('imageUploadModal').style.display = 'flex';
    document.getElementById('imageUrlInput').value = '';
    document.getElementById('imageFileInput').value = '';
}

function closeImageUploadModal() {
    document.getElementById('imageUploadModal').style.display = 'none';
}

function insertImage() {
    const url = document.getElementById('imageUrlInput').value.trim();
    const fileInput = document.getElementById('imageFileInput');
    
    if (url) {
        insertImageAtCursor(url);
        closeImageUploadModal();
    } else if (fileInput.files && fileInput.files[0]) {
        handleImageFile(fileInput.files[0]);
    } else {
        alert('Please provide an image URL or select a file.');
    }
}

function execCommand(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('noteContent').focus();
}

function addChecklistItem() {
    const checklistItem = document.createElement('div');
    checklistItem.className = 'checklist-item';
    checklistItem.innerHTML = `
        <input type="checkbox">
        <span contenteditable="true">New item</span>
    `;
    document.getElementById('noteContent').appendChild(checklistItem);
}

function insertLink() {
    const url = prompt('Enter URL:');
    if (url) {
        execCommand('createLink', url);
    }
}

function updateWordCount() {
    const content = document.getElementById('noteContent').innerText;
    const wordCount = content.trim() ? content.trim().split(/\s+/).length : 0;
    document.getElementById('wordCount').textContent = `Words: ${wordCount}`;
}

function autoSave() {
    if (currentNote) {
        saveNote(true);
    }
}

function createNewNote() {
    currentNote = {
        id: Date.now(),
        title: '',
        content: '',
        category: '',
        archived: false,
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
    };
    
    document.getElementById('noteTitle').value = '';
    document.getElementById('noteCategory').value = '';
    document.getElementById('noteContent').innerHTML = '';
    document.getElementById('lastSaved').textContent = 'Last saved: Never';
    document.getElementById('archiveButtonText').textContent = 'Archive';
    updateWordCount();
}

function saveNote(isAutoSave = false) {
    if (!currentNote) return;
    
    currentNote.title = document.getElementById('noteTitle').value;
    currentNote.content = document.getElementById('noteContent').innerHTML;
    currentNote.category = document.getElementById('noteCategory').value;
    currentNote.updatedAt = new Date().toISOString();
    
    const existingNoteIndex = notes.findIndex(note => note.id === currentNote.id);
    if (existingNoteIndex !== -1) {
        notes[existingNoteIndex] = currentNote;
    } else {
        notes.push(currentNote);
    }
    
    saveToLocalStorage();
    renderNotesList();
    
    if (!isAutoSave) {
        showNotification('Note saved successfully!');
    }
    
    document.getElementById('lastSaved').textContent = `Last saved: ${new Date().toLocaleTimeString()}`;
}

function deleteNoteWithConfirmation(noteId = null) {
    if (noteId) {
        // If called from note item
        const note = notes.find(n => n.id === noteId);
        if (note) {
            currentNote = note;
        }
    }
    
    if (!currentNote) return;
    
    document.getElementById('deleteConfirmModal').style.display = 'flex';
}

function closeDeleteConfirmModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
}

function deleteNote() {
    if (!currentNote) return;
    
    notes = notes.filter(note => note.id !== currentNote.id);
    saveToLocalStorage();
    renderNotesList();
    
    // If we're viewing the deleted note, create a new one
    if (document.getElementById('noteTitle').value) {
        createNewNote();
    }
    
    closeDeleteConfirmModal();
    showNotification('Note deleted successfully!');
}

function toggleArchiveNote(noteId = null) {
    // If called from note item
    if (noteId) {
        const noteIndex = notes.findIndex(n => n.id === noteId);
        if (noteIndex !== -1) {
            notes[noteIndex].archived = !notes[noteIndex].archived;
            
            // If this is the current note, update the button text
            if (currentNote && currentNote.id === noteId) {
                currentNote.archived = notes[noteIndex].archived;
                document.getElementById('archiveButtonText').textContent = 
                    currentNote.archived ? 'Unarchive' : 'Archive';
            }
            
            saveToLocalStorage();
            renderNotesList();
            showNotification(notes[noteIndex].archived ? 'Note archived!' : 'Note unarchived!');
            return;
        }
    }
    
    // If called from main editor
    if (!currentNote) return;
    
    currentNote.archived = !currentNote.archived;
    
    // Update button text
    document.getElementById('archiveButtonText').textContent = 
        currentNote.archived ? 'Unarchive' : 'Archive';
    
    saveNote();
    showNotification(currentNote.archived ? 'Note archived!' : 'Note unarchived!');
}

function toggleNotesFilter(filter) {
    currentFilter = filter;
    
    // Update active button
    document.getElementById('showAllNotesBtn').classList.toggle('active', filter === 'all');
    document.getElementById('showArchivedNotesBtn').classList.toggle('active', filter === 'archived');
    
    renderNotesList();
}

function loadNote(noteId) {
    const note = notes.find(n => n.id === noteId);
    if (note) {
        currentNote = note;
        document.getElementById('noteTitle').value = note.title;
        document.getElementById('noteCategory').value = note.category;
        document.getElementById('noteContent').innerHTML = note.content;
        document.getElementById('lastSaved').textContent = `Last saved: ${new Date(note.updatedAt).toLocaleTimeString()}`;
        document.getElementById('archiveButtonText').textContent = note.archived ? 'Unarchive' : 'Archive';
        updateWordCount();
        
        // Update active state in list
        document.querySelectorAll('.note-item').forEach(item => {
            item.classList.toggle('active', item.dataset.id === noteId.toString());
        });
    }
}

function showAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'flex';
}

function closeAddCategoryModal() {
    document.getElementById('addCategoryModal').style.display = 'none';
}

function addNewCategory() {
    const name = document.getElementById('newCategoryName').value.trim();
    const color = document.getElementById('newCategoryColor').value;
    
    if (name) {
        categories.push({
            id: Date.now(),
            name,
            color
        });
        
        saveToLocalStorage();
        renderCategories();
        closeAddCategoryModal();
        document.getElementById('newCategoryName').value = '';
    }
}

function renderCategories() {
    const categoryList = document.getElementById('categoryList');
    const categorySelect = document.getElementById('noteCategory');
    
    // Clear existing options
    categoryList.innerHTML = '';
    categorySelect.innerHTML = '<option value="">Select Category</option>';
    
    categories.forEach(category => {
        // Add to sidebar list
        const categoryItem = document.createElement('div');
        categoryItem.className = 'category-item';
        categoryItem.innerHTML = `
            <div class="category-color" style="background: ${category.color}"></div>
            <span>${category.name}</span>
        `;
        categoryList.appendChild(categoryItem);
        
        // Add to select dropdown
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}

function renderNotesList() {
    const notesList = document.getElementById('notesList');
    notesList.innerHTML = '';
    
    // Filter notes based on current filter
    const filteredNotes = notes.filter(note => {
        if (currentFilter === 'all') return !note.archived;
        if (currentFilter === 'archived') return note.archived;
        return true;
    });
    
    filteredNotes.sort((a, b) => new Date(b.updatedAt) - new Date(a.updatedAt)).forEach(note => {
        const noteItem = document.createElement('div');
        noteItem.className = 'note-item';
        if (note.archived) {
            noteItem.classList.add('archived');
        }
        if (currentNote && currentNote.id === note.id) {
            noteItem.classList.add('active');
        }
        noteItem.dataset.id = note.id;
        
        const category = categories.find(c => c.id === parseInt(note.category));
        const categoryName = category ? category.name : '';
        
        noteItem.innerHTML = `
            <div class="note-item-title">${note.title || 'Untitled'}</div>
            <div class="note-item-meta">
                <span>${categoryName}</span>
                <span>${new Date(note.updatedAt).toLocaleDateString()}</span>
            </div>
            <div class="note-actions">
                <button onclick="event.stopPropagation(); toggleArchiveNote(${note.id})" class="note-action-btn archive-btn" title="${note.archived ? 'Unarchive' : 'Archive'}">
                    <i class="fas fa-archive"></i>
                </button>
                <button onclick="event.stopPropagation(); deleteNoteWithConfirmation(${note.id})" class="note-action-btn delete-btn" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        noteItem.addEventListener('click', () => loadNote(note.id));
        notesList.appendChild(noteItem);
    });
}

function saveToLocalStorage() {
    localStorage.setItem('learnhub_notes', JSON.stringify(notes));
    localStorage.setItem('learnhub_categories', JSON.stringify(categories));
}

function loadFromLocalStorage() {
    const savedNotes = localStorage.getItem('learnhub_notes');
    const savedCategories = localStorage.getItem('learnhub_categories');
    
    if (savedNotes) {
        notes = JSON.parse(savedNotes);
    }
    
    if (savedCategories) {
        categories = JSON.parse(savedCategories);
    }
}

function showNotification(message) {
    // You can implement a more sophisticated notification system here
    alert(message);
}
</script> 