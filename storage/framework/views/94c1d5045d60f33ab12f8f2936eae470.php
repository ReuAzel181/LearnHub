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
            <div id="savedNotesList"></div>
        </div>
    </div>

    <!-- Tool Components -->
    <?php echo $__env->make('components.notes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.draw', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.calculator', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.dictionary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</main>

<style>
.main-content {
    position: fixed;
    top: 60px;
    right: 0;
    width: calc(100% - 280px);
    height: calc(100vh - 60px);
    padding: 1.5rem;
    background: linear-gradient(135deg, #f6f6f7 0%, #e9ecf4 100%);
    overflow-y: auto;
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

.saved-note-date {
    font-size: 0.75rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .main-content {
        width: 100%;
        margin: 0;
        top: 60px;
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
</style> <?php /**PATH D:\XAMPP\htdocs\LearnHub\resources\views/components/layout/main-content.blade.php ENDPATH**/ ?>