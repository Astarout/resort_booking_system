import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener('DOMContentLoaded', () => {
    const dateInput = document.getElementById('date_range');
    if (dateInput) {
        flatpickr(dateInput, {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr) {
                if (dateStr.includes(" to ")) {
                    dateInput.value = dateStr.replace(" to ", " - ");
                }
            }
        });
    }
});
