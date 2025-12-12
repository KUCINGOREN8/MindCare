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
        const timeInputs = fields.querySelectorAll('.schedule-time');
        const dayName = fields.closest('.schedule-day').querySelector('h3').textContent;

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

        let isValid = true;
        const errorMessages = [];
        const schedules = [];

        document.querySelectorAll('.schedule-day').forEach((day, index) => {
            const checkbox = day.querySelector('.not-available-toggle');
            const dayName = day.querySelector('h3').textContent;
            const notAvailable = checkbox.checked;

            if (!notAvailable) {
                const startTime = day.querySelector('input[name="schedules['+index+'][start_time]"]').value;
                const endTime = day.querySelector('input[name="schedules['+index+'][end_time]"]').value;

                if (!startTime || !endTime) {
                    errorMessages.push(`Please set time for ${dayName} or mark as "Not available"`);
                    isValid = false;
                } else if (startTime >= endTime) {
                    errorMessages.push(`For ${dayName}, end time must be after start time`);
                    isValid = false;
                } else {
                    schedules.push({
                        day_of_week: day.querySelector('input[name="schedules['+index+'][day_of_week]"]').value,
                        start_time: startTime,
                        end_time: endTime
                    });
                }
            }
        });

        const availableDays = document.querySelectorAll('.not-available-toggle:not(:checked)').length;
        if (availableDays === 0) {
            errorMessages.push('Please set availability for at least one day');
            isValid = false;
        }

        if (!isValid) {
            alert('Please fix the following issues:\n\n' + errorMessages.join('\n'));
            return;
        }

        this.submit();
    });
});
