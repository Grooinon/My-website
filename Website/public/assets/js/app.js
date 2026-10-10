/**
 * TemanBelajar - Frontend Application JS
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Auto-dismiss flash alerts after 6 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 6000);
    });

    // 2. Initialize Bootstrap Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 3. Dynamic End Time Calculator on Booking Modal
    const startTimeInput = document.getElementById('booking_start_time');
    const durationInput = document.getElementById('booking_duration');
    const endTimeDisplay = document.getElementById('booking_end_time_display');

    function updateEndTime() {
        if (!startTimeInput || !durationInput || !endTimeDisplay) return;

        const timeVal = startTimeInput.value;
        const durationVal = parseInt(durationInput.value, 10);

        if (!timeVal || isNaN(durationVal)) {
            endTimeDisplay.textContent = '-';
            return;
        }

        const parts = timeVal.split(':');
        let hours = parseInt(parts[0], 10);
        let minutes = parseInt(parts[1], 10);

        minutes += durationVal;
        hours += Math.floor(minutes / 60);
        minutes = minutes % 60;
        hours = hours % 24;

        const formatted = String(hours).padStart(2, '0') + ':' + String(minutes).padStart(2, '0') + ' WIB';
        endTimeDisplay.textContent = formatted;
    }

    if (startTimeInput && durationInput) {
        startTimeInput.addEventListener('change', updateEndTime);
        durationInput.addEventListener('change', updateEndTime);
        updateEndTime();
    }

    // 4. Star Rating Interactive Label
    const starInputs = document.querySelectorAll('.star-input-group input[type="radio"]');
    const starLabel = document.getElementById('star_rating_text');
    const starLabels = {
        '1': '1 Bintang (Kurang Memuaskan)',
        '2': '2 Bintang (Cukup)',
        '3': '3 Bintang (Baik)',
        '4': '4 Bintang (Sangat Baik)',
        '5': '5 Bintang (Luar Biasa / Sangat Membantu!)'
    };

    if (starInputs.length > 0 && starLabel) {
        starInputs.forEach(function (input) {
            input.addEventListener('change', function () {
                starLabel.textContent = starLabels[this.value] || '';
            });
        });
    }

    // 5. Rate type toggle (Free vs Paid) in Tutor Profile Edit
    const isFreeCheckbox = document.getElementById('is_free_checkbox');
    const hourlyRateContainer = document.getElementById('hourly_rate_container');
    const hourlyRateInput = document.getElementById('hourly_rate_input');

    if (isFreeCheckbox && hourlyRateContainer) {
        function toggleHourlyRate() {
            if (isFreeCheckbox.checked) {
                hourlyRateContainer.style.display = 'none';
                if (hourlyRateInput) hourlyRateInput.value = '0';
            } else {
                hourlyRateContainer.style.display = 'block';
            }
        }
        isFreeCheckbox.addEventListener('change', toggleHourlyRate);
        toggleHourlyRate();
    }
});
