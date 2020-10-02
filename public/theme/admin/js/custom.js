$(document).ready(function () {
    // Login Page
    $('#buttonLogin').click(function () {
        fetch('https://jsonplaceholder.typicode.com/todos/1').then(response => response.json()).then(json => {
            console.log(json.completed);
        });
    });
});