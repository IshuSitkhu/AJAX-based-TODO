$(document).ready(function () {

    loadMyTasks();

    let calendar; // important

    // =========================
    // LOAD TASKS
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

    // =========================
    // UPDATE STATUS
    // =========================
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

    // =========================
    // NAVIGATION (ADMIN STYLE)
    // =========================
    window.openTasks = function () {
        $(".section").hide();
        $("#taskSection").show();
    };

    window.openCalendar = function () {

        $(".section").hide();
        $("#calendarSection").show();

        // INIT ONLY ON FIRST OPEN
        if (!calendar) {
            initCalendar();
        } else {
            calendar.updateSize(); // FIX BLANK ISSUE
        }
    };

    // =========================
    // CALENDAR INIT
    // =========================
    function initCalendar() {

        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {

            initialView: 'dayGridMonth',
            displayEventTime: false,
            eventColor: "#0d6efd",

            events: "../api/events/get_events.php",

            editable: false,

            eventClick: function (info) {
                Swal.fire({
                    title: info.event.title,
                    html: `
                        <b>Start:</b> ${info.event.start.toISOString().split('T')[0]} <br>
                        <b>End:</b> ${info.event.end ? info.event.end.toISOString().split('T')[0] : 'N/A'}
                    `,
                    icon: "info"
                });
            }

        });

        calendar.render();
    }

    // =========================
    // LOGOUT
    // =========================
    window.logout = function () {
        $.get("../api/logout.php", function () {
            window.location.href = "login.php";
        });
    };

    // default view
    openTasks();

});