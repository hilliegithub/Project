
function validate(e){
if(checkUsername()){
    e.preventDefault();
}
}

function checkUsername(){
    // alert('Hey');
    console.log("Test");
    return true;
}

function load(){
    
    let inputUsername = document.querySelector('#username');
    // inputUsername.onblur = checkUsername;
    
    document.querySelector('#submit').addEventListener("submit", validate);
}

document.addEventListener("DOMContentLoaded",load);