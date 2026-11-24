
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        let calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            events: window.calendarEvents || [],
            dayMaxEvents: true,

            moreLinkText: function(num) {
                return `+${num} more reservations`;
            },

            eventClick: function(info) {
                info.jsEvent.preventDefault();

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: {
                        id: info.event.id,
                        title: info.event.title,
                        start: info.event.start,
                        end: info.event.end,
                        extendedProps: info.event.extendedProps
                    }
                }));
            },
            eventDidMount: function(info) {
                info.el.style.cursor = 'pointer';
                info.el.style.textAlign = 'center';
            },
        });

        calendar.render();
    }
});
