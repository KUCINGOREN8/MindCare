document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('scheduleForm');
    if (!form) return;

    const checkboxes = document.querySelectorAll('.not-available-toggle');

    checkboxes.forEach(checkbox => {
        const index = checkbox.dataset.index;

        checkbox.addEventListener('change', function() {
            toggleDayAvailability(this, index);
        });

        toggleDayAvailability(checkbox, index, true);
    });

    function toggleDayAvailability(checkbox, index, isInitial = false) {
        const fields = document.getElementById(`schedule-fields-${index}`);
        if (!fields) return;

        const timeInputs = fields.querySelectorAll('.schedule-time');

        if (checkbox.checked) {
            timeInputs.forEach(input => {
                input.disabled = true;
                input.required = false;
                input.value = '';
                input.placeholder = '--:--';
            });
        } else {
            timeInputs.forEach((input, i) => {
                input.disabled = false;
                input.required = true;
                input.placeholder = '';
                if (isInitial || !input.value) {
                    input.value = i === 0 ? '09:00' : '17:00';
                }
            });
        }
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        document.querySelectorAll('.not-available-toggle:checked').forEach(checkbox => {
            const dayDiv = checkbox.closest('.schedule-day');
            if (dayDiv) dayDiv.remove();
        });

        this.submit();
    });
});
