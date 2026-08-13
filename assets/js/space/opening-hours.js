import {FRIDAY, MONDAY, SATURDAY, SUNDAY, THURSDAY, trans, TUESDAY, WEDNESDAY} from "../../translator.js";

class OpeningHoursManager {
    constructor() {
        this.daysOptions = [
            { value: 'sunday', label: trans(SUNDAY) },
            { value: 'monday', label: trans(MONDAY) },
            { value: 'tuesday', label: trans(TUESDAY) },
            { value: 'wednesday', label: trans(WEDNESDAY) },
            { value: 'thursday', label: trans(THURSDAY) },
            { value: 'friday', label: trans(FRIDAY) },
            { value: 'saturday', label: trans(SATURDAY) }
        ];

        this.refs = {
            container: document.getElementById('opening-hours-container'),
            list: document.getElementById('opening-hours-list'),
            hiddenInput: document.getElementById('opening-hours-json'),
            addBtn: document.getElementById('add-opening-hours-btn'),
            template: document.getElementById('opening-hours-row-template')
        };

        if (!this.refs.container) return;

        this.init();
    }

    init() {
        this._bindEvents();
        this._loadInitialData();
    }

    _bindEvents() {
        this.refs.addBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this._addRow();
        });

        this.refs.list.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.btn-remove-row');
            if (removeBtn) {
                e.preventDefault();
                removeBtn.closest('.opening-hours-row').remove();
                this._syncData();
            }
        });

        this.refs.list.addEventListener('input', (e) => {
            if (e.target.matches('select, input')) {
                this._syncData();
            }
        });
    }

    _loadInitialData() {
        try {
            const rawData = this.refs.container.dataset.initialData;
            const data = rawData ? JSON.parse(rawData) : {};

            if (data.openingHours && typeof data.openingHours === 'object') {
                Object.entries(data.openingHours).forEach(([day, slots]) => {
                    slots.forEach(slot => {
                        this._addRow({ day, open: slot.open, close: slot.close });
                    });
                });
            } else {
                this._addRow();
            }
        } catch (error) {
            console.error('Erro ao carregar dados iniciais:', error);
            this._addRow();
        }
    }

    _addRow(data = null) {
        const clone = this.refs.template.content.cloneNode(true);
        const row = clone.querySelector('.opening-hours-row');

        const select = row.querySelector('.field-day');
        this._populateDaySelect(select, data?.day);

        if (data) {
            row.querySelector('.field-open').value = data.open || '';
            row.querySelector('.field-close').value = data.close || '';
        }

        this.refs.list.appendChild(row);
        this._syncData();
    }

    _populateDaySelect(selectElement, selectedValue = null) {
        this.daysOptions.forEach(day => {
            const option = document.createElement('option');
            option.value = day.value;
            option.textContent = day.label;
            if (day.value === selectedValue) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    _syncData() {
        const rows = this.refs.list.querySelectorAll('.opening-hours-row');
        const openingHours = {};

        rows.forEach(row => {
            const day = row.querySelector('.field-day').value;
            const open = row.querySelector('.field-open').value;
            const close = row.querySelector('.field-close').value;

            if (day && open && close) {
                if (!openingHours[day]) {
                    openingHours[day] = [];
                }
                openingHours[day].push({ open, close });
            }
        });

        this.refs.hiddenInput.value = JSON.stringify(openingHours);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new OpeningHoursManager();
});