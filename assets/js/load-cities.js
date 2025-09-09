import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const stateElement  = document.getElementById('state');
    const cityElement   = document.getElementById('city');

    stateElement.classList.remove('form-select');
    cityElement .classList.remove('form-select');

    const stateSelect = new TomSelect(stateElement, {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: stateElement.dataset.placeholder || 'Selecione',
        allowEmptyOption: false,
    });

    const citySelect = new TomSelect(cityElement, {
        create: false,
        sortField: { field: 'text', direction: 'asc' },
        placeholder: cityElement.dataset.placeholder || 'Selecione',
        allowEmptyOption: false,
    });

    const fetchData = url =>
        fetch(url)
            .then(res => res.ok ? res.json() : [])
            .catch(() => []);

    const clearCities = () => {
        citySelect.clearOptions();
        citySelect.clear(true);
    };

    stateSelect.on('change', async value => {
        clearCities();
        if (!value) return;

        const cities = await fetchData(
            `/api/states/${encodeURIComponent(value)}/cities`
        );

        cities.forEach(c => {
            citySelect.addOption({ value: c.id, text: c.name });
        });
        citySelect.refreshOptions(false);
    });

    const initialState = stateSelect.getValue();
    if (initialState) {
        stateSelect.trigger('change', initialState);
    }
});
