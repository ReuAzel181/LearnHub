// Initialize Quill editor
let quill;
let drawingCanvas;
let currentView = 'grid';
let currentTool = 'pen';

document.addEventListener('DOMContentLoaded', function() {
    initializeQuillEditor();
    initializeDrawingBoard();
    initializeCalculator();
    initializeTooltips();
    initializeDragAndDrop();
    setupEventListeners();
    
    // Add animation classes to elements
    animateOnScroll();
    animateToolCards();
});

// Editor Initialization
function initializeQuillEditor() {
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        ['link', 'image', 'formula'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }]
    ];

    quill = new Quill('#noteEditor', {
        modules: {
            toolbar: toolbarOptions,
            formula: true,
            syntax: true
        },
        theme: 'snow',
        placeholder: 'Start writing your note...'
    });
}

// Drawing Board Initialization
function initializeDrawingBoard() {
    drawingCanvas = new fabric.Canvas('drawingCanvas');
    drawingCanvas.isDrawingMode = true;
    drawingCanvas.freeDrawingBrush.width = 2;
    drawingCanvas.freeDrawingBrush.color = '#4f46e5';
    
    // Set canvas size
    function resizeCanvas() {
        const container = document.querySelector('#drawingBoardModal .modal-body');
        const width = container.offsetWidth;
        const height = container.offsetHeight;
        
        drawingCanvas.setWidth(width);
        drawingCanvas.setHeight(height);
        drawingCanvas.renderAll();
    }
    
    window.addEventListener('resize', debounce(resizeCanvas, 250));
    
    // Drawing tools event listeners
    document.querySelector('#strokeColor').addEventListener('change', function(e) {
        drawingCanvas.freeDrawingBrush.color = e.target.value;
    });
    
    document.querySelector('#strokeWidth').addEventListener('change', function(e) {
        drawingCanvas.freeDrawingBrush.width = parseInt(e.target.value, 10);
    });
}

// Set drawing tool
function setTool(tool) {
    currentTool = tool;
    const toolButtons = document.querySelectorAll('.drawing-tools .btn-icon');
    toolButtons.forEach(btn => btn.classList.remove('active'));
    
    if (tool === 'pen') {
        drawingCanvas.isDrawingMode = true;
        document.querySelector('[onclick="setTool(\'pen\')"]').classList.add('active');
    } else if (tool === 'eraser') {
        drawingCanvas.isDrawingMode = true;
        drawingCanvas.freeDrawingBrush.color = '#ffffff';
        document.querySelector('[onclick="setTool(\'eraser\')"]').classList.add('active');
    }
}

// Calculator Initialization
function initializeCalculator() {
    const calculator = document.querySelector('.calc-buttons');
    const display = document.querySelector('#calcInput');
    const buttons = [
        'C', '±', '%', '/',
        '7', '8', '9', '*',
        '4', '5', '6', '-',
        '1', '2', '3', '+',
        '0', '.', '=', 'DEL'
    ];
    
    let calculation = '';
    let lastResult = '';
    
    buttons.forEach(btn => {
        const button = document.createElement('button');
        button.className = `btn btn-calculator ${isNaN(btn) ? 'btn-operator' : 'btn-number'}`;
        if (btn === '=') button.classList.add('btn-equals');
        if (btn === 'C' || btn === 'DEL') button.classList.add('btn-clear');
        button.textContent = btn;
        button.onclick = () => {
            switch(btn) {
                case 'C':
                    calculation = '';
                    lastResult = '';
                    break;
                case '±':
                    calculation = String(-parseFloat(calculation || '0'));
                    break;
                case '%':
                    calculation = String(parseFloat(calculation || '0') / 100);
                    break;
                case 'DEL':
                    calculation = calculation.slice(0, -1);
                    break;
                case '=':
                    try {
                        lastResult = calculation;
                        calculation = String(eval(calculation));
                        button.classList.add('active');
                        setTimeout(() => button.classList.remove('active'), 200);
                    } catch (e) {
                        calculation = 'Error';
                        setTimeout(() => calculation = lastResult || '', 1000);
                    }
                    break;
                default:
                    if (calculation === 'Error') calculation = '';
                    calculation += btn;
            }
            display.value = calculation;
            
            // Add button press animation
            button.classList.add('pressed');
            setTimeout(() => button.classList.remove('pressed'), 100);
        };
        calculator.appendChild(button);
    });
}

// Tooltips Initialization
function initializeTooltips() {
    tippy('[title]', {
        placement: 'bottom',
        arrow: true,
        delay: [50, 0],
        animation: 'shift-away',
        theme: 'custom'
    });
}

// Drag and Drop Initialization
function initializeDragAndDrop() {
    const notesList = document.querySelector('#recentNotes');
    new Sortable(notesList, {
        animation: 150,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        dragClass: 'sortable-drag',
        onStart: function() {
            document.body.classList.add('dragging');
        },
        onEnd: function(evt) {
            document.body.classList.remove('dragging');
            const noteId = evt.item.dataset.id;
            const newIndex = evt.newIndex;
            updateNoteOrder(noteId, newIndex);
        }
    });
}

// Animation Functions
function animateOnScroll() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });
    
    document.querySelectorAll('.section-header, .tool-card, .note-card').forEach(el => {
        el.classList.add('animate-prepare');
        observer.observe(el);
    });
}

