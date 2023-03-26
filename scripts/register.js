// Script borrowed from: https://github.com/StungEye-RRC/AJAX-Username/blob/master/web/main.js

// Hides or shows the username available and the username taken
// messages using CSS block property.
function usernameMessage(usernameAvailable) {
    let usernameAvailableMsg = document.querySelector('.username-available');
    let usernameTakenMsg = document.querySelector('.username-taken');
  
    if (usernameAvailable) {
      usernameAvailableMsg.style.display = 'block';
      usernameTakenMsg.style.display = 'none';
      // registerBtn.disabled = false;
    } else {
      usernameAvailableMsg.style.display = 'none';
      usernameTakenMsg.style.display = 'block';
      // registerBtn.disabled = true;
    }
  }
  
  function checkUsername(event) {
    // Fetch the username provided by the user in the target input.
    let username = event.target.value;
  
    // Don't bother checking blank usernames.
    if (username === '') {
      return;
    }
  
    // AJAX GET request to test the username for availability.
    fetch('username.php?username=' + username)
      .then(function(rawResponse) { 
        return rawResponse.json(); // Promise for parsed JSON.
      })
      .then(function(response) {
        // If the API check was successful.
        if (response['success']) {
          // Show the relevant username message (available / taken).
          usernameMessage(response['usernameAvailable'])
  
          // If the username is take put the focus back on the input
          // and select all text.
          if (! response['usernameAvailable']) {
            event.target.select();
          }
        }
      });
  };
  
function validate(e){
  // Hides all error elements on the page
  hideErrors();

// Determine if the form has errors
  if (formHasErrors()) {
  // Prevents the form from submitting
    e.preventDefault();

    return false;
}

// When using onSubmit="validate()" in markup, returning true would allow
// the form to submit
  return true;
}

/*
 * Hides all of the error elements.
 */
function hideErrors() {
	// Get an array of error elements
	let error = document.getElementsByClassName("error");

	// Loop through each element in the error array
	for (let i = 0; i < error.length; i++) {
		// Hide the error element by setting it's display style to "none"
		error[i].style.display = "none";
	}
}

function formHasErrors(){
  let errorFlag = false;

    {
      let passLength = document.getElementById("password").value.length;
      if(passLength < 8 || passLength > 32){
        document.getElementById("password_error").style.display = "block";
        errorFlag = true;
      }
    }

    {
      let pass = document.getElementById("password").value;
      let confirmpass = document.getElementById("confirmpassword").value;
      console.log(pass);
      console.log(confirmpass);
      console.log(confirmpass.localeCompare(pass));
      if(confirmpass.localeCompare(pass) != 0){
        errorFlag = true;
        document.getElementById("confirmpassword_error").style.display = "block";
      }
    }


    return errorFlag;
}

  // Bind the checkUsername function to the onblur event of the username input.
  // When this input loses focus this function will be executed.
  let inputUsername = document.querySelector('#username');
  let registerBtn = document.querySelector('#submit');
  inputUsername.onblur = checkUsername;

  document.getElementById("registerform").addEventListener("submit", validate);