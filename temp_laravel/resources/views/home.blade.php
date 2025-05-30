@extends('layouts.app')

@section('content')
<div class="app-container">
    <!-- Sidebar Navigation -->
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <h1>
                <i class="fas fa-graduation-cap"></i>
                LearnHub
            </h1>
            <p class="text-secondary">Your Learning Workspace</p>
        </div>

        <div class="nav-section">
            <h2 class="nav-title">Quick Actions</h2>
            <button class="action-btn" onclick="createNewNote()">
                <i class="fas fa-pen-to-square"></i>
                <span>New Note</span>
            </button>
            <button class="action-btn" onclick="openDrawingBoard()">
                <i class="fas fa-paintbrush"></i>
                <span>Draw</span>
            </button>
            <button class="action-btn" onclick="openCalculator()">
                <i class="fas fa-calculator"></i>
                <span>Calculator</span>
            </button>
        </div>

        <div class="nav-section">
            <h2 class="nav-title">Categories</h2>
            <div class="category-form">
                <div class="input-group">
                    <input type="text" id="newCategory" class="form-control" placeholder="New Category">
                    <input type="color" id="categoryColor" value="#4f46e5" title="Category Color">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i>
                    Add
                </button>
            </div>
            <div class="category-list" id="categoryList">
                <!-- Categories will be listed here -->
            </div>
        </div>

        <div class="nav-section">
            <h2 class="nav-title">Tools</h2>
            <button class="tool-btn" onclick="openLibrary()">
                <i class="fas fa-book"></i>
                <span>Library</span>
            </button>
            <button class="tool-btn" onclick="openFlashcards()">
                <i class="fas fa-layer-group"></i>
                <span>Flashcards</span>
            </button>
            <button class="tool-btn" onclick="openPomodoro()">
                <i class="fas fa-clock"></i>
                <span>Pomodoro</span>
            </button>
            <button class="tool-btn" onclick="openTasks()">
                <i class="fas fa-list-check"></i>
                <span>Tasks</span>
            </button>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="app-main">
        <!-- Top Bar -->
        <header class="main-header">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="globalSearch" class="form-control" placeholder="Search notes, files, and more...">
            </div>
            <div class="header-actions">
                <button class="btn btn-icon" onclick="toggleView('grid')" title="Grid View">
                    <i class="fas fa-grid-2"></i>
                </button>
                <button class="btn btn-icon" onclick="toggleView('list')" title="List View">
                    <i class="fas fa-list"></i>
                </button>
                <button class="btn btn-icon" onclick="toggleTrash()" title="Trash">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Quick Access -->
            <section class="quick-access">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Quick Access</h2>
                        <p class="section-subtitle">Your most used tools</p>
                    </div>
                </div>
                
                <div class="quick-tools">
                    <div class="tool-card" onclick="openTool('notes')">
                        <i class="fas fa-note-sticky"></i>
                        <span>Notes</span>
                        <p class="tool-description">Create and organize your study notes</p>
                    </div>
                    <div class="tool-card" onclick="openTool('draw')">
                        <i class="fas fa-paintbrush"></i>
                        <span>Draw</span>
                        <p class="tool-description">Sketch diagrams and illustrations</p>
                    </div>
                    <div class="tool-card" onclick="openTool('calc')">
                        <i class="fas fa-calculator"></i>
                        <span>Calculator</span>
                        <p class="tool-description">Solve mathematical problems</p>
                    </div>
                    <div class="tool-card" onclick="openTool('library')">
                        <i class="fas fa-book"></i>
                        <span>Library</span>
                        <p class="tool-description">Access your study materials</p>
                    </div>
                </div>
            </section>

            <!-- Recent Notes -->
            <section class="recent-notes">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Recent Notes</h2>
                        <p class="section-subtitle">Your latest study notes and materials</p>
                    </div>
                    <button class="btn btn-primary" onclick="createNewNote()">
                        <i class="fas fa-plus"></i>
                        <span>New Note</span>
                    </button>
                </div>
                <div class="notes-grid" id="recentNotes">
                    <!-- Notes will be displayed here -->
                </div>
            </section>
        </div>
    </main>
</div>

<!-- Modals -->
<!-- Note Editor Modal -->
<div id="noteEditorModal" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <input type="text" class="form-control title-input" placeholder="Note Title">
            </div>
            <div class="editor-tools">
                <button class="btn btn-icon" title="Bold">
                    <i class="fas fa-bold"></i>
                </button>
                <button class="btn btn-icon" title="Italic">
                    <i class="fas fa-italic"></i>
                </button>
                <button class="btn btn-icon" title="List">
                    <i class="fas fa-list"></i>
                </button>
                <button class="btn btn-icon" title="Image">
                    <i class="fas fa-image"></i>
                </button>
                <button class="btn btn-icon" title="Draw">
                    <i class="fas fa-paintbrush"></i>
                </button>
                <button class="btn btn-icon" title="Formula">
                    <i class="fas fa-function"></i>
                </button>
            </div>
            <button class="btn-close" onclick="closeModal('noteEditorModal')" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="noteEditor"></div>
        </div>
        <div class="modal-footer">
            <div class="note-meta">
                <select class="form-control" id="noteCategory">
                    <option value="">Select Category</option>
                </select>
                <button class="btn btn-icon" title="Add Tag">
                    <i class="fas fa-tag"></i>
                </button>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeModal('noteEditorModal')">Cancel</button>
                <button class="btn btn-primary" onclick="saveNote()">
                    <i class="fas fa-save"></i>
                    <span>Save Note</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Drawing Board Modal -->
<div id="drawingBoardModal" class="modal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <div class="modal-title">
                <h2>Drawing Board</h2>
                <p class="modal-subtitle">Create diagrams and illustrations</p>
            </div>
            <div class="drawing-tools">
                <button class="btn btn-icon active" title="Pen" onclick="setTool('pen')">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="btn btn-icon" title="Eraser" onclick="setTool('eraser')">
                    <i class="fas fa-eraser"></i>
                </button>
                <div class="tool-separator"></div>
                <input type="color" id="strokeColor" title="Color" value="#4f46e5">
                <input type="range" id="strokeWidth" min="1" max="20" value="2" title="Stroke Width">
            </div>
            <button class="btn-close" onclick="closeModal('drawingBoardModal')" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <canvas id="drawingCanvas"></canvas>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="clearCanvas()">
                <i class="fas fa-trash"></i>
                <span>Clear</span>
            </button>
            <button class="btn btn-primary" onclick="saveDrawing()">
                <i class="fas fa-save"></i>
                <span>Save</span>
            </button>
        </div>
    </div>
</div>

<!-- Calculator Modal -->
<div id="calculatorModal" class="modal">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <div class="modal-title">
                <h2>Calculator</h2>
                <p class="modal-subtitle">Solve mathematical problems</p>
            </div>
            <button class="btn-close" onclick="closeModal('calculatorModal')" title="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="calculator">
                <div class="calc-display">
                    <input type="text" id="calcInput" class="form-control" readonly>
                </div>
                <div class="calc-buttons">
                    <!-- Calculator buttons will be added dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fabric@5.3.0/dist/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/tippy.js@6"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
@endpush
@endsection 