function animateToolCards() {
    const cards = document.querySelectorAll('.tool-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}

// Event Listeners Setup
function setupEventListeners() {
    // Modal handlers
    document.querySelectorAll('.modal .btn-close').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.closest('.modal').id;
            closeModal(modalId);
        });
    });
    
    // Search handler with highlighting
    const searchInput = document.querySelector('#globalSearch');
    searchInput.addEventListener('input', debounce(function(e) {
        searchContent(e.target.value);
    }, 300));
    
    // View toggle handler with animation
    document.querySelectorAll('[onclick^="toggleView"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.getAttribute('onclick').match(/'(.+)'/)[1];
            toggleView(view);
        });
    });
    
    // Category color preview
    const colorInput = document.querySelector('#categoryColor');
    colorInput.addEventListener('input', function(e) {
        this.parentElement.style.borderColor = e.target.value;
    });
}

// Modal Functions with Animation
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Add entrance animation
    requestAnimationFrame(() => {
        modal.classList.add('modal-open');
    });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('modal-open');
    modal.classList.add('modal-close');
    
    // Wait for animation to complete
    setTimeout(() => {
        modal.style.display = 'none';
        modal.classList.remove('modal-close');
        document.body.style.overflow = 'auto';
    }, 300);
}

// Note Functions with Animation
function createNewNote() {
    openModal('noteEditorModal');
    quill.setText('');
    
    // Focus title input with delay for animation
    setTimeout(() => {
        document.querySelector('.title-input').focus();
    }, 300);
}

function saveNote() {
    const title = document.querySelector('.title-input').value;
    const content = quill.getContents();
    const category = document.querySelector('#noteCategory').value;
    
    if (!title.trim()) {
        showToast('Please enter a title for your note', 'warning');
        document.querySelector('.title-input').focus();
        return;
    }
    
    // Save note to backend
    fetch('/api/notes', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            title,
            content,
            category
        })
    })
    .then(response => response.json())
    .then(data => {
        closeModal('noteEditorModal');
        addNoteToGrid(data);
        showToast('Note saved successfully!', 'success');
    })
    .catch(error => {
        showToast('Error saving note', 'error');
    });
}

// Drawing Functions
function openDrawingBoard() {
    openModal('drawingBoardModal');
    setTimeout(() => {
        const container = document.querySelector('#drawingBoardModal .modal-body');
        drawingCanvas.setWidth(container.offsetWidth);
        drawingCanvas.setHeight(container.offsetHeight);
        drawingCanvas.renderAll();
    }, 300);
}

function saveDrawing() {
    const dataUrl = drawingCanvas.toDataURL();
    
    fetch('/api/drawings', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            image: dataUrl
        })
    })
    .then(response => response.json())
    .then(data => {
        closeModal('drawingBoardModal');
        showToast('Drawing saved successfully!', 'success');
    })
    .catch(error => {
        showToast('Error saving drawing', 'error');
    });
}

function clearCanvas() {
    // Add confirmation for better UX
    if (drawingCanvas.getObjects().length > 0) {
        if (!confirm('Are you sure you want to clear the canvas?')) return;
    }
    drawingCanvas.clear();
}

// View Functions with Animation
function toggleView(view) {
    if (currentView === view) return;
    
    const container = document.querySelector('#studyMaterials');
    container.style.opacity = '0';
    
    setTimeout(() => {
        currentView = view;
        container.className = `materials-${view}`;
        
        document.querySelectorAll('[onclick^="toggleView"]').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[onclick="toggleView('${view}')"]`).classList.add('active');
        
        container.style.opacity = '1';
    }, 300);
}

// Search Function with Highlighting
function searchContent(query) {
    const items = document.querySelectorAll('.note-card, .material-card');
    
    if (!query) {
        items.forEach(el => {
            el.style.display = 'block';
            el.classList.remove('search-highlight');
            // Remove highlight spans
            el.querySelectorAll('.highlight').forEach(span => {
                span.outerHTML = span.innerHTML;
            });
        });
        return;
    }
    
    const regex = new RegExp(query, 'gi');
    
    items.forEach(el => {
        const title = el.querySelector('.title').textContent;
        const content = el.querySelector('.content').textContent;
        const matches = title.match(regex) || content.match(regex);
        
        if (matches) {
            el.style.display = 'block';
            el.classList.add('search-highlight');
            
            // Highlight matching text
            el.querySelectorAll('.title, .content').forEach(textEl => {
                textEl.innerHTML = textEl.textContent.replace(regex, match => 
                    `<span class="highlight">${match}</span>`
                );
            });
        } else {
            el.style.display = 'none';
            el.classList.remove('search-highlight');
        }
    });
}

// Utility Functions
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

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 
                         type === 'error' ? 'times-circle' : 
                         type === 'warning' ? 'exclamation-circle' : 
                         'info-circle'}"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    
    // Animate entrance
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });
    
    setTimeout(() => {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// API Functions
function updateNoteOrder(noteId, newIndex) {
    fetch('/api/notes/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            noteId,
            newIndex
        })
    })
    .catch(error => {
        showToast('Error updating note order', 'error');
    });
}

function addNoteToGrid(note) {
    const noteCard = document.createElement('div');
    noteCard.className = 'note-card animate-prepare';
    noteCard.dataset.id = note.id;
    noteCard.innerHTML = `
        <h3 class="title">${note.title}</h3>
        <div class="content">${note.content}</div>
        <div class="note-footer">
            <span class="category">${note.category}</span>
            <div class="actions">
                <button class="btn-icon" onclick="editNote(${note.id})" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-icon" onclick="deleteNote(${note.id})" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    const notesList = document.querySelector('#recentNotes');
    notesList.insertBefore(noteCard, notesList.firstChild);
    
    // Animate entrance
    requestAnimationFrame(() => {
        noteCard.classList.add('animate-in');
    });
    
    // Initialize tooltips for new buttons
    tippy(noteCard.querySelectorAll('[title]'), {
        placement: 'bottom',
        arrow: true,
        delay: [50, 0],
        animation: 'shift-away',
        theme: 'custom'
    });
} 