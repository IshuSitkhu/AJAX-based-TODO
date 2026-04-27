$(document).ready(function () {

    loadMyTasks();

    // =========================
    // LOAD STAFF PROJECT TASKS
    // =========================
    function loadMyTasks() {

        $.get("../api/staff_project_tasks.php", function(data) {

            let html = "";

            if (data.length === 0) {
                html = `<li class="list-group-item text-muted">No tasks assigned</li>`;
            } else {

                data.forEach(t => {

                    html += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        <div>
                            <strong>${t.task}</strong><br>
                            <small class="text-muted">
                                Project: ${t.project_name}
                            </small>
                        </div>

                        <div>
                            <span class="badge bg-${t.status === 'completed' ? 'success' : 'warning'}">
                                ${t.status}
                            </span>

                            ${t.status !== 'completed' ? `
                                <button class="btn btn-sm btn-success ms-2"
                                    onclick="markCompleted(${t.id})">
                                    Done
                                </button>
                            ` : ''}
                        </div>

                    </li>`;
                });
            }

            $("#taskList").html(html);

        }, "json");
    }

    window.loadMyTasks = loadMyTasks;

    // =========================
    // MARK AS COMPLETED
    // =========================
    window.markCompleted = function (id) {

        $.post("../api/update_task_status.php", {
            id: id,
            status: "completed"
        }, function(res) {

            if (res.status === "success") {

                Swal.fire("Success", "Task completed", "success");
                loadMyTasks();

            } else {
                Swal.fire("Error", "Update failed", "error");
            }

        }, "json");
    };

});