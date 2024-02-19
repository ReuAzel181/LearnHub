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
function showContent(file) {
    const contentWindow = window.open('', '_blank', 'width=600,height=400,left=' + ((window.innerWidth - 600) / 2) + ',top=' + ((window.innerHeight - 400) / 2));
    contentWindow.document.body.innerHTML = `
      <h2>${file.name}</h2>
      <p>${file.note}</p>
    `;
  }
  
  function displaySavedFiles() {
      savedFilesContainer.innerHTML = '';
  
      savedFiles.forEach((file, index) => {
          const fileElement = document.createElement('div');
          fileElement.classList.add('savedFile');
  
          // Image
          const imgElement = document.createElement('img');
          imgElement.src = file.thumbnailUrl;
          imgElement.alt = `${file.name} thumbnail`;
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
  
          // Add click event listener to open content
          fileElement.addEventListener('click', () => {
              showContent(file);
          });
  
          // Delete button
          const deleteButton = document.createElement('button');
          deleteButton.textContent = 'Delete';
          deleteButton.classList.add('deleteButton');
          deleteButton.addEventListener('click', () => {
              savedFiles.splice(index, 1);
              localStorage.setItem('savedFiles', JSON.stringify(savedFiles));
              displaySavedFiles();
          });
          fileElement.appendChild(deleteButton);
  
          savedFilesContainer.appendChild(fileElement);
  
          // Divider
          const divider = document.createElement('hr');
          savedFilesContainer.appendChild(divider);
      });
  
      // Update the CSS to align the "Delete" button to the right
      const deleteButtons = document.querySelectorAll('.deleteButton');
      deleteButtons.forEach(button => {
          button.style.marginLeft = 'auto'; // Pushes the button to the right
      });
  }
  