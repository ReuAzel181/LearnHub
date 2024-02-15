const modulesContainer = document.getElementById('modules');
const moduleForm = document.getElementById('moduleForm');

moduleForm.addEventListener('submit', (event) => {
  event.preventDefault();
  
  const moduleName = document.getElementById('moduleName').value;
  const thumbnailUrl = document.getElementById('thumbnailUrl').value;

  // Send data to PHP file
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'addModule.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
      // Response from PHP file
      console.log(this.responseText);
    }
  };
  xhr.send(`moduleName=${moduleName}&thumbnailUrl=${thumbnailUrl}`);
});
