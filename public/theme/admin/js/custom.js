$(document).ready(function () {
    // Login Page
    // Get
    $('#buttonLogin').click(function () {
        fetch('https://jsonplaceholder.typicode.com/todos/1').then(response => response.json()).then(json => {
            console.log(json.completed);
        });
    });
    // Post
    $('#buttonLogin').click(function () {
        var data = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({
                'client_id': '(API KEY)',
                'client_secret': '(API SECRET)',
                'grant_type': 'client_credentials'
            })
        };
        fetch('https://jsonplaceholder.typicode.com/todos/1', data).then(response => response.json()).then(json => {
            console.log(json.completed);
        });
    });
});