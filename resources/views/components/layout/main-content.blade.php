<!-- URL: /dashboard -->
<main class="main-content">
    <div id="dashboard-content">
        <div class="dashboard-header">
            <p>Welcome to LearnHub! Select a tool to get started.</p>
        </div>

        <div class="action-buttons">
            <div class="action-button" onclick="switchContent('notes')">
                <div class="button-content">
                    <i class="fas fa-sticky-note"></i>
                    <span class="button-text">Notes</span>
                    <span class="button-description">Create and manage your study notes</span>
                </div>
            </div>
            <div class="action-button" onclick="switchContent('draw')">
                <div class="button-content">
                    <i class="fas fa-paint-brush"></i>
                    <span class="button-text">Draw</span>
                    <span class="button-description">Sketch and illustrate your ideas</span>
                </div>
            </div>
            <div class="action-button" onclick="switchContent('calculator')">
                <div class="button-content">
                    <i class="fas fa-calculator"></i>
                    <span class="button-text">Calculator</span>
                    <span class="button-description">Solve mathematical problems</span>
                </div>
            </div>
            <div class="action-button" onclick="switchContent('dictionary')">
                <div class="button-content">
                    <i class="fas fa-book"></i>
                    <span class="button-text">Dictionary</span>
                    <span class="button-description">Look up word definitions</span>
                </div>
            </div>
        </div>

        <!-- Saved Notes Section -->
        <div class="saved-notes-section">
            <h3>Saved Notes</h3>
            <div id="savedNotesList">
                <!-- Static fallback notes - these will show if JavaScript fails -->
                <div class="saved-note-item" style="border: 2px solid #6c757d;">
                    <div class="saved-note-title">Static Fallback Note</div>
                    <div class="saved-note-preview">This is a static fallback note that displays when JavaScript is not working properly.</div>
                    <div class="saved-note-date">{{ date('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tool Components -->
    @include('components.notes')
    @include('components.draw')
    @include('components.calculator')
    @include('components.dictionary')

    <button id="exit-button" onclick="showDashboard()" style="display: none;">
        <i class="fas fa-arrow-left"></i>
        Back to Dashboard
    </button>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize based on URL
    const path = window.location.pathname;
    if (path === '/dashboard' || path === '/') {
        showDashboard();
    } else {
        const tool = path.substring(1); // Remove leading slash
        if (['notes', 'draw', 'calculator', 'dictionary'].includes(tool)) {
            switchContent(tool);
        } else {
            showDashboard();
        }
    }

    // Load and display saved notes on dashboard
    loadSavedNotes();
    
    // Listen for notes updates
    document.addEventListener('notes-updated', function() {
        loadSavedNotes();
    });
});

// Function to load saved notes from NoteStorage - exposed globally
window.loadSavedNotes = function() {
    const savedNotesContainer = document.getElementById('savedNotesList');
    if (!savedNotesContainer) {
        console.error('savedNotesList container not found!');
        return;
    }

    // Get notes from NoteStorage
    const notes = NoteStorage.getNotes();
    console.log('Loading notes for dashboard:', notes.length);
    
    if (notes.length === 0) {
        savedNotesContainer.innerHTML = '<div class="no-notes-message">No saved notes yet. Create notes using the Notes tool.</div>';
        return;
    }

    // Filter to show only non-archived notes
    const activeNotes = notes.filter(note => !note.archived);
    console.log('Active notes for dashboard:', activeNotes.length);
    
    if (activeNotes.length === 0) {
        savedNotesContainer.innerHTML = '<div class="no-notes-message">All notes are archived. Create new notes using the Notes tool.</div>';
        return;
    }

    // Generate HTML for each note
    const notesHTML = activeNotes.map(note => `
        <div class="saved-note-item" onclick="editSavedNote('${note.id}')">
            <div class="saved-note-title">${note.title || 'Untitled'}</div>
            <div class="saved-note-preview">${truncateText(note.content, 100)}</div>
            <div class="saved-note-date">${note.date}</div>
        </div>
    `).join('');

    console.log('Setting HTML content for notes list');
    savedNotesContainer.innerHTML = notesHTML;
}

// Helper function to truncate text
function truncateText(text, maxLength) {
    // Remove HTML tags
    const div = document.createElement('div');
    div.innerHTML = text;
    const plainText = div.textContent || div.innerText || '';
    
    if (plainText.length <= maxLength) return plainText;
    return plainText.substring(0, maxLength) + '...';
}

// Function to edit a saved note
function editSavedNote(noteId) {
    // Navigate to notes page with the note ID
    switchContent('notes');
    
    // Set a flag to edit this note
    localStorage.setItem('editNoteId', noteId);
    
    // Dispatch a custom event that the notes component can listen for
    const event = new CustomEvent('edit-note', { detail: { noteId } });
    document.dispatchEvent(event);
}

function switchContent(contentType) {
    // Hide all content sections
    document.querySelectorAll('.tool-content, #dashboard-content').forEach(content => {
        content.style.display = 'none';
    });

    // Show selected content
    const contentElement = document.getElementById(`${contentType}-content`);
    if (contentElement) {
        contentElement.style.display = 'block';
        
        // Initialize specific tool
        switch(contentType) {
            case 'notes':
                if (typeof initializeNotes === 'function') {
                    initializeNotes();
                }
                break;
            case 'draw':
                if (typeof initializeDrawingCanvas === 'function') {
                    initializeDrawingCanvas();
                }
                break;
            case 'calculator':
                if (typeof initializeCalculator === 'function') {
                    initializeCalculator();
                }
                break;
            case 'dictionary':
                if (typeof initializeDictionary === 'function') {
                    initializeDictionary();
                }
                break;
        }
    }

    // Update title and show back button
    const titles = {
        notes: 'Notes',
        draw: 'Drawing Board',
        calculator: 'Calculator',
        dictionary: 'Dictionary'
    };
    
    document.getElementById('section-title').textContent = titles[contentType] || 'Dashboard';
    document.getElementById('exit-button').style.display = 'flex';

    // Update URL without page reload
    window.history.pushState({}, '', `/${contentType}`);

    // Update active state in sidebar
    document.querySelectorAll('.nav-section li').forEach(item => {
        item.classList.remove('active');
    });
    const activeLink = document.querySelector(`.nav-section a[href="/${contentType}"]`);
    if (activeLink) {
        activeLink.parentElement.classList.add('active');
    }
}

function showDashboard() {
    // Hide all tool content
    document.querySelectorAll('.tool-content').forEach(content => {
        content.style.display = 'none';
    });
    
    // Show dashboard
    document.getElementById('dashboard-content').style.display = 'block';
    document.getElementById('section-title').textContent = 'Dashboard';
    document.getElementById('exit-button').style.display = 'none';
    
    // Update URL
    window.history.pushState({}, '', '/dashboard');
    
    // Update active state in sidebar
    document.querySelectorAll('.nav-section li').forEach(item => {
        item.classList.remove('active');
    });
    const dashboardLink = document.querySelector('.nav-section a[href="/dashboard"]');
    if (dashboardLink) {
        dashboardLink.parentElement.classList.add('active');
    }
    
    // Refresh saved notes when returning to dashboard
    loadSavedNotes();
}
</script>

<style>
.main-content {
    position: fixed;
    top: var(--header-height);
    left: var(--sidebar-width);
    right: 0;
    bottom: 0;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f6f6f7 0%, #e9ecf4 100%);
    overflow-y: auto;
    transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
    will-change: left, margin-left;
}

body.sidebar-collapsed .main-content {
    left: var(--sidebar-collapsed-width);
}

.dashboard-header {
    margin-bottom: 2rem;
}

.dashboard-header p {
    color: #6c757d;
    font-size: 1.1rem;
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-button {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.action-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 1);
}

.button-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.75rem;
}

.button-content i {
    font-size: 2rem;
    color: #3498db;
}

.button-text {
    font-size: 1.25rem;
    font-weight: 500;
    color: #2c3e50;
}

.button-description {
    color: #6c757d;
    font-size: 0.875rem;
}

.saved-notes-section {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-top: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.saved-notes-section h3 {
    margin-bottom: 1rem;
    color: #2c3e50;
    font-size: 1.25rem;
    font-weight: 600;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

#savedNotesList {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.saved-note-item {
    padding: 1rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.saved-note-item:hover {
    background: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.saved-note-title {
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.saved-note-preview {
    font-size: 0.875rem;
    color: #555;
    margin-bottom: 0.5rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.saved-note-date {
    font-size: 0.75rem;
    color: #6c757d;
}

.no-notes-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 1rem;
    color: #6c757d;
    font-style: italic;
}

@media (max-width: 768px) {
    .main-content {
        left: 0;
        width: 100%;
        margin: 0;
        top: var(--header-height);
    }

    body.sidebar-collapsed .main-content {
        left: 0;
    }

    .action-buttons {
        grid-template-columns: 1fr;
    }

    #savedNotesList {
        grid-template-columns: 1fr;
    }

    .action-button {
        padding: 1rem;
    }
}
</style> 