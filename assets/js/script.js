$(document).ready(function () {

    loadTodos();

    loadUsersDropdown();

    function loadTodos() {
        $.ajax({
            url: "../api/fetch.php",
            method: "GET",
            dataType: "json",
            success: function (todos) {

                let html = "";

                todos.forEach(function (t) {

                    html += `
                        <li>
                            ${t.task} 
                            ${t.user_name ? " (Assigned to: " + t.user_name + ")" : ""}
                            <button onclick="deleteTodo(${t.id})">Delete</button>
                        </li>
                    `;
                });

                $("#todoList").html(html);
            }
        });
    }

    $("#addBtn").click(function () {

        let task = $("#task").val();

        let user_id = $("#assignUser").val();

        $.ajax({
            url: "../api/insert.php",
            method: "POST",
            data: {
                task: task,
                user_id: user_id
            },
            success: function () {
                $("#task").val("");
                loadTodos();
                Swal.fire("Success", "Task added", "success");
            }
        });

    });

    function loadUsersDropdown() {

        $.ajax({
            url: "../api/get_users.php",
            method: "GET",
            dataType: "json",
            success: function (users) {

                let options = "";

                users.forEach(function (u) {
                    options += `<option value="${u.id}">${u.name}</option>`;
                });

                $("#assignUser").html(options);
            }
        });
    }

    window.deleteTodo = function(id) {
    $.post("../api/delete.php", {id}, function(res){
        loadTodos();
        Swal.fire("Deleted", "", "success");
    });
};

window.editTodo = function(id, task) {
    let newTask = prompt("Edit Task:", task);

    if (newTask) {
        $.post("../api/edit.php", {id, task: newTask}, function(){
            loadTodos();
            Swal.fire("Updated", "", "success");
        });
    }
};

window.toggleTodo = function(id) {
    $.post("../api/toggle.php", {id}, function(){
        loadTodos();
    });
};

});