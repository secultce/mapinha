function applyMask(input, maskFunction) {
    input.addEventListener("input", () => {
        input.value = maskFunction(input.value);
    });
}

function cpfMask(value) {
    return value
        .replace(/\D/g, '')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
}

function phoneMask(value) {
    return value
        .replace(/\D/g, '')
        .replace(/^(\d{2})(\d)/, '($1) $2')
        .replace(/(\d{1})?(\d{4})(\d{4})$/, '$1 $2-$3');
}

function cnpjMask(value) {
    return value
        .replace(/\D/g, '')
        .replace(/^(\d{2})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1.$2')
        .replace(/(\d{3})(\d)/, '$1/$2')
        .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
}

const cpfInput = document.querySelector('[data-mask="cpf"]');
const phoneInput = document.querySelectorAll('[data-mask="phone"]');
const cnpjInput = document.querySelector('[data-mask="cnpj"]');

if (cpfInput) {
    applyMask(cpfInput, cpfMask);
}

phoneInput.forEach(phoneInput => {
    if (phoneInput) {
        applyMask(phoneInput, phoneMask);
    }
})

if (cnpjInput) {
    applyMask(cnpjInput, cnpjMask);
}
