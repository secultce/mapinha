function initializePeriodFilterValidation() {
    const startDateInput = document.getElementById('period_start');
    const endDateInput = document.getElementById('period_end');

    if (!startDateInput || !endDateInput) {
        return;
    }

    const updateRequiredStatus = () => {
        const startValue = startDateInput.value;
        const endValue = endDateInput.value;

        endDateInput.required = !!startValue;

        startDateInput.required = !!endValue;
    };

    startDateInput.addEventListener('input', updateRequiredStatus);
    endDateInput.addEventListener('input', updateRequiredStatus);

    updateRequiredStatus();
}

document.addEventListener('DOMContentLoaded', initializePeriodFilterValidation);