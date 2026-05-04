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
                input: 'text',
                inputLabel: 'Event Title',
                inputPlaceholder: 'Enter event name...',
                showCancelButton: true,
                confirmButtonText: 'Add',
                confirmButtonColor: '#28a745',

                inputValidator: (value) => {
                    if (!value) {
                        return 'Event title cannot be empty!';
                    }
                }

            }).then((result) => {

                if (result.isConfirmed) {

                    $.post('../api/events/add_event.php', {
                        title: result.value,
                        start: info.dateStr
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
                input: 'text',
                inputValue: info.event.title,
                showCancelButton: true,
                confirmButtonText: 'Update',
                showDenyButton: true,
                denyButtonText: 'Delete',
                confirmButtonColor: '#ffc107',
                denyButtonColor: '#dc3545',

                inputValidator: (value) => {
                    if (!value) {
                        return 'Event title cannot be empty!';
                    }
                }

            }).then((result) => {

                // UPDATE
                if (result.isConfirmed) {

                    $.post('../api/events/update_event.php', {
                        id: info.event.id,
                        title: result.value
                    }, function () {
                        Swal.fire('Updated!', '', 'success');
                        calendar.refetchEvents();
                    });

                }

                // DELETE
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