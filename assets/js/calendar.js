document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    let currentEventId = null;

    const formatDate = (date) => {
        if (!date) return '';

        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    };

    const validateEvent = ({ title, start, end }) => {

        if (!title || !start || !end) {
            Swal.showValidationMessage("All fields are required");
            return false;
        }

        if (end < start) {
            Swal.showValidationMessage("End date cannot be before start date");
            return false;
        }

        return true;
    };

    const eventFormHTML = (
        data = {},
        users = [],
        assignedUsers = []
    ) => {

        const assignedIds = assignedUsers.map(String);

        // ONLY show users not already assigned
        const availableUsers = users.filter(u =>
            !assignedIds.includes(String(u.id))
        );

        let options = availableUsers.map(u => {
            return `
                <option value="${u.id}">
                    ${u.name}
                </option>
            `;
        }).join('');

        // Assigned users section
        let assignedHTML = '';

        if (assignedUsers.length > 0) {

            assignedHTML = assignedUsers.map(uid => {

                const user = users.find(
                    u => String(u.id) === String(uid)
                );

                return `
                    <div style="
                        display:flex;
                        justify-content:space-between;
                        align-items:center;
                        padding:6px 10px;
                        border:1px solid #ddd;
                        border-radius:6px;
                        margin-bottom:5px;
                    ">
                        <span>
                            ${user ? user.name : 'Unknown User'}
                        </span>

                        <button 
                            type="button"
                            class="btn btn-sm btn-danger"
                            onclick="removeUser(${uid})"
                        >
                            Remove
                        </button>
                    </div>
                `;
            }).join('');

        } else {

            assignedHTML = `
                <small class="text-muted">
                    No users assigned
                </small>
            `;
        }

        return `
            <div style="text-align:left;">

                <label style="font-size:13px;">
                    Event Title
                </label>

                <input 
                    id="title"
                    class="swal2-input"
                    value="${data.title || ''}"
                    placeholder="Enter event title"
                >

                <div style="
                    display:flex;
                    gap:10px;
                    margin-top:5px;
                ">

                    <div style="flex:1;">
                        <label style="font-size:12px;">
                            Start
                        </label>

                        <input
                            id="start"
                            type="date"
                            class="form-control"
                            value="${data.start || ''}"
                        >
                    </div>

                    <div style="flex:1;">
                        <label style="font-size:12px;">
                            End
                        </label>

                        <input
                            id="end"
                            type="date"
                            class="form-control"
                            value="${data.end || ''}"
                        >
                    </div>

                </div>

                <hr>

                <label>
                    Assigned Users
                </label>

                <div id="assignedBox">
                    ${assignedHTML}
                </div>

                <hr>

                <label>
                    Add More Users
                </label>

                <select
                    id="users"
                    class="form-select"
                    multiple
                    size="5"
                >
                    ${options}
                </select>

            </div>
        `;
    };

    const getFormData = () => {

        let selectedUsers = Array.from(
            document.getElementById('users').selectedOptions
        ).map(opt => opt.value);

        return {
            title: document.getElementById('title').value.trim(),
            start: document.getElementById('start').value,
            end: document.getElementById('end').value,
            users: selectedUsers
        };
    };

    const postAndRefresh = (url, data, msg) => {

        $.post(url, data, function () {

            Swal.fire(msg, '', 'success');

            calendar.refetchEvents();
        });
    };

    window.removeUser = function (userId) {

        $.post('../api/events/remove_user.php', {

            event_id: currentEventId,
            user_id: userId

        }, function () {

            Swal.fire({
                icon: 'success',
                title: 'User Removed',
                timer: 1200,
                showConfirmButton: false
            });

            calendar.refetchEvents();

            Swal.close();
        });
    };

    const calendar = new FullCalendar.Calendar(calendarEl, {

        timeZone: 'local',

        initialView: 'dayGridMonth',

        displayEventTime: false,

        eventColor: "#0d6efd",

        editable: true,

        eventStartEditable: true,

        eventDurationEditable: true,

        events: '../api/events/get_events.php',

        dateClick: function (info) {

            $.get('../api/events/get_users.php', function (users) {

                Swal.fire({

                    title: 'Add Event',

                    html: eventFormHTML({

                        start: info.dateStr,
                        end: info.dateStr,
                        users: []

                    }, users, []),

                    showCancelButton: true,

                    preConfirm: () => {

                        let data = getFormData();

                        return validateEvent(data) && data;
                    }

                }).then((result) => {

                    if (result.isConfirmed) {

                        postAndRefresh(
                            '../api/events/add_event.php',
                            result.value,
                            'Added!'
                        );
                    }
                });

            }, 'json');
        },

        eventClick: function (info) {

            currentEventId = info.event.id;

            // GET ALL USERS
            $.get('../api/events/get_users.php', function (allUsers) {

                // GET ASSIGNED USERS
                $.get('../api/events/get_event_users.php', {

                    event_id: info.event.id

                }, function (assignedUsers) {

                    Swal.fire({

                        title: 'Edit Event',

                        html: eventFormHTML({

                            title: info.event.title,

                            start: formatDate(info.event.start),

                            end: info.event.end
                                ? formatDate(
                                    new Date(
                                        info.event.end.getTime() - 86400000
                                    )
                                )
                                : formatDate(info.event.start),

                            users: assignedUsers

                        }, allUsers, assignedUsers),

                        showCancelButton: true,

                        showDenyButton: true,

                        confirmButtonText: 'Update',

                        denyButtonText: 'Delete',

                        preConfirm: () => {

                            let data = getFormData();

                            return validateEvent(data) && data;
                        }

                    }).then((result) => {

                        // UPDATE
                        if (result.isConfirmed) {

                            postAndRefresh(
                                '../api/events/update_event.php',
                                {
                                    id: info.event.id,
                                    ...result.value
                                },
                                'Updated!'
                            );
                        }

                        // DELETE
                        else if (result.isDenied) {

                            $.post(
                                '../api/events/delete_event.php',
                                {
                                    id: info.event.id
                                },
                                function () {

                                    Swal.fire('Deleted!');

                                    calendar.refetchEvents();
                                }
                            );
                        }
                    });

                }, 'json');

            }, 'json');
        },

        eventDrop: function (info) {

            let start = formatDate(info.event.start);

            let end = info.event.end
                ? formatDate(
                    new Date(
                        info.event.end.getTime() - 86400000
                    )
                )
                : start;

            $.post('../api/events/update_event.php', {

                id: info.event.id,

                title: info.event.title,

                start: start,

                end: end

            }).done(() => {

                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: 'Moved',
                    timer: 1500,
                    showConfirmButton: false
                });

            }).fail(() => info.revert());
        },

        eventResize: function (info) {

            let start = formatDate(info.event.start);

            let end = info.event.end
                ? formatDate(
                    new Date(
                        info.event.end.getTime() - 86400000
                    )
                )
                : start;

            $.post('../api/events/update_event.php', {

                id: info.event.id,

                title: info.event.title,

                start: start,

                end: end
            });
        }

    });

    calendar.render();
});