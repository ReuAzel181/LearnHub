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

    const bodyData = `name=${encodeURIComponent(moduleName)}&icon=${encodeURIComponent(thumbnailUrl)}&note=${encodeURIComponent(note)}`;
    console.log('Request body:', bodyData);

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: bodyData
        });

        const result = await response.json();
        console.log('Server response:', result);

        if (response.ok && result.success) {
            alert('Module added successfully');
            savedFiles.push({ id: result.id, name: moduleName, thumbnailUrl: thumbnailUrl, note: note });
            localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
            displaySavedFiles();
        } else {
            throw new Error('Failed to add module');
        }

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
        fileElement.dataset.id = file.id;
        fileElement.dataset.name = file.name;
        fileElement.dataset.icon = file.thumbnailUrl;
        fileElement.dataset.note = file.note;

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
        deleteButton.addEventListener('click', async (event) => {
            event.stopPropagation();

            const moduleId = file.id;

            try {
                const response = await fetch(`process.php?id=${encodeURIComponent(moduleId)}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to delete module');
                }

                const result = await response.json();
                console.log('Server response:', result);

                if (result.success) {
                    alert('Module deleted successfully');
                    savedFiles = savedFiles.filter(file => file.id !== moduleId);
                    localStorage.setItem(savedFilesKey, JSON.stringify(savedFiles));
                    displaySavedFiles();
                } else {
                    alert('Failed to delete module. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete module. Please try again.');
            }
        });

        fileElement.appendChild(deleteButton);

        fileElement.addEventListener('click', () => {
            const moduleName = fileElement.dataset.name;
            const thumbnailUrl = fileElement.dataset.icon;
            const note = fileElement.dataset.note;

            createModal(moduleName, thumbnailUrl, note, true);
        });

        savedFilesContainer.appendChild(fileElement);

        const divider = document.createElement('hr');
        savedFilesContainer.appendChild(divider);
    });
}
