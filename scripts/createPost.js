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


// editBikePost shares this javascript
// const removeImageCheckBox = document.querySelector('#removeimage');

// // Add event listener to listen for checkbox click
// removeImageCheckBox.addEventListener('click', (event) => {
//   if(event.target.checked == true){
//     onclick="confirm('Are you sure you wish to delete this image?')"
//   }
//   else{
//     console.log("false");
//   }
// })