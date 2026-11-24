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

                if (info.event.extendedProps.is_owner) {
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: {
                            id: info.event.id,
                            title: info.event.title,
                            start: info.event.start,
                            end: info.event.end,
                            extendedProps: info.event.extendedProps
                        }
                    }));
                }
            },

            eventDidMount: function(info) {
                info.el.style.textAlign = 'center';

                if (!info.event.extendedProps.is_owner) {

                    const titleEl = info.el.querySelector('.fc-event-title');
                    if (titleEl) titleEl.innerText = 'Reserved';

                    info.el.style.cursor = 'default';
                    info.el.style.backgroundColor = '#9CA3AF';
                    info.el.style.borderColor = '#6B7280';
                    info.el.style.color = '#ffffff';
                } else {
                    info.el.style.cursor = 'pointer';
                }
            },
        });

        calendar.render();
    }
});
