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
                                Project: ${t.project_name} <br>
                                Assigned by: ${t.assigned_by_name ?? 'Admin'} <br>
                                Date: ${t.created_at ?? 'N/A'}
                            </small>
                        </div>

                        <div>
                            <span class="badge bg-${t.status === 'completed' ? 'success' : 'warning'}">
                                ${t.status}
                            </span>

                            <select onchange="updateTaskStatus(${t.id}, this.value)" 
                                    class="form-select form-select-sm ms-2">

                                <option value="pending" ${t.status === 'pending' ? 'selected' : ''}>
                                    Pending
                                </option>

                                <option value="completed" ${t.status === 'completed' ? 'selected' : ''}>
                                    Completed
                                </option>

                            </select>
                        </div>

                    </li>`;
                });
            }

            $("#taskList").html(html);

        }, "json");
    }

    window.loadMyTasks = loadMyTasks;

   window.updateTaskStatus = function (id, status) {

    $.post("../api/update_task_status.php", {
        id: id,
        status: status
    }, function (res) {

        if (res.status === "success") {

            Swal.fire("Updated", "Status changed", "success");

            loadMyTasks();

        } else {
            Swal.fire("Error", res.message || "Update failed", "error");
        }

    }, "json");
};

});