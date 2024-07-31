document.addEventListener('DOMContentLoaded', () => {
    flatpickr('#deadline', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        minDate: 'today',
    });

    const savedFilesContainer = document.getElementById('savedFiles');
    const savedFilesKey = 'savedFiles';
    let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];
    
    const deletedFilesKey = 'deletedFiles';
    let deletedFiles = JSON.parse(localStorage.getItem(deletedFilesKey)) || [];

    const categoriesKey = 'categories';
    let categories = JSON.parse(localStorage.getItem(categoriesKey)) || [];
    
    document.getElementById('moduleForm').addEventListener('submit', async (event) => {
        event.preventDefault();
    
        const moduleNameElement = document.getElementById('name');
        const thumbnailUrlElement = document.getElementById('icon');
        const noteElement = document.getElementById('note');
        const fileInput = document.getElementById('fileInput');
        const categoryDropdown = document.getElementById('category');
    
        if (!moduleNameElement || !thumbnailUrlElement || !noteElement) {
            console.error('One or more required elements not found');
            return;
        }
    
        const moduleName = moduleNameElement.value.trim();
        const thumbnailUrl = thumbnailUrlElement.value.trim();
        const note = noteElement.value.trim();
        const categoryId = categoryDropdown ? categoryDropdown.value.trim() : '';
    
        if (!moduleName || !thumbnailUrl || !note) {
            alert('Please fill in all fields');
            return;
        }
    
        let fileDataUrl = '';
        let fileName = '';
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            fileName = file.name;
            try {
                fileDataUrl = await readFileAsDataURL(file);
            } catch (error) {
                console.error('Error reading file:', error);
                alert('Error reading file. Please try again.');
                return;
            }
        }
    
        const category = categories.find(c => c.id === categoryId);
        const categoryColor = category ? category.color : '#ffffff';
    
        const id = Date.now().toString();
    
        const newFile = {
            id,
            name: moduleName,
            thumbnailUrl,
            note,
            category: category ? category.name : '',
            categoryColor,
            fileName,
            fileDataUrl
        };
    
        savedFiles.push(newFile);
        localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
        displaySavedFiles();
    
        moduleNameElement.value = '';
        thumbnailUrlElement.value = '';
        noteElement.value = '';
        fileInput.value = '';
        categoryDropdown.value = '';
    
        alert('Module added successfully');
    });
    
    function readFileAsDataURL(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }
    
    function displaySavedFiles() {
        savedFilesContainer.innerHTML = '';
        savedFiles.forEach((file) => {
            const category = categories.find(c => c.name === file.category);
            const categoryColor = category ? category.color : '#ffffff';
            const fileElement = createFileElement(file, categoryColor);
            savedFilesContainer.appendChild(fileElement);
        });
    }
    function createFileElement(file, categoryColor) {
        const fileElement = document.createElement('div');
        fileElement.classList.add('savedFile');
        if (categoryColor) {
            fileElement.style.backgroundColor = categoryColor;
        }
        fileElement.dataset.id = file.id;

        const editIcon = document.createElement('span');
        editIcon.textContent = '✎';
        editIcon.classList.add('edit-icon');
        editIcon.addEventListener('click', () => {
            openEditModal(file.id);
        });
        fileElement.appendChild(editIcon);

        const moduleNameElement = document.createElement('h3');
        moduleNameElement.textContent = file.name;
        moduleNameElement.contentEditable = true;
        moduleNameElement.addEventListener('input', () => {
            file.name = moduleNameElement.textContent;
            localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
        });
        fileElement.appendChild(moduleNameElement);

        const fileNameElement = document.createElement('p');
        fileElement.appendChild(fileNameElement);

        const noteElement = document.createElement('p');
        noteElement.textContent = file.note;
        fileElement.appendChild(noteElement);

        if (file.fileDataUrl) {
            if (file.fileDataUrl.startsWith('data:image/')) {
                const imgElement = document.createElement('img');
                imgElement.src = file.fileDataUrl;
                imgElement.alt = file.fileName;
                imgElement.classList.add('thumbnail');
                fileElement.appendChild(imgElement);
            } else {
                const fileLink = document.createElement('a');
                fileLink.href = file.fileDataUrl;
                fileLink.textContent = file.fileName || 'Download File';
                fileLink.download = file.fileName || 'download';
                fileElement.appendChild(fileLink);
            }
        }

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('deleteModuleButton');
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation();
            deleteModule(file.id);
        });
        deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i> Delete';
        fileElement.appendChild(deleteButton);

        return fileElement;
    }

    
    displaySavedFiles();

    document.getElementById('categoryForm').addEventListener('submit', (event) => {
        event.preventDefault();
    
        const newCategory = document.getElementById('newCategory').value.trim();
        const categoryColor = document.getElementById('categoryColor').value.trim();
    
        if (!newCategory) {
            alert('Please enter a category name');
            return;
        }
    
        const id = Date.now().toString();
        categories.push({ id, name: newCategory, color: categoryColor });
        localStorage.setItem(categoriesKey, JSON.stringify(categories));
    
        populateCategoryDropdown();
        populateCategoryList();
    
        document.getElementById('newCategory').value = '';
        document.getElementById('categoryColor').value = '#ffffff';
    
        alert('Category added successfully');
    });

    function deleteCategory(categoryId) {
        if (confirm("Are you sure you want to delete this category?")) {
            categories = categories.filter(category => category.id !== categoryId);
            localStorage.setItem(categoriesKey, JSON.stringify(categories));
            populateCategoryDropdown();
            populateCategoryList();
        }
    }

    function populateCategoryDropdown() {
        const categoryDropdown = document.getElementById('category');
        if (!categoryDropdown) {
            console.error('Category dropdown element not found');
            return;
        }
    
        categoryDropdown.innerHTML = '';
    
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Category (optional)';
        categoryDropdown.appendChild(defaultOption);
    
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categoryDropdown.appendChild(option);
        });
    }

    function deleteModule(moduleId) {
        if (confirm("Are you sure you want to delete this module?")) {
            const moduleIndex = savedFiles.findIndex(module => module.id === moduleId);
            if (moduleIndex !== -1) {
                deletedFiles.push(savedFiles[moduleIndex]);
                savedFiles.splice(moduleIndex, 1);
                localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
                localStorage.setItem(deletedFilesKey, JSON.stringify(deletedFiles));
                displaySavedFiles();
                displayDeletedFiles();
                alert('Module deleted successfully');
            } else {
                alert('Module not found');
            }
        }
    }
    
    

    function restoreModule(moduleId) {
        const moduleToRestore = deletedFiles.find(file => file.id === moduleId);
        if (moduleToRestore) {
            savedFiles.push(moduleToRestore);
            localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
            deletedFiles = deletedFiles.filter(file => file.id !== moduleId);
            localStorage.setItem(deletedFilesKey, JSON.stringify(deletedFiles));
            displaySavedFiles();
            displayDeletedFiles();
            alert('Module restored successfully');
        }
    }

    const thumbnailDropzone = document.getElementById('thumbnailDropzone');

    thumbnailDropzone.addEventListener('dragover', function(event) {
        event.preventDefault();
        if (event.target.id === 'icon' && event.dataTransfer.types.includes('Files')) {
            const file = event.dataTransfer.files[0];
            if (file.type.startsWith('image/')) {
                thumbnailDropzone.classList.add('dragover');
            }
        }
    });

    thumbnailDropzone.addEventListener('dragleave', function(event) {
        event.preventDefault();
        if (event.target.id === 'icon') {
            thumbnailDropzone.classList.remove('dragover');
        }
    });

    thumbnailDropzone.addEventListener('drop', function(event) {
        event.preventDefault();
        thumbnailDropzone.classList.remove('dragover');

        const droppedFile = event.dataTransfer.files[0];
        if (droppedFile.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function() {
                const thumbnailUrl = reader.result;
                document.getElementById('icon').value = thumbnailUrl;
            };
            reader.readAsDataURL(droppedFile);
        }
    });

    function createModuleElement(module) {
        const moduleElement = document.createElement('div');
        moduleElement.classList.add('module');
    
        const moduleName = document.createElement('h3');
        moduleName.textContent = module.name;
        moduleElement.appendChild(moduleName);
    
        const moduleUrl = document.createElement('p');
        moduleUrl.textContent = 'URL: ' + module.url;
        moduleElement.appendChild(moduleUrl);
    
        const moduleNote = document.createElement('p');
        moduleNote.textContent = 'Note: ' + module.note;
        moduleElement.appendChild(moduleNote);
    
        const moduleCategory = document.createElement('p');
        moduleCategory.textContent = 'Category: ' + (module.category || 'None');
        moduleElement.appendChild(moduleCategory);
    
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', () => {
            deleteModule(module.id);
        });
        moduleElement.appendChild(deleteButton);
    
        if (module.deadline) {
            const deadline = new Date(module.deadline);
            const deadlineText = document.createElement('p');
            deadlineText.textContent = 'Deadline: ' + deadline.toLocaleString();
            moduleElement.appendChild(deadlineText);
    
            if (deadline < new Date()) {
                const deadlinePassedText = document.createElement('p');
                deadlinePassedText.textContent = 'Deadline Passed';
                deadlinePassedText.classList.add('deadline-passed');
                moduleElement.appendChild(deadlinePassedText);
            }
        }
    
        return moduleElement;
    }

    function populateCategoryList() {
        const categoriesList = document.getElementById('categoryList');
        categoriesList.innerHTML = '';

        categories.forEach(category => {
            const categoryItem = document.createElement('div');
            categoryItem.classList.add('category-item');

            const categoryName = document.createElement('span');
            categoryName.textContent = category.name;

            const deleteButton = document.createElement('button');
            deleteButton.classList.add('deleteButton');
            deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteButton.addEventListener('click', () => {
                deleteCategory(category.id);
            });
            categoryItem.appendChild(categoryName);
            categoryItem.appendChild(deleteButton);
            categoriesList.appendChild(categoryItem);
        });
    }

    populateCategoryDropdown();
    populateCategoryList();

    const deletedFilesSection = document.getElementById('deletedFilesSection');
    const deletedFilesList = document.getElementById('deletedFilesList');
    const toggleDeletedFilesButton = document.getElementById('toggleDeletedFilesButton');

    function toggleDeletedFilesSection() {
        if (deletedFilesSection.style.display === 'none') {
            deletedFilesSection.style.display = 'block';
        } else {
            deletedFilesSection.style.display = 'none';
        }
    }

    toggleDeletedFilesButton.addEventListener('click', toggleDeletedFilesSection);

    function displayDeletedFiles() {
        deletedFilesList.innerHTML = '';
        deletedFiles.forEach(file => {
            const fileElement = document.createElement('div');
            fileElement.textContent = file.name;
            
            const restoreButton = document.createElement('button');
            restoreButton.textContent = 'Restore';
            restoreButton.addEventListener('click', () => {
                restoreModule(file.id);
            });
            fileElement.appendChild(restoreButton);
            
            deletedFilesList.appendChild(fileElement);
        });
    }

    displayDeletedFiles();

    document.querySelector('.dropdown-selected').addEventListener('click', function() {
        document.querySelector('.custom-dropdown').classList.toggle('open');
    });

    function openEditModal(noteId) {
        const file = savedFiles.find(file => file.id === noteId);
        if (!file) return;

        const editNoteModal = document.getElementById('editNoteModal');
        const editNoteContent = document.getElementById('editNoteContent');
        const editNoteImage = document.getElementById('editNoteImage');
        const editNoteTitle = document.getElementById('editNoteTitle');

        if (!editNoteModal || !editNoteContent || !editNoteImage || !editNoteTitle) {
            console.error('One or more required elements not found in the modal');
            return;
        }

        editNoteContent.value = file.note;
        editNoteImage.src = file.thumbnailUrl || '../images/download.png';
        editNoteTitle.textContent = file.name;

        const saveChangesButton = document.getElementById('saveChangesButton');
        if (saveChangesButton) {
            saveChangesButton.onclick = function() {
                file.note = editNoteContent.value;
                file.thumbnailUrl = editNoteImage.src;
                file.name = editNoteTitle.textContent;

                localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
                displaySavedFiles();
                editNoteModal.style.display = 'none';
            };
        }

        const closeModalButton = document.querySelector('.close');
        if (closeModalButton) {
            closeModalButton.onclick = function() {
                editNoteModal.style.display = 'none';
            };
        }

        editNoteModal.style.display = 'block';
    }
});
