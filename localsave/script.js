document.addEventListener('DOMContentLoaded', () => {
const savedFilesContainer = document.getElementById('savedFiles');
const savedFilesKey = 'savedFiles';
let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];

const categoriesKey = 'categories';
let categories = JSON.parse(localStorage.getItem(categoriesKey)) || [];


document.getElementById('moduleForm').addEventListener('submit', (event) => {
    event.preventDefault(); // Prevent default form submission behavior

    const moduleNameElement = document.getElementById('name');
    const thumbnailUrlElement = document.getElementById('icon');
    const noteElement = document.getElementById('note');
    const categoryElement = document.getElementById('category');

    if (!moduleNameElement || !thumbnailUrlElement || !noteElement) {
        console.error('One or more required elements not found');
        return;
    }

    const moduleName = moduleNameElement.value.trim();
    const thumbnailUrl = thumbnailUrlElement.value.trim();
    const note = noteElement.value.trim();
    const category = categoryElement.value.trim();

    if (!moduleName || !thumbnailUrl || !note) {
        alert('Please fill in all fields');
        return;
    }

    const id = Date.now().toString();

    savedFiles.push({ id, name: moduleName, thumbnailUrl, note, category });
    localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
    displaySavedFiles();

    moduleNameElement.value = '';
    thumbnailUrlElement.value = '';
    noteElement.value = '';
    categoryElement.value = '';

    alert('Module added successfully');
});


function displaySavedFiles() {
    savedFilesContainer.innerHTML = '';

    savedFiles.forEach((file) => {
        const fileElement = createFileElement(file);
        savedFilesContainer.appendChild(fileElement);
    });
}
displaySavedFiles();

document.getElementById('categoryForm').addEventListener('submit', (event) => {
    event.preventDefault();

    console.log('Form submitted');

    const newCategory = document.getElementById('newCategory').value.trim();
    const categoryColor = document.getElementById('categoryColor').value.trim();

    if (!newCategory) {
        alert('Please enter a category name');
        return;
    }

    categories.push({ id: Date.now().toString(), name: newCategory, color: categoryColor });
    localStorage.setItem(categoriesKey, JSON.stringify(categories));

    populateCategoryDropdown(); // Add this line to update the dropdown
    populateCategoryList(); // Update the list of categories

    document.getElementById('newCategory').value = '';
    document.getElementById('categoryColor').value = '#ffffff';

    alert('Category added successfully');
});


function populateCategoryDropdown() {
    const categoryDropdown = document.getElementById('categoryDropdown');
    const dropdownSelected = categoryDropdown.querySelector('.dropdown-selected');
    const categoryList = categoryDropdown.querySelector('.dropdown-list');

    if (!dropdownSelected || !categoryList) {
        return; // Ensure the elements exist
    }

    dropdownSelected.textContent = 'Select Category (optional)'; // Reset the dropdown title
    categoryList.innerHTML = ''; // Clear the category list

    categories.forEach(category => {
        const categoryItem = document.createElement('div');
        categoryItem.classList.add('category-item');

        const categoryName = document.createElement('span');
        categoryName.textContent = category.name;
        categoryName.classList.add('category-name');

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.classList.add('deleteButton', 'small');
        deleteButton.addEventListener('click', () => {
            deleteCategory(category.id);
        });

        categoryItem.appendChild(categoryName);
        categoryItem.appendChild(deleteButton);
        categoryList.appendChild(categoryItem);

        categoryItem.addEventListener('click', () => {
            dropdownSelected.textContent = category.name; // Update dropdown-selected with selected category
            categoryDropdown.classList.remove('open');
        });
    });
}


function createFileElement(file) {
    const fileElement = document.createElement('div');
    fileElement.classList.add('savedFile');
    const category = categories.find(c => c.name === file.category);
    if (category) {
        fileElement.style.backgroundColor = category.color;
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
    deleteButton.addEventListener('click', (event) => {
        event.stopPropagation(); // Stop event propagation
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
function deleteCategory(categoryId) {
    if (confirm("Are you sure you want to delete this category?")) {
        categories = categories.filter(category => category.id !== categoryId);
        localStorage.setItem('categories', JSON.stringify(categories));
        populateCategoryDropdown();
        populateCategoryList(); // Update the list of categories
    }
}

  function updateCategoryList() {
    // Get the category list element
    const categoryList = document.getElementById('categoryList');
  
    // Clear existing options
    categoryList.innerHTML = '';
  
    // Get categories from storage
    let categories = JSON.parse(localStorage.getItem('categories')) || [];
  
    // Add each category to the dropdown list
    categories.forEach(category => {
      const option = document.createElement('option');
      option.value = category.id;
      option.text = category.name;
      categoryList.appendChild(option);
    });
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
        deleteButton.textContent = 'Delete';
        deleteButton.classList.add('deleteButton');
        deleteButton.addEventListener('click', () => {
            deleteCategory(category.id);
        });

        categoryItem.appendChild(categoryName);
        categoryItem.appendChild(deleteButton);
        categoriesList.appendChild(categoryItem);
    });
}

  
  // Call populateCategoryList initially to populate the list
  populateCategoryList();
  
 function populateCategoryDropdown() {
        const categoryDropdown = document.getElementById('categoryDropdown');
        const dropdownSelected = categoryDropdown.querySelector('.dropdown-selected');
        const categoryDropdownList = categoryDropdown.querySelector('.dropdown-list');

        dropdownSelected.addEventListener('click', () => {
            categoryDropdownList.classList.toggle('open');
        });

        categories.forEach(category => {
            const option = document.createElement('div');
            option.classList.add('dropdown-option');
            option.textContent = category.name;

            option.addEventListener('click', () => {
                dropdownSelected.textContent = category.name + ' ' + ' &#9662;'; // Update selected category text
                categoryDropdownList.classList.remove('open'); // Hide the dropdown list
            });

            categoryDropdownList.appendChild(option);
        });
    } 
// Call populateCategoryDropdown initially to populate the list
populateCategoryDropdown();

// Toggle the dropdown list when the dropdown selected area is clicked
document.querySelector('.dropdown-selected').addEventListener('click', function() {
    document.querySelector('.custom-dropdown').classList.toggle('open');
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
});