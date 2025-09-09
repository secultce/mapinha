import {
    FRIDAY,
    MONDAY,
    SATURDAY,
    SELECT_DAY,
    SUNDAY,
    THURSDAY,
    trans,
    TUESDAY,
    WEDNESDAY
} from "../../translator.js";

document.addEventListener('DOMContentLoaded', function() {

    const daysOfWeek = [
        { value: 'sunday',    label: trans(SUNDAY)    },
        { value: 'monday',    label: trans(MONDAY)    },
        { value: 'tuesday',   label: trans(TUESDAY)   },
        { value: 'wednesday', label: trans(WEDNESDAY) },
        { value: 'thursday',  label: trans(THURSDAY)  },
        { value: 'friday',    label: trans(FRIDAY)    },
        { value: 'saturday',  label: trans(SATURDAY)  }
    ];

    function getUsedDays() {
        const used = [];
        document.querySelectorAll('select.week_days').forEach(select => {
            if (select.value) {
                used.push(select.value);
            }
        });
        return used;
    }

    function populateDropdown(selectElement, usedDays = [], currentValue = '') {
        selectElement.innerHTML = '';

        // Option default
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.disabled = true;
        if (!currentValue) {
            defaultOption.selected = true;
        }
        defaultOption.textContent = trans(SELECT_DAY);
        selectElement.appendChild(defaultOption);

        daysOfWeek.forEach(day => {
            if (usedDays.includes(day.value) && day.value !== currentValue) {
                return;
            }
            const option = document.createElement('option');
            option.value = day.value;
            option.textContent = day.label;
            if (day.value === currentValue) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    function updateAllSelects() {
        const usedDays = getUsedDays();
        const allSelects = document.querySelectorAll('select.week_days');

        allSelects.forEach(select => {
            const currentValue = select.value;
            populateDropdown(select, usedDays, currentValue);
        });
    }

    function addRemoveButton(row) {
        const existingRemove = row.querySelector('.remove-opening-hours');
        if (existingRemove) {
            existingRemove.remove();
        }

        const removeButton = document.createElement('a');
        removeButton.href = "#";
        removeButton.classList.add('remove-opening-hours', 'text-danger', 'ms-2');
        removeButton.innerHTML = '<i class="iconify fs-4" data-icon="mdi:trash-can-outline"></i>';

        removeButton.addEventListener('click', function(event) {
            event.preventDefault();
            row.remove();

            updateAllSelects();
        });

        let removeCol = row.querySelector('.remove-col');
        if (!removeCol) {
            removeCol = document.createElement('div');
            removeCol.classList.add('col-md-2', 'd-flex', 'remove-col');
            row.appendChild(removeCol);
        }
        removeCol.innerHTML = '';
        removeCol.appendChild(removeButton);
    }

    document.querySelectorAll('select.week_days').forEach(select => {

        populateDropdown(select);

        select.addEventListener('change', function() {
            updateAllSelects();
        });
    });

    const addButton = document.getElementById('add-opening-hours');
    const container = document.getElementById('opening-hours-container');
    const templateRow = document.querySelector('.opening-hours-row');

    if (addButton && container && templateRow) {
        addButton.addEventListener('click', function(event) {
            event.preventDefault();

            const newRow = templateRow.cloneNode(true);
            newRow.classList.add('dynamic-row');

            const newSelect = newRow.querySelector('select.week_days');
            if (newSelect) {
                newSelect.value = '';
            }
            const opensInput = newRow.querySelector('.opens_at');
            if (opensInput) {
                opensInput.value = '';
            }
            const closesInput = newRow.querySelector('.closes_at');
            if (closesInput) {
                closesInput.value = '';
            }

            addRemoveButton(newRow);

            const addButtonRow = addButton.closest('.row.mt-4');
            container.insertBefore(newRow, addButtonRow);

            populateDropdown(newSelect);

            newSelect.addEventListener('change', function() {
                updateAllSelects();
            });

            updateAllSelects();
        });
    }

    document.querySelectorAll('.opening-hours-row').forEach(row => {
        addRemoveButton(row);
    });

    container.addEventListener('click', function(event) {
        if (event.target.closest('.remove-opening-hours')) {
            event.preventDefault();
            const rowToRemove = event.target.closest('.opening-hours-row');
            if (rowToRemove) {
                rowToRemove.remove();
                updateAllSelects();
            }
        }
    });

    updateAllSelects();
});
