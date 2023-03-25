let commentsCheckbox = document.querySelectorAll('input[type="checkbox"]');

commentsCheckbox.forEach(element => {
    element.addEventListener('click', (event) => {
        
        console.log(JSON.stringify({commentid: event.target.dataset.commentid, hidden: String(event.target.checked)}));
        
        fetch("hiddenComments.php", {
            method: "post",
            body: JSON.stringify({commentid: event.target.dataset.commentid, hidden: String(event.target.checked)}),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });

    })
});
