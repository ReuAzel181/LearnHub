const savedFilesContainer = document.getElementById('savedFiles');
const savedFilesKey = 'savedFiles';
let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];

displaySavedFiles();

document.getElementById('moduleForm').addEventListener('submit', (event) => {
    event.preventDefault();

    const moduleName = document.getElementById('name').value.trim();
    const thumbnailUrl = document.getElementById('icon').value.trim();
    const note = document.getElementById('note').value.trim();

    if (!moduleName || !thumbnailUrl || !note) {
        alert('Please fill in all fields');
        return;
    }

    const id = Date.now().toString();

    savedFiles.push({ id, name: moduleName, thumbnailUrl, note });
    localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
    displaySavedFiles();

    document.getElementById('name').value = '';
    document.getElementById('icon').value = '';
    document.getElementById('note').value = '';

    alert('Module added successfully');
});

function displaySavedFiles() {
    savedFilesContainer.innerHTML = '';

    savedFiles.forEach((file, index) => {
        const fileElement = document.createElement('div');
        fileElement.classList.add('savedFile');
        fileElement.dataset.id = file.id;

        const imgElement = document.createElement('img');
        imgElement.src = file.thumbnailUrl;
        imgElement.alt = `${file.name}`;
        imgElement.onerror = function() {
            imgElement.src = '../images/download.png';
            imgElement.alt = 'Placeholder Image';
        };
        fileElement.appendChild(imgElement);

        const titleElement = document.createElement('h3');
        titleElement.textContent = file.name;
        fileElement.appendChild(titleElement);

        const noteElement = document.createElement('p');
        noteElement.textContent = `${file.note}`;
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

        fileElement.addEventListener('click', () => {
            createModal(file.name, file.thumbnailUrl, file.note, true);
        });

        savedFilesContainer.appendChild(fileElement);

        const divider = document.createElement('hr');
        savedFilesContainer.appendChild(divider);
    });
}