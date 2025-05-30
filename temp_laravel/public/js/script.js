document.addEventListener('DOMContentLoaded', () => {
    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
    
    // Initialize Flatpickr for deadline input with time
    flatpickr('#deadline', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        minDate: 'today',
        time_24hr: true,
        onChange: (selectedDates) => {
            if (selectedDates[0]) {
                const deadline = selectedDates[0];
                updateDeadlineStatus(deadline);
            }
        }
    });

    // Initialize tooltips
    tippy('[data-tippy-content]', {
        theme: 'light-border',
        animation: 'shift-away'
    });

    // Initialize Sortable for module grid
    new Sortable(document.getElementById('savedFiles'), {
        animation: 150,
        ghostClass: 'module-card--ghost',
        chosenClass: 'module-card--chosen',
        dragClass: 'module-card--drag',
        onEnd: updateModuleOrder
    });

    // Load initial data with loading states
    loadModules();
    loadCategories();
    loadTrashedModules();
    initializeCalendar();

    // Initialize drag and drop for thumbnails
    initializeDropzone();

    // Event listeners
    setupEventListeners();
});

// Initialize dropzone
function initializeDropzone() {
    const dropzone = document.getElementById('thumbnailDropzone');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', (e) => {
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            handleThumbnailUpload(file);
        }
    });
}

// Setup event listeners
function setupEventListeners() {
    document.getElementById('moduleForm').addEventListener('submit', handleModuleSubmit);
    document.getElementById('categoryForm').addEventListener('submit', handleCategorySubmit);
    document.getElementById('toggleDeletedFilesButton').addEventListener('click', toggleDeletedFiles);

    // Search functionality
    const searchInput = document.getElementById('moduleSearch');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
}

// Module functions
async function loadModules() {
    const container = document.getElementById('savedFiles');
    container.classList.add('loading');

    try {
        const response = await axios.get('/api/modules');
        displayModules(response.data);
    } catch (error) {
        showNotification('Error loading modules', 'error');
        console.error('Error loading modules:', error);
    } finally {
        container.classList.remove('loading');
    }
}

function displayModules(modules) {
    const container = document.getElementById('savedFiles');
    container.innerHTML = modules.map(module => `
        <div class="module-card animate-slide-in" data-id="${module.id}">
            ${module.icon ? `
                <img src="${module.icon}" alt="${module.name}" class="module-card__image">
            ` : ''}
            <div class="module-card__content">
                <h3 class="module-card__title">${module.name}</h3>
                ${module.category ? `
                    <span class="category-tag" style="background-color: ${module.category_color}">
                        ${module.category}
                    </span>
                ` : ''}
                ${module.note ? `<p class="module-card__note">${module.note}</p>` : ''}
                <div class="module-card__actions">
                    ${module.file_path ? `
                        <button class="btn btn-primary" onclick="viewFile('${module.file_path}')" 
                                data-tippy-content="View attached file">
                            <i class="fas fa-file"></i>
                        </button>
                    ` : ''}
                    <button class="btn btn-secondary" onclick="editModule(${module.id})"
                            data-tippy-content="Edit module">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="deleteModule(${module.id})"
                            data-tippy-content="Move to trash">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    // Reinitialize tooltips
    tippy('[data-tippy-content]', {
        theme: 'light-border',
        animation: 'shift-away'
    });
}

async function handleModuleSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.classList.add('loading');

    const formData = new FormData(form);

    try {
        const response = await axios.post('/api/modules', formData);
        form.reset();
        loadModules();
        showNotification('Module added successfully', 'success');
    } catch (error) {
        showNotification(error.response?.data?.message || 'Error adding module', 'error');
    } finally {
        submitButton.classList.remove('loading');
    }
}

// Category functions
async function loadCategories() {
    try {
        const response = await axios.get('/api/categories');
        updateCategoryList(response.data);
        updateCategorySelect(response.data);
    } catch (error) {
        showNotification('Error loading categories', 'error');
    }
}

function updateCategoryList(categories) {
    const categoryList = document.getElementById('categoryList');
    categoryList.innerHTML = categories.map(category => `
        <div class="category-item animate-slide-in" data-id="${category.id}">
            <div class="category-color" style="background-color: ${category.color}"></div>
            <span>${category.name}</span>
            <div class="category-actions">
                <button class="btn btn-secondary" onclick="editCategory(${category.id})"
                        data-tippy-content="Edit category">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger" onclick="deleteCategory(${category.id})"
                        data-tippy-content="Delete category">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');

    // Reinitialize tooltips
    tippy('[data-tippy-content]', {
        theme: 'light-border',
        animation: 'shift-away'
    });
}

// Calendar functions
function initializeCalendar() {
    const calendar = document.getElementById('calendarWidget');
    const date = new Date();
    
    calendar.innerHTML = `
        <div class="calendar-header">
            <div class="calendar-title">${formatMonth(date)}</div>
            <div class="calendar-nav">
                <button onclick="changeMonth(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="changeMonth(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        ${generateCalendarGrid(date)}
    `;
}

// Utility functions
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} animate-slide-in`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 
                            type === 'error' ? 'exclamation-circle' : 
                            'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('toast-hide');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

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

function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    const modules = document.querySelectorAll('.module-card');
    
    modules.forEach(module => {
        const title = module.querySelector('.module-card__title').textContent.toLowerCase();
        const note = module.querySelector('.module-card__note')?.textContent.toLowerCase() || '';
        const isVisible = title.includes(searchTerm) || note.includes(searchTerm);
        module.style.display = isVisible ? 'block' : 'none';
    });
}

function updateDeadlineStatus(deadline) {
    const now = new Date();
    const daysUntil = Math.ceil((deadline - now) / (1000 * 60 * 60 * 24));
    
    let statusClass = 'status-normal';
    if (daysUntil <= 1) {
        statusClass = 'status-urgent';
    } else if (daysUntil <= 3) {
        statusClass = 'status-warning';
    }
    
    document.getElementById('deadlineContainer').className = statusClass;
}

// Handle file preview
function viewFile(filePath) {
    const fileExtension = filePath.split('.').pop().toLowerCase();
    const previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
    
    if (previewableTypes.includes(fileExtension)) {
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close">&times;</span>
                ${fileExtension === 'pdf' ? `
                    <iframe src="/storage/${filePath}" width="100%" height="600px"></iframe>
                ` : `
                    <img src="/storage/${filePath}" alt="Preview" style="max-width: 100%;">
                `}
            </div>
        `;
        document.body.appendChild(modal);
        modal.style.display = 'block';
        
        modal.querySelector('.close').onclick = () => {
            modal.remove();
        };
    } else {
        window.open(`/storage/${filePath}`, '_blank');
    }
}

// Export functions for global access
window.editModule = editModule;
window.deleteModule = deleteModule;
window.editCategory = editCategory;
window.deleteCategory = deleteCategory;
window.changeMonth = changeMonth;
window.viewFile = viewFile; 