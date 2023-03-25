
let btns = document.querySelectorAll('input[type="radio"]');

btns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        const d = new Date();
        d.setTime(d.getTime() + 15*60*1000); 
        let expires = "expires=" + d.toUTCString(); 
        document.cookie = "sort=" + e.target.value + ";" + expires + "; path=/wd2/Project;";
        location.reload();
        })
});