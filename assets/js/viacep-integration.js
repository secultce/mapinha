import {
    trans,
    ZIP_CODE_NOT_FOUND,
    ZIP_CODE_CITY_NOT_FOUND,
    ZIP_CODE_GENERIC_ERROR,
    ZIP_CODE_INVALID,
    ZIP_CODE_STATE_NOT_FOUND,
    ZIP_CODE_NUMBER_REQUIRED,
} from "../translator.js";

document.addEventListener('DOMContentLoaded', () => {
    const DOM = {
        cepInput: document.getElementById('cep'),
        streetInput: document.getElementById('street'),
        numberInput: document.getElementById('number'),
        neighborhoodInput: document.getElementById('neighborhood'),
        addressComplementInput: document.getElementById('address_complement'),
        noNumberCheckbox: document.getElementById('no_number'),
        numberErrorElement: document.getElementById('number-error-message'),
        mapIframe: document.querySelector('.ratio iframe'),
        stateSelectElement: document.getElementById('state'),
        citySelectElement: document.getElementById('city'),
        cepErrorMessageContainer: document.getElementById('cep-error-message') || createCepErrorMessageContainer(document.getElementById('cep')),
    };

    const stateSelectInstance = DOM.stateSelectElement ? DOM.stateSelectElement.tomselect : null;
    const citySelectInstance = DOM.citySelectElement ? DOM.citySelectElement.tomselect : null;

    function createCepErrorMessageContainer(referenceElement) {
        const errorDiv = document.createElement('div');
        errorDiv.id = 'cep-error-message';
        errorDiv.style.display = 'none';
        if (referenceElement && referenceElement.parentNode) {
            referenceElement.parentNode.insertBefore(errorDiv, referenceElement.nextSibling);
        }
        return errorDiv;
    }

    function toggleErrorMessage(element, message, isVisible, typeClass = 'text-danger') {
        element.textContent = message;
        element.classList.toggle(typeClass, isVisible);
        element.classList.toggle('mt-2', isVisible);
        element.style.display = isVisible ? 'block' : 'none';
    }

    const showError = (message) => toggleErrorMessage(DOM.cepErrorMessageContainer, message, true);
    const clearError = () => toggleErrorMessage(DOM.cepErrorMessageContainer, '', false);

    const showNumberError = (message) => toggleErrorMessage(DOM.numberErrorElement, message, true);
    const clearNumberError = () => toggleErrorMessage(DOM.numberErrorElement, '', false);

    function clearAddressFields() {
        DOM.streetInput.value = '';
        DOM.numberInput.value = '';
        DOM.neighborhoodInput.value = '';
        DOM.addressComplementInput.value = '';

        if (stateSelectInstance) {
            stateSelectInstance.setValue('');
        }
        if (citySelectInstance) {
            citySelectInstance.clearOptions();
            citySelectInstance.clear();
        }
        DOM.mapIframe.src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d75897.0345148201!2d-38.627535961048046!3d-3.704056341712097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x7c749006cf93087%3A0x203684bff0b2f209!2sEd.%20S.%20Pedro%20(marco%20hist%C3%B3rico%20demolido)!5e1!3m2!1spt-BR!2sbr!4v1739989008210!5m2!1spt-BR!2sbr';
    }

    function findAndSetTomSelectValue(instance, apiValue, apiText, errorMessageKey) {
        if (!instance) return false;

        let selectedValue = '';
        for (const optionId in instance.options) {
            const option = instance.options[optionId];
            if (option.value === apiValue || option.text.toUpperCase() === apiText.toUpperCase()) {
                selectedValue = option.value;
                break;
            }
        }

        if (selectedValue) {
            instance.setValue(selectedValue);
            if (instance === stateSelectInstance) {
                instance.trigger('change', selectedValue);
            }
            return true;
        } else {
            instance.setValue('');
            if (instance === stateSelectInstance && citySelectInstance) {
                citySelectInstance.clearOptions();
                citySelectInstance.clear();
            }
            showError(trans(errorMessageKey));
            return false;
        }
    }

    async function fetchAddressByCep() {
        const cep = DOM.cepInput.value.replace(/\D/g, '');
        clearError();

        if (cep.length !== 8) {
            if (cep.length > 0) {
                showError(trans(ZIP_CODE_INVALID));
            }
            clearAddressFields();
            return;
        }

        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();

            if (data.erro) {
                showError(trans(ZIP_CODE_NOT_FOUND));
                clearAddressFields();
                return;
            }

            DOM.streetInput.value = data.logradouro || '';
            DOM.neighborhoodInput.value = data.bairro || '';
            DOM.addressComplementInput.value = data.complemento || '';

            const stateSet = findAndSetTomSelectValue(stateSelectInstance, data.uf, data.estado, ZIP_CODE_STATE_NOT_FOUND);

            setTimeout(() => {
                if (stateSet) {
                    findAndSetTomSelectValue(citySelectInstance, data.localidade, data.localidade, ZIP_CODE_CITY_NOT_FOUND);
                } else if (!stateSelectInstance || stateSelectInstance.getValue() === '') {
                    if (citySelectInstance) {
                        citySelectInstance.clearOptions();
                        citySelectInstance.clear();
                    }
                    showError(trans(ZIP_CODE_CITY_NOT_FOUND));
                }
            }, 600);

            const fullAddress = `${data.logradouro || ''}, ${data.bairro || ''}, ${data.localidade || ''} - ${data.uf || ''}, ${data.cep || ''}, Brasil`;
            const encodedAddress = encodeURIComponent(fullAddress);
            DOM.mapIframe.src = `https://maps.google.com/maps?q=${encodedAddress}&t=&z=15&ie=UTF8&iwloc=&output=embed`;

        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
            showError(trans(ZIP_CODE_GENERIC_ERROR));
            clearAddressFields();
        }
    }

    DOM.cepInput.addEventListener('blur', fetchAddressByCep);

    DOM.numberInput.addEventListener('blur', () => {
        if (!DOM.noNumberCheckbox.checked && DOM.numberInput.value.trim() === '') {
            showNumberError(trans(ZIP_CODE_NUMBER_REQUIRED));
        } else {
            clearNumberError();
        }
    });

    DOM.noNumberCheckbox.addEventListener('change', () => {
        if (DOM.noNumberCheckbox.checked) {
            DOM.numberInput.value = '';
            DOM.numberInput.disabled = true;
            clearNumberError();
        } else {
            DOM.numberInput.disabled = false;
            if (DOM.numberInput.value.trim() === '') {
                showNumberError(trans(ZIP_CODE_NUMBER_REQUIRED));
            }
        }
    });
});
