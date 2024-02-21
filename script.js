const savedFilesContainer = document.getElementById('savedFiles');
const savedFilesKey = 'savedFiles';
let savedFiles = JSON.parse(localStorage.getItem(savedFilesKey)) || [];

displaySavedFiles();



document.getElementById('moduleForm').addEventListener('submit', async (event) => {
    event.preventDefault();

    const moduleName = document.getElementById('name').value.trim();
    const thumbnailUrl = document.getElementById('icon').value.trim();
    const note = document.getElementById('note').value.trim();

    if (!moduleName || !thumbnailUrl || !note) {
        alert('Please fill in all fields');
        return;
    }

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `name=${encodeURIComponent(moduleName)}&icon=${encodeURIComponent(thumbnailUrl)}&note=${encodeURIComponent(note)}`
        });

        if (!response.ok) {
            throw new Error('Failed to add module');
        }

        const result = await response.json();
        console.log('Server response:', result);

        if (result.success) {
            alert('Module added successfully');
            savedFiles.push({ name: moduleName, thumbnailUrl: thumbnailUrl, note: note });
            localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
            displaySavedFiles();
        } else {
            alert('Failed to add module. Please try again.');
        }

        // Reset form fields after successful submission
        document.getElementById('name').value = '';
        document.getElementById('icon').value = '';
        document.getElementById('note').value = '';
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to add module. Please try again.');
    }
});
function displaySavedFiles() {
    savedFilesContainer.innerHTML = '';

    savedFiles.forEach((file, index) => {
        const fileElement = document.createElement('div');
        fileElement.classList.add('savedFile');
        fileElement.dataset.name = file.name; // Set data-name attribute
        fileElement.dataset.icon = file.thumbnailUrl; // Set data-icon attribute
        fileElement.dataset.note = file.note; // Set data-note attribute

        // Image
        const imgElement = document.createElement('img');
        imgElement.src = file.thumbnailUrl;
        imgElement.alt = `${file.name}`;
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
        noteElement.textContent = `${file.note}`;
        fileElement.appendChild(noteElement);

        // Delete button
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.classList.add('deleteButton');
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Stop the click event from propagating to the parent element
            savedFiles.splice(index, 1);
            localStorage.setItem('savedFiles', JSON.stringify(savedFiles));
            displaySavedFiles();
        });
        fileElement.appendChild(deleteButton);

        fileElement.addEventListener('click', () => {
        const moduleName = fileElement.dataset.name;
        const thumbnailUrl = fileElement.dataset.icon;
        const note = fileElement.dataset.note;

        createModal(moduleName, thumbnailUrl, note, true); // Pass true to show the delete button
        });


        savedFilesContainer.appendChild(fileElement);

        // Divider
        const divider = document.createElement('hr');
        savedFilesContainer.appendChild(divider);
    });
}
