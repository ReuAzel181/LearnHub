<!-- Notes Component -->
<div id="notes-content" class="tool-content">
    <div class="notes-workspace">
        <div class="notes-sidebar">
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
            </div>

            <div id="noteContent" class="note-content" contenteditable="true"></div>

            <div class="editor-footer">
                <div class="note-meta">
                    <span id="lastSaved">Last saved: Never</span>
                    <span id="wordCount">Words: 0</span>
                </div>
                <div class="editor-actions">
                    <button class="secondary-btn" onclick="createNewNote()">
                        <i class="fas fa-plus"></i> New
                    </button>
                    <button class="primary-btn" onclick="saveNote()">
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
}

.note-item:hover {
    transform: translateX(4px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.note-item.active {
    background: #e3f2fd;
}

.note-item-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.note-item-meta {
    font-size: 0.8rem;
    color: #666;
    display: flex;
    justify-content: space-between;
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

.primary-btn, .secondary-btn {
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

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
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

document.addEventListener('DOMContentLoaded', function() {
    loadFromLocalStorage();
    renderCategories();
    renderNotesList();
    initializeEditor();
});

function initializeEditor() {
    const noteContent = document.getElementById('noteContent');
    
    noteContent.addEventListener('input', function() {
        updateWordCount();
        autoSave();
    });
    
    // Initialize with empty note
    createNewNote();
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
    const wordCount = content.trim().split(/\s+/).length;
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
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
    };
    
    document.getElementById('noteTitle').value = '';
    document.getElementById('noteCategory').value = '';
    document.getElementById('noteContent').innerHTML = '';
    document.getElementById('lastSaved').textContent = 'Last saved: Never';
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

function loadNote(noteId) {
    const note = notes.find(n => n.id === noteId);
    if (note) {
        currentNote = note;
        document.getElementById('noteTitle').value = note.title;
        document.getElementById('noteCategory').value = note.category;
        document.getElementById('noteContent').innerHTML = note.content;
        document.getElementById('lastSaved').textContent = `Last saved: ${new Date(note.updatedAt).toLocaleTimeString()}`;
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
    
    notes.sort((a, b) => new Date(b.updatedAt) - new Date(a.updatedAt)).forEach(note => {
        const noteItem = document.createElement('div');
        noteItem.className = 'note-item';
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