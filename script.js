const modulesContainer = document.getElementById('modules');
const moduleForm = document.getElementById('moduleForm');

moduleForm.addEventListener('submit', (event) => {
  event.preventDefault();
  
  const moduleName = document.getElementById('moduleName').value;
  const thumbnailUrl = document.getElementById('thumbnailUrl').value;

  // Create a new module element
  const module = document.createElement('div');
  module.className = 'module';
  module.innerHTML = `
    <h2>${moduleName}</h2>
    <img src="${thumbnailUrl}" alt="${moduleName} thumbnail">
    <p>Created: ${new Date().toLocaleString()}</p>
  `;

  // Append the new module to the modules container
  modulesContainer.appendChild(module);

  // Clear the form inputs
  document.getElementById('moduleName').value = '';
  document.getElementById('thumbnailUrl').value = '';
});
