const savedFilesContainer = document.getElementById('savedFiles');
const savedFilesKey = 'savedFiles';
let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];

displaySavedFiles();

moduleForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const moduleName = document.getElementById('moduleName').value;
    const thumbnailUrl = document.getElementById('thumbnailUrl').value;
    const note = document.getElementById('note').value;

    savedFiles.push({ name: moduleName, thumbnailUrl: thumbnailUrl, note: note });

    localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));

    displaySavedFiles();
});

function displaySavedFiles() {
    savedFilesContainer.innerHTML = '';

    savedFiles.forEach((file, index) => {
        const fileElement = document.createElement('div');
        fileElement.classList.add('savedFile');

        // Image
        const imgElement = document.createElement('img');
        imgElement.src = file.thumbnailUrl;
        imgElement.alt = `${file.name} thumbnail`;
        // Check if the image is available
        imgElement.onerror = function() {
            imgElement.src = 'images/download.png'; // Use a placeholder image
            imgElement.alt = 'Placeholder Image';
        };
        fileElement.appendChild(imgElement);

        // Title
        const titleElement = document.createElement('h3');
        titleElement.textContent = file.name;
        fileElement.appendChild(titleElement);

        // Note
        const noteElement = document.createElement('p');
        noteElement.textContent = `Note: ${file.note}`;
        fileElement.appendChild(noteElement);

        // Delete icon
        const deleteIcon = document.createElement('span');
        deleteIcon.classList.add('deleteIcon');
        deleteIcon.innerHTML = '&times;';
        deleteIcon.addEventListener('click', () => {
            savedFiles = savedFiles.filter((f, i) => i !== index);
            localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
            displaySavedFiles();
        });
        fileElement.appendChild(deleteIcon);

        savedFilesContainer.appendChild(fileElement);

        // Divider
        const divider = document.createElement('hr');
        savedFilesContainer.appendChild(divider);
    });
}
