import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const regionElement = document.getElementById('region-filter');
    const stateElement = document.getElementById('state-filter');
    const typeElement = document.getElementById('type-filter');
    const cityElement = document.getElementById('city-filter');
    const statusesElement = document.getElementById('status-filter');

    let stateSelect;

    if (regionElement) {
        regionElement.classList.remove('form-select');
        const regionSelect = new TomSelect(regionElement, {
            create: false,
            placeholder: regionElement.dataset.placeholder || 'Selecione',
            allowEmptyOption: true,
            sortField: { field: 'text', direction: 'asc' },
        });

        regionSelect.on('change', async value => {
            if (!stateElement) return;

            stateSelect.clearOptions();
            stateSelect.clear(true);

            if (!value) return;

            const states = await fetch(`/api/regions/${encodeURIComponent(value)}/states`)
                .then(res => res.ok ? res.json() : [])
                .catch(() => []);

            states.forEach(state => {
                stateSelect.addOption({ value: state.acronym, text: state.name });
            });
            stateSelect.refreshOptions(false);

            regionElement.form.submit();
        });
    }

    if (stateElement) {
        stateElement.classList.remove('form-select');
        stateSelect = new TomSelect(stateElement, {
            create: false,
            placeholder: stateElement.dataset.placeholder || 'Selecione',
            allowEmptyOption: true,
        });

        stateSelect.on('change', () => {
            stateElement.form.submit();
        });
    }

    if (typeElement) {
        typeElement.classList.remove('form-select');
        new TomSelect(typeElement, {
            create: false,
            placeholder: typeElement.dataset.placeholder || 'Selecione',
            allowEmptyOption: true,
            sortField: { field: 'text', direction: 'asc' },
        }).on('change', () => {
            typeElement.form.submit();
        });
    }

    if (statusesElement) {
        statusesElement.classList.remove('form-select');
        new TomSelect(statusesElement, {
            create: false,
            placeholder: statusesElement.dataset.placeholder || 'Selecione',
            allowEmptyOption: true,
            sortField: { field: 'text', direction: 'asc' },
        }).on('change', () => {
            statusesElement.form.submit();
        });
    }

    if (cityElement) {
        cityElement.classList.remove('form-select');
        const citySelect = new TomSelect(cityElement, {
            create: false,
            allowEmptyOption: true,
            placeholder: cityElement.dataset.placeholder,
            persist: false,
            selectOnTab: true,
            maxOptions: null,
        });

        citySelect.on('change', () => {
            cityElement.form.submit();
        });
    }
});
