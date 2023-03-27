let commentsCheckbox = document.querySelectorAll('input[type="checkbox"]');
let commentsDeleteBtn = document.querySelectorAll('button[data-btn-id]');
console.log(commentsDeleteBtn);

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


commentsDeleteBtn.forEach(btn => {

    btn.addEventListener('click', (event) => {

        console.log(event.target.dataset.btnId);

        fetch("deleteComment.php", {
            method: "post",
            body: JSON.stringify({commentid: event.target.dataset.btnId, instruction: 'delete'}),
            headers: {
                "Content-type": "application/json; charset=UTF-8",
            },
        })
        .then(response => {
            return response.json();
        })
        .catch(error => {
            console.error('Error:', error);
        });
        location.reload();
    });
})