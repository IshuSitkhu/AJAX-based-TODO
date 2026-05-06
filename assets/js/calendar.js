document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    // ================= COMMON FUNCTIONS =================

    const formatDate = (date) => {
        return date ? date.toISOString().split('T')[0] : '';
    };

    const validateEvent = ({ title, start, end }) => {
        if (!title || !start || !end) {
            Swal.showValidationMessage(" All fields are required");
            return false;
        }
        if (end < start) {
            Swal.showValidationMessage(" End date cannot be before start date");
            return false;
        }
        return true;
    };

    const eventFormHTML = (data = {}) => {
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

            </div>
        `;
    };

    const getFormData = () => {
        return {
            title: document.getElementById('title').value.trim(),
            start: document.getElementById('start').value,
            end: document.getElementById('end').value
        };
    };

    const postAndRefresh = (url, data, msg) => {
        $.post(url, data, function () {
            Swal.fire(' ' + msg, '', 'success');
            calendar.refetchEvents();
        });
    };

    // ================= CALENDAR =================

    const calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        displayEventTime: false,
        eventColor: "#0d6efd",

        events: '../api/events/get_events.php',

        // 🇳🇵 Nepali Date
        dayCellDidMount: function (info) {
            try {
                let nepDate = NepaliDate.fromAD(info.date);

                const nepaliDigits = ['०','१','२','३','४','५','६','७','८','९'];
                const bsMonths = [
                    "Baishakh","Jestha","Ashadh","Shrawan","Bhadra","Ashwin",
                    "Kartik","Mangsir","Poush","Magh","Falgun","Chaitra"
                ];

                const toNepaliNumber = (num) =>
                    num.toString().split('').map(d => nepaliDigits[d]).join('');

                let bs = document.createElement("div");
                bs.className = "bs-date";

                bs.innerHTML = `
                    <div style="font-size:11px;font-weight:600;color:#198754;">
                        ${toNepaliNumber(nepDate.getDate())}
                    </div>
                    <div style="font-size:9px;color:#555;">
                        ${bsMonths[nepDate.getMonth()]}
                    </div>
                `;

                info.el.querySelector('.fc-daygrid-day-top')?.appendChild(bs);

            } catch (e) {
                console.log(e);
            }
        },

        // ================= ADD EVENT =================
        dateClick: function (info) {

            Swal.fire({
                title: ' Add Event',
                html: eventFormHTML({
                    start: info.dateStr,
                    end: info.dateStr
                }),
                showCancelButton: true,
                confirmButtonText: ' Add',
                confirmButtonColor: '#198754',
                background: '#f8f9fa',

                preConfirm: () => {
                    let data = getFormData();
                    return validateEvent(data) && data;
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    postAndRefresh('../api/events/add_event.php', result.value, 'Added!');
                }
            });
        },

        // ================= EDIT / DELETE =================
        eventClick: function (info) {

            Swal.fire({
                title: ' Edit Event',
                html: eventFormHTML({
                    title: info.event.title,
                    start: formatDate(info.event.start),
                    end: formatDate(info.event.end)
                }),
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: ' Update',
                denyButtonText: ' Delete',
                confirmButtonColor: '#ffc107',
                denyButtonColor: '#dc3545',

                preConfirm: () => {
                    let data = getFormData();
                    return validateEvent(data) && data;
                }

            }).then((result) => {

                // UPDATE
                if (result.isConfirmed) {
                    postAndRefresh('../api/events/update_event.php', {
                        id: info.event.id,
                        ...result.value
                    }, 'Updated!');
                }

                // DELETE
                else if (result.isDenied) {

                    Swal.fire({
                        title: "Delete this event?",
                        text: "This cannot be undone!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        confirmButtonText: "Yes, delete"
                    }).then((confirmResult) => {

                        if (confirmResult.isConfirmed) {
                            postAndRefresh('../api/events/delete_event.php', {
                                id: info.event.id
                            }, 'Deleted!');
                        }
                    });
                }
            });
        }

    });

    calendar.render();
});