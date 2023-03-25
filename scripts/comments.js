let commentsCheckbox = document.querySelectorAll('input[type="checkbox"]');

commentsCheckbox.forEach(element => {
    element.addEventListener('click', (event) => {
        
        // if(event.target.checked){
            console.log(event.target.checked);
            fetch("hiddenComments.php", {
                method: "post",
                body: JSON.stringify({commentid: event.target.dataset.commentid, hidden: event.target.checked}),
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            })
            .then((response) => console.log(response.json()));
        // }else{
        //     console.log(event.target.checked);
        // }


    })
});
