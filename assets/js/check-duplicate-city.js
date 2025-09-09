import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const cityElement   = document.getElementById('city');
    const errorElement  = document.getElementById('error-message');

    cityElement .classList.remove('form-select');

    const citySelect = cityElement.tomselect;

    const fetchData = url =>
        fetch(url)
            .then(res => res.ok ? res.json() : [])
            .catch(() => []);

    const showError = msg => {
        if (!errorElement) return;
        errorElement.textContent = msg;
        errorElement.classList.remove('d-none');
    };
    const hideError = () => {
        if (!errorElement) return;
        errorElement.textContent = '';
        errorElement.classList.add('d-none');
    };

    citySelect.on('change', async value => {
        hideError();
        if (!value) return;

        const name = citySelect.options[value]?.text?.trim();
        if (!name) return;

        const { exists } = await fetchData(
            `/organizations/check-duplicate?name=${encodeURIComponent(name)}&cityId=${encodeURIComponent(value)}`
        );

        if (exists) {
            showError(
                `Este município já foi credenciado. Entre em contato com ${window.SNP_EMAIL} para mais informações.`
            );
            citySelect.clear(true);
        }
    });
});
