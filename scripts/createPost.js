// Get the file input element
const fileInput = document.getElementById('image');

// Add event listener to update the file name on selection
fileInput.addEventListener('change', (event) => {
  const fileName = event.target.files[0].name;
  const label = document.querySelector('.custom-file-label');

  if(fileName.length > 20){
    label.innerHTML = fileName.substring(0,20) + `...`;
  }
  else{
      label.innerHTML = fileName;
  }
});
