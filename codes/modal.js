function createModal(moduleName, thumbnailUrl, note, index, onDelete) {
  // Create modal overlay
  const modalOverlay = document.createElement('div');
  modalOverlay.classList.add('modal-overlay');

  // Modal content
  const modalContent = document.createElement('div');
  modalContent.classList.add('modal-content');
  modalContent.innerHTML = `
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>${moduleName}</h2>
    </div>
    <div class="modal-body">
      <img src="${thumbnailUrl}" alt="${moduleName}">
      <p>${note}</p>
    </div>
  `;

  const deleteButton = document.createElement('button');
  

  // Delete button to dapat sa modals

  // deleteButton.textContent = 'Delete';
  // deleteButton.classList.add('deleteButton');
  // deleteButton.addEventListener('click', () => {
  //   onDelete(index); // Call the onDelete callback with the index
  //   modalOverlay.remove(); // Remove the modal
  // });

  // modalContent.querySelector('.modal-header').appendChild(deleteButton);

  modalOverlay.appendChild(modalContent);
  document.body.appendChild(modalOverlay);

  // Close modal overlay when close button is clicked
  const closeButton = modalContent.querySelector('.close');
  closeButton.addEventListener('click', () => {
    modalOverlay.remove();
  });

  // Close modal overlay when clicking outside the modal
  modalOverlay.addEventListener('click', (event) => {
    if (event.target === modalOverlay) {
      modalOverlay.remove();
    }
  });

  // Close modal overlay when pressing the Escape key
  window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      modalOverlay.remove();
    }
  });
}
