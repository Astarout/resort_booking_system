
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", function () {
    const disabledDates = window.disabledDates || [];

    flatpickr("#datePicker", {
        dateFormat: "Y-m-d",
        minDate: new Date(Date.now() + (2 * 24 * 60 * 60 * 1000)),
        disable: disabledDates,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            let dateStr = fp.formatDate(dayElem.dateObj, 'Y-m-d');

            if (disabledDates.includes(dateStr)) {
                dayElem.classList.add('reserved-date');
            }
        },
        onClose: function(selectedDates, dateStr, instance) {
            instance.clear();
        }
    });
});
