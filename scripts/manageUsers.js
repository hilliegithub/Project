


let checkbox = document.querySelector("#changePassword"); 

checkbox.addEventListener('click', () => {
  let passwordinputs = document.querySelectorAll(".passwordlist");

  passwordinputs.forEach(element => {
    element.classList.toggle('show');
  });
})