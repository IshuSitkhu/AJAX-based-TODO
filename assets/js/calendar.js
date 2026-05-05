document.addEventListener('DOMContentLoaded', function () {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',

        displayEventTime: false,

        eventColor: "#0d6efd",

        //  FETCH EVENTS
        events: '../api/events/get_events.php',

        // 🇳🇵 NEPALI DATE ADDITION
        dayCellDidMount: function (info) {

            try {
                let engDate = info.date;

                let nepDate = NepaliDate.fromAD(engDate);

                let bsDay = nepDate.getDate();
                let bsMonthIndex = nepDate.getMonth();

                const nepaliDigits = ['०','१','२','३','४','५','६','७','८','९'];

                function toNepaliNumber(num) {
                    return num.toString().split('').map(d => nepaliDigits[d]).join('');
                }

                const bsMonths = [
                    "Baishakh","Jestha","Ashadh","Shrawan","Bhadra","Ashwin",
                    "Kartik","Mangsir","Poush","Magh","Falgun","Chaitra"
                ];

                let bsMonthName = bsMonths[bsMonthIndex];

                let bs = document.createElement("div");
                bs.className = "bs-date";  

                bs.innerHTML = `
                    <div style="font-size:11px; font-weight:600; color:#198754;">
                        ${toNepaliNumber(bsDay)}
                    </div>
                    <div style="font-size:9px; color:#555;">
                        ${bsMonthName}
                    </div>
                `;

                // append below AD date
                let topEl = info.el.querySelector('.fc-daygrid-day-top');
                if (topEl) {
                    topEl.appendChild(bs);
                }

            } catch (e) {
                console.log(e);
            }
        },

        dateClick: function (info) {

    Swal.fire({
        title: 'Add Event',

        html: `
            <input id="eventTitle" class="swal2-input" placeholder="Event Title">
            <input id="eventStart" type="date" class="swal2-input" value="${info.dateStr}">
            <input id="eventEnd" type="date" class="swal2-input" value="${info.dateStr}">
        `,

        showCancelButton: true,
        confirmButtonText: 'Add',
        confirmButtonColor: '#28a745',

        preConfirm: () => {
            return {
                title: document.getElementById('eventTitle').value,
                start: document.getElementById('eventStart').value,
                end: document.getElementById('eventEnd').value
            }
        }

    }).then((result) => {

        if (result.isConfirmed) {

            let data = result.value;

            if (!data.title || !data.start || !data.end) {
                Swal.fire('All fields are required!');
                return;
            }

            $.post('../api/events/add_event.php', {
                title: data.title,
                start: data.start,
                end: data.end
            }, function () {
                Swal.fire('Added!', '', 'success');
                calendar.refetchEvents();
            });

        }

    });
},

        eventClick: function (info) {

    Swal.fire({
        title: 'Edit Event',

        html: `
            <input id="editTitle" class="swal2-input" placeholder="Title" value="${info.event.title}">
            <input id="editStart" type="date" class="swal2-input"
                value="${info.event.start.toISOString().split('T')[0]}">

            <input id="editEnd" type="date" class="swal2-input"
                value="${info.event.end ? info.event.end.toISOString().split('T')[0] : ''}">
        `,

        showCancelButton: true,
        confirmButtonText: 'Update',
        showDenyButton: true,
        denyButtonText: 'Delete',
        confirmButtonColor: '#ffc107',
        denyButtonColor: '#dc3545',

        preConfirm: () => {
            return {
                title: document.getElementById('editTitle').value,
                start: document.getElementById('editStart').value,
                end: document.getElementById('editEnd').value
            }
        }

    }).then((result) => {

        // ================= UPDATE =================
        if (result.isConfirmed) {

            let data = result.value;

            if (!data.title || !data.start || !data.end) {
                Swal.fire("All fields required!");
                return;
            }

            $.post('../api/events/update_event.php', {
                id: info.event.id,
                title: data.title,
                start: data.start,
                end: data.end
            }, function () {
                Swal.fire('Updated!', '', 'success');
                calendar.refetchEvents();
            });

        }

        // ================= DELETE =================
        else if (result.isDenied) {

            Swal.fire({
                title: "Are you sure?",
                text: "This event will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((confirmResult) => {

                if (confirmResult.isConfirmed) {

                    $.post('../api/events/delete_event.php', {
                        id: info.event.id
                    }, function () {
                        Swal.fire('Deleted!', '', 'success');
                        calendar.refetchEvents();
                    });

                }

            });
        }
    });
}

    });

    calendar.render();
});