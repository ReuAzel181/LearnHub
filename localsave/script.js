const savedFilesContainer = document.getElementById('savedFiles');
const savedFilesKey = 'savedFiles';
let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];

displaySavedFiles();

document.getElementById('moduleForm').addEventListener('submit', (event) => {
    event.preventDefault();

    const moduleName = document.getElementById('name').value.trim();
    const thumbnailUrl = document.getElementById('icon').value.trim();
    const note = document.getElementById('note').value.trim();
    const category = document.getElementById('category').value.trim();

    if (!moduleName || !thumbnailUrl || !note) {
        alert('Please fill in all fields');
        return;
    }

    const id = Date.now().toString();

    savedFiles.push({ id, name: moduleName, thumbnailUrl, note, category });
    localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
    displaySavedFiles();

    document.getElementById('name').value = '';
    document.getElementById('icon').value = '';
    document.getElementById('note').value = '';
    document.getElementById('category').value = '';

    alert('Module added successfully');
});

function displaySavedFiles() {
    savedFilesContainer.innerHTML = '';

    savedFiles.forEach((file) => {
        const fileElement = createFileElement(file);
        savedFilesContainer.appendChild(fileElement);
    });
}

function createFileElement(file) {
    const fileElement = document.createElement('div');
    fileElement.classList.add('savedFile');
    if (file.category) {
        fileElement.classList.add(file.category);
    }
    fileElement.dataset.id = file.id;

    const editIcon = document.createElement('span');
    editIcon.textContent = '✎';
    editIcon.classList.add('edit-icon');
    editIcon.addEventListener('click', () => {
        openEditModal(file.id);
    });
    fileElement.appendChild(editIcon);

    const imgElement = document.createElement('img');
    imgElement.src = file.thumbnailUrl || '../images/download.png'; // Use placeholder if no image
    imgElement.alt = file.name;
    imgElement.classList.add('thumbnail');
    imgElement.contentEditable = true; // Make thumbnail editable
    imgElement.addEventListener('input', () => {
        file.thumbnailUrl = imgElement.src;
        localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
    });
    fileElement.appendChild(imgElement);

    const titleElement = document.createElement('h3');
    titleElement.textContent = file.name;
    titleElement.contentEditable = true; // Make title editable
    titleElement.addEventListener('input', () => {
        file.name = titleElement.textContent;
        localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
    });
    fileElement.appendChild(titleElement);

    const noteElement = document.createElement('p');
    noteElement.textContent = file.note;
    fileElement.appendChild(noteElement);

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Delete';
    deleteButton.classList.add('deleteButton');
    deleteButton.addEventListener('click', () => {
        savedFiles = savedFiles.filter(savedFile => savedFile.id !== file.id);
        localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
        displaySavedFiles();
        alert('Module deleted successfully');
    });
    fileElement.appendChild(deleteButton);

    return fileElement;
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

function openEditModal(noteId) {
    const file = savedFiles.find(file => file.id === noteId);
    if (!file) return;

    const editNoteTextarea = document.getElementById('editNoteTextarea');
    editNoteTextarea.value = file.note;

    const saveEditButton = document.getElementById('saveEditButton');
    saveEditButton.onclick = () => {
        const editedNote = editNoteTextarea.value.trim();
        if (!editedNote) {
            alert('Please enter a note.');
            return;
        }
        file.note = editedNote;
        localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
        displaySavedFiles();
        closeModal('editModal');
    };

    openModal('editModal');
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'none';
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = 'block';
}
