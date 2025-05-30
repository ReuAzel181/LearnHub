document.addEventListener('DOMContentLoaded', () => {
    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Initialize Flatpickr for deadline input
    flatpickr('#deadline', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        minDate: 'today',
    });

    // Load initial data
    loadModules();
    loadCategories();
    loadTrashedModules();

    // Initialize drag and drop for thumbnails
    const dropzone = document.getElementById('thumbnailDropzone');
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            handleThumbnailUpload(file);
        }
    });

    // Event listeners
    document.getElementById('moduleForm').addEventListener('submit', handleModuleSubmit);
    document.getElementById('categoryForm').addEventListener('submit', handleCategorySubmit);
    document.getElementById('toggleDeletedFilesButton').addEventListener('click', toggleDeletedFiles);
});

// Module functions
async function loadModules() {
    try {
        const response = await fetch('/api/modules');
        const modules = await response.json();
        displayModules(modules);
    } catch (error) {
        console.error('Error loading modules:', error);
    }
}

function displayModules(modules) {
    const container = document.getElementById('savedFiles');
    container.innerHTML = modules.map(module => `
        <div class="module-card" style="border-left: 4px solid ${module.category_color || '#ddd'}">
            ${module.icon ? `<img src="${module.icon}" alt="${module.name}">` : ''}
            <h3>${module.name}</h3>
            ${module.note ? `<p>${module.note}</p>` : ''}
            <div class="module-actions">
                ${module.file_path ? `<a href="/storage/${module.file_path}" class="btn" target="_blank">View File</a>` : ''}
                <button class="btn" onclick="editModule(${module.id})">Edit</button>
                <button class="btn" onclick="deleteModule(${module.id})">Delete</button>
            </div>
        </div>
    `).join('');
}

async function handleModuleSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);

    try {
        const response = await fetch('/api/modules', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });

        if (response.ok) {
            e.target.reset();
            loadModules();
            alert('Module added successfully');
        } else {
            const error = await response.json();
            alert(error.message || 'Error adding module');
        }
    } catch (error) {
        console.error('Error creating module:', error);
        alert('Error creating module');
    }
}

async function deleteModule(id) {
    if (!confirm('Are you sure you want to delete this module?')) return;

    try {
        const response = await fetch(`/api/modules/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            loadModules();
            loadTrashedModules();
            alert('Module moved to trash');
        }
    } catch (error) {
        console.error('Error deleting module:', error);
        alert('Error deleting module');
    }
}

// Category functions
async function loadCategories() {
    try {
        const response = await fetch('/api/categories');
        const categories = await response.json();
        updateCategoryList(categories);
        updateCategorySelect(categories);
    } catch (error) {
        console.error('Error loading categories:', error);
    }
}

function updateCategoryList(categories) {
    const categoryList = document.getElementById('categoryList');
    categoryList.innerHTML = categories.map(category => `
        <div class="category-item" style="border-left: 4px solid ${category.color || '#ddd'}">
            <span>${category.name}</span>
            <div class="category-actions">
                <button class="btn" onclick="editCategory(${category.id})">Edit</button>
                <button class="btn" onclick="deleteCategory(${category.id})">Delete</button>
            </div>
        </div>
    `).join('');
}

function updateCategorySelect(categories) {
    const select = document.getElementById('category');
    select.innerHTML = '<option value="">Select Category (optional)</option>' + 
        categories.map(category => `
            <option value="${category.id}">${category.name}</option>
        `).join('');
}

async function handleCategorySubmit(e) {
    e.preventDefault();
    const formData = {
        name: document.getElementById('newCategory').value.trim(),
        color: document.getElementById('categoryColor').value.trim()
    };

    try {
        const response = await fetch('/api/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            e.target.reset();
            loadCategories();
            alert('Category added successfully');
        } else {
            const error = await response.json();
            alert(error.message || 'Error adding category');
        }
    } catch (error) {
        console.error('Error creating category:', error);
        alert('Error creating category');
    }
}

// Deleted files functions
async function loadTrashedModules() {
    try {
        const response = await fetch('/api/trashed-modules');
        const modules = await response.json();
        updateDeletedFilesList(modules);
    } catch (error) {
        console.error('Error loading trashed modules:', error);
    }
}

function updateDeletedFilesList(modules) {
    const container = document.getElementById('deletedFilesList');
    container.innerHTML = modules.map(module => `
        <div class="deleted-item">
            <span>${module.name}</span>
            <div class="deleted-actions">
                <button class="btn" onclick="restoreModule(${module.id})">Restore</button>
                <button class="btn" onclick="permanentlyDeleteModule(${module.id})">Delete Permanently</button>
            </div>
        </div>
    `).join('');
}

function toggleDeletedFiles() {
    const section = document.getElementById('deletedFilesSection');
    section.style.display = section.style.display === 'none' ? 'block' : 'none';
}

async function restoreModule(id) {
    try {
        const response = await fetch(`/api/modules/${id}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            loadModules();
            loadTrashedModules();
            alert('Module restored successfully');
        }
    } catch (error) {
        console.error('Error restoring module:', error);
        alert('Error restoring module');
    }
}

async function permanentlyDeleteModule(id) {
    if (!confirm('Are you sure you want to permanently delete this module? This action cannot be undone.')) return;

    try {
        const response = await fetch(`/api/modules/${id}/force`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (response.ok) {
            loadTrashedModules();
            alert('Module permanently deleted');
        }
    } catch (error) {
        console.error('Error permanently deleting module:', error);
        alert('Error permanently deleting module');
    }
}

// Utility functions
function handleThumbnailUpload(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('icon').value = e.target.result;
    };
    reader.readAsDataURL(file);
}

function editModule(id) {
    // Open edit modal and load module data
    const modal = document.getElementById('editModal');
    modal.style.display = 'block';
    
    // Load module data and populate form
    fetch(`/api/modules/${id}`)
        .then(response => response.json())
        .then(module => {
            document.getElementById('editNoteTextarea').value = module.note || '';
            document.getElementById('saveEditButton').onclick = () => saveModuleEdit(id);
        })
        .catch(error => {
            console.error('Error loading module:', error);
            alert('Error loading module data');
        });
}

async function saveModuleEdit(id) {
    const note = document.getElementById('editNoteTextarea').value;
    
    try {
        const response = await fetch(`/api/modules/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ note })
        });

        if (response.ok) {
            document.getElementById('editModal').style.display = 'none';
            loadModules();
            alert('Module updated successfully');
        }
    } catch (error) {
        console.error('Error updating module:', error);
        alert('Error updating module');
    }
} 