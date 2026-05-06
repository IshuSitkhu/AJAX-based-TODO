document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

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

    const eventFormHTML = (data = {}, users = []) => {

    let options = users.map(u => {
        let selected = data.users?.includes(String(u.id)) ? 'selected' : '';
        return `<option value="${u.id}" ${selected}>${u.name}</option>`;
    }).join('');

    return `
        <div style="text-align:left">

            <label style="font-size:13px;">Event Title</label>
            <input id="title" class="swal2-input" 
                value="${data.title || ''}" placeholder="Enter event title">

            <div style="display:flex; gap:10px; margin-top:5px;">

                <div style="flex:1;">
                    <label style="font-size:12px;">Start</label>
                    <input id="start" type="date" 
                        class="form-control small-date" 
                        value="${data.start || ''}">
                </div>

                <div style="flex:1;">
                    <label style="font-size:12px;">End</label>
                    <input id="end" type="date" 
                        class="form-control small-date" 
                        value="${data.end || ''}">
                </div>

            </div>

            <label class="mt-2">Assign Users</label>
            <select id="users" class="form-select" multiple>
                ${options}
            </select>

        </div>
    `;
};

    const getFormData = () => {
    let selectedUsers = Array.from(document.getElementById('users').selectedOptions)
        .map(opt => opt.value);

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
                end: info.dateStr
            }, users),
            showCancelButton: true,

            preConfirm: () => getFormData()

        }).then((result) => {
            if (result.isConfirmed) {
                postAndRefresh('../api/events/add_event.php', result.value, 'Added!');
            }
        });

    }, 'json'); // ✅ IMPORTANT
},

eventClick: function (info) {

    // 1. Get all users
    $.get('../api/events/get_users.php', function (allUsers) {

        // 2. Get assigned users
        $.get('../api/events/get_event_users.php', {
            event_id: info.event.id
        }, function (assignedUsers) {

            Swal.fire({
                title: 'Edit Event',
                html: eventFormHTML({
                    title: info.event.title,
                    start: formatDate(info.event.start),
                    end: info.event.end
                        ? formatDate(new Date(info.event.end.getTime() - 86400000))
                        : formatDate(info.event.start),
                    users: assignedUsers
                }, allUsers),

                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Update',
                denyButtonText: 'Delete',

                preConfirm: () => {
                    let data = getFormData();
                    return validateEvent(data) && data;
                }

            }).then((result) => {

                if (result.isConfirmed) {
                    postAndRefresh('../api/events/update_event.php', {
                        id: info.event.id,
                        ...result.value
                    }, 'Updated!');
                }

                else if (result.isDenied) {
                    $.post('../api/events/delete_event.php', {
                        id: info.event.id
                    }, function () {
                        Swal.fire('Deleted!');
                        calendar.refetchEvents();
                    });
                }
            });

        }, 'json');

    }, 'json');
},

eventDrop: function (info) {

    let start = formatDate(info.event.start);

    let end = info.event.end
        ? formatDate(new Date(info.event.end.getTime() - 86400000)) 
        : start;

    $.post('../api/events/update_event.php', {
        id: info.event.id,
        title: info.event.title,
        start: start,
        end: end
    }).done(() => {
        Swal.fire({ toast: true, icon: 'success', title: 'Moved', timer: 1500 });
    }).fail(() => info.revert());
},

eventResize: function (info) {

    let start = formatDate(info.event.start);

    let end = info.event.end
        ? formatDate(new Date(info.event.end.getTime() - 86400000)) 
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
