// on edit button click
// grab the id of the button and find the td for coresponding row
//Change each column to input field 
//Change edit button to dISABLED and cancel button to refresh the page
// Place an update button that submits a form




// Add event listener to all edit buttons
const editButtons = document.querySelectorAll('.edit-button');
editButtons.forEach(button => {
  button.addEventListener('click', () => {
    // Get the user ID from the data attribute on the button
    const userId = button.dataset.userId;
    
    // Get the row for this user
    const row = button.closest('tr');
    
    // Replace the username and email cells with input fields
    const usernameCell = row.querySelector('td:nth-child(1)');
    //usernameCell.innerHTML = `<input type="text" name="username" value="${usernameCell.textContent}" />`;
    let usernameinput = document.createElement('input');
    usernameinput.type = 'text';
    usernameinput.name = 'username';
    usernameinput.value = usernameCell.textContent.trim();
    usernameCell.replaceChild(usernameinput, usernameCell.firstChild);
    usernameinput.addEventListener('input',updateValue
    );


    const emailCell = row.querySelector('td:nth-child(2)');
    //emailCell.innerHTML = `<input type="email" name="email" value="${emailCell.textContent}" />`;
    let emailinput = document.createElement('input');
    emailinput.type = 'email';
    emailinput.name = 'email';
    emailinput.value = emailCell.textContent.trim();
    emailCell.replaceChild(emailinput,emailCell.firstChild);

    const adminCell = row.querySelector('td:nth-child(3)');
    // adminCell.innerHTML = `<select name="isAdmin" id="isAdmin">
    // <option value="${adminCell.textContent.trim()}">${adminCell.textContent.trim()}</option>
    // <option value="${adminCell.textContent.trim() === 'false'? 'true':'false'}">${adminCell.textContent.trim() === 'false'? 'true':'false'}</option>
    // </select>`;
    const selectElement = document.createElement('select');
    selectElement.name = "isAdmin";
    selectElement.id = "isAdmin";

    const option1 = document.createElement('option');
    option1.value = adminCell.textContent.trim();
    option1.text = adminCell.textContent.trim();
    selectElement.appendChild(option1);

    const option2 = document.createElement('option');
    option2.value = adminCell.textContent.trim() === 'false' ? 'true': 'false';
    option2.text = adminCell.textContent.trim() === 'false' ? 'true': 'false';
    selectElement.appendChild(option2);
    

    adminCell.appendChild(selectElement);


    // Replace the "Edit" button with a "Save" button
    button.textContent = 'Save';
    button.classList.remove('edit-button');
    button.classList.add('save-button');
    
    // Add event listener to the new "Save" button
    button.addEventListener('click', (event) => {
        event.preventDefault();
        postChanges(event);
      // Get the updated values from the input fields
      const updatedUsername = usernameCell.querySelector('input[name="username"]').value;
      const updatedEmail = emailCell.querySelector('input[name="email"]').value;
      const updatedAdminStatus = adminCell.querySelector('select[name="isAdmin"]').value;

      console.log(usernameCell.querySelector('input[name="username"]'));
    //   console.log({
    //     userId: userId,
    //     username: updatedUsername,
    //     email: updatedEmail,
    //     isAdmin: updatedAdminStatus
    //   });
      //Make AJAX request to update the user in the database
    //   fetch('manageUserProcess.php', {
    //     method: 'POST',
    //     body: JSON.stringify({
    //       userId: userId,
    //       username: updatedUsername,
    //       email: updatedEmail,
    //       isAdmin: updatedAdminStatus
    //     }),
    //     headers: {
    //       'Content-Type': 'application/json charset=UTF-8',
    //     }
    //   })
    //   .then(response => {
    //     const contentType = response.headers.get('Content-Type');
    //     console.log(contentType);
    //     console.log(JSON.parse(response.json));

    //     // if (contentType.includes('application/json')) {
    //     //     // Parse the response data as JSON
    //     //     console.log('here');
    //     //     const parsedData =  JSON.parse(response.json);
    //     //     console.log(parsedData);
    //     //     return response.json();
    //     //   } else {
    //     //     // Do something else with the response data
    //     //     return response.text();
    //     //   }
    //   })
    //   .then(data => {
        
    //     console.log(data);
    //   })
    //   .catch(error => {
    //     console.error(error);
    //   });

      // Replace the input fields with the new values
      //usernameCell.innerHTML = updatedUsername;
      //emailCell.innerHTML = updatedEmail;
      
      // Replace the "Save" button with the "Edit" button
      button.textContent = 'Edit';
      button.classList.remove('save-button');
      button.classList.add('edit-button');
    });
  });
});

// Add event listener to all remove buttons
const removeButtons = document.querySelectorAll('.remove-button');
removeButtons.forEach(button => {
  button.addEventListener('click', () => {
    // Get the user ID from the data attribute on the button
    const userId = button.dataset.userId;
    
    // Make AJAX request to remove the user from the database
    // ...
    
    // Remove the row from the table
    const row = button.closest('tr');
    row.parentNode.removeChild(row);
  });
});


function postChanges(event){
    
    let show = event.target.parentNode.parentNode;
    let q = show.querySelector('input[name=username');
    console.log(q);
}

function updateValue(e) {

    document.cookie = `value=` + e.target.value;
  }