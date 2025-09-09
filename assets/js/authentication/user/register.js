import "../../../app.js";
import "../../mask.js";

import {
    trans,
    VIEW_AUTHENTICATION_ERROR_FIRST_NAME_LENGTH,
    VIEW_AUTHENTICATION_ERROR_INVALID_EMAIL,
    VIEW_AUTHENTICATION_ERROR_INVALID_PASSWORD,
    VIEW_AUTHENTICATION_ERROR_LAST_NAME_LENGTH,
    VIEW_AUTHENTICATION_ERROR_PASSWORD_MISMATCH,
    VIEW_AUTHENTICATION_ERROR_CPF_INVALID,
    VIEW_AUTHENTICATION_ERROR_PHONE_INVALID,
    VIEW_AUTHENTICATION_ERROR_EMAIL_IN_USE,
    VIEW_AUTHENTICATION_ERROR_CNPJ_INVALID
} from "../../../translator.js";

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const btnNext = document.querySelector('.btn-next');
    const errorMessage = document.getElementById('error-message');

    const inputs = {
        firstName: document.querySelector('input[name="first_name"]'),
        lastName: document.querySelector('input[name="last_name"]'),
        cpf: document.querySelector('input[name="cpf"]'),
        phone: document.querySelector('input[name="phone"]'),
        email: document.querySelector('input[name="email"]'),
        password: document.querySelector('input[name="password"]'),
        confirmPassword: document.querySelector('input[name="confirm_password"]'),
        cnpj: document.querySelector('input[name="cnpj"]')
    };

    const progressBar = document.getElementById('progressBar');
    const strengthMessage = document.getElementById('strengthMessage');

    function getValidations() {
        const firstName = inputs.firstName?.value.trim() || '';
        const lastName = inputs.lastName?.value.trim() || '';
        const cpf = inputs.cpf?.value.trim() || '';
        const phone = inputs.phone?.value.trim() || '';
        const email = inputs.email?.value.trim() || '';
        const password = inputs.password?.value.trim() || '';
        const confirmPassword = inputs.confirmPassword?.value.trim() || '';
        const cnpj = inputs.cnpj?.value.trim() || '';

        const validations = [
            {
                valid: () => validateName(firstName),
                input: inputs.firstName,
                message: trans(VIEW_AUTHENTICATION_ERROR_FIRST_NAME_LENGTH)
            },
            {
                valid: () => validateName(lastName),
                input: inputs.lastName,
                message: trans(VIEW_AUTHENTICATION_ERROR_LAST_NAME_LENGTH)
            },
            {
                valid: () => validateCpf(cpf),
                input: inputs.cpf,
                message: trans(VIEW_AUTHENTICATION_ERROR_CPF_INVALID)
            },
            ...(inputs.phone ? [{
                valid: () => validatePhone(phone),
                input: inputs.phone,
                message: trans(VIEW_AUTHENTICATION_ERROR_PHONE_INVALID)
            }] : []),
            {
                valid: () => validateEmail(email),
                input: inputs.email,
                message: trans(VIEW_AUTHENTICATION_ERROR_INVALID_EMAIL)
            },
            {
                valid: () => validatePassword(password),
                input: inputs.password,
                message: trans(VIEW_AUTHENTICATION_ERROR_INVALID_PASSWORD)
            },
            {
                valid: () => validateConfirmPassword(password, confirmPassword),
                input: inputs.confirmPassword,
                message: trans(VIEW_AUTHENTICATION_ERROR_PASSWORD_MISMATCH)
            },
            {
                valid: () => calculatePasswordStrength(password) === 5,
                input: inputs.password,
                message: trans(VIEW_AUTHENTICATION_ERROR_INVALID_PASSWORD)
            }
        ];

        if (inputs.phone) {
            validations.push({
                valid: () => validatePhone(phone),
                input: inputs.phone,
                message: trans(VIEW_AUTHENTICATION_ERROR_PHONE_INVALID)
            });
        }

        if (inputs.email) {
            validations.push({
                valid: () => validateEmail(email),
                input: inputs.email,
                message: trans(VIEW_AUTHENTICATION_ERROR_INVALID_EMAIL)
            });
        }

        if (inputs.cnpj) {
            validations.push({
                valid: () => validateCnpj(cnpj),
                input: inputs.cnpj,
                message: trans(VIEW_AUTHENTICATION_ERROR_CNPJ_INVALID)
            });
        }

        return validations;
    }

    async function checkEmailExists(email) {
        try {
            const response = await fetch(`/api/users/exists?email=${encodeURIComponent(email)}`);
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error('Erro ao verificar o email:', error);
            return false;
        }
    }

    async function validateField(input) {
        const validations = getValidations();
        const rule = validations.find(r => r.input === input);
        if (!rule) return true;

        const isValid = await rule.valid();

        if (isValid) {
            errorMessage.classList.add('d-none');
            input.classList.remove('border-danger');
            return true;
        } else {
            errorMessage.textContent = rule.message;
            errorMessage.classList.remove('d-none');
            input.classList.add('border-danger');
            return false;
        }
    }

    async function validateFields() {
        const validations = getValidations();
        Object.values(inputs).forEach(i => i && i.classList.remove('border-danger'));

        for (const rule of validations) {
            const ok = await rule.valid();
            if (!ok) {
                errorMessage.textContent = rule.message;
                errorMessage.classList.remove('d-none');
                if (rule.input) rule.input.classList.add('border-danger');
                return false;
            }
        }

        errorMessage.classList.add('d-none');
        return true;
    }

    async function validateEmailAvailability() {
        const email = inputs.email.value.trim();
        if (!validateEmail(email)) {
            inputs.email.classList.add('border-danger');
            errorMessage.textContent = trans(VIEW_AUTHENTICATION_ERROR_INVALID_EMAIL);
            errorMessage.classList.remove('d-none');
            return false;
        }

        const exists = await checkEmailExists(email);
        if (exists) {
            inputs.email.classList.add('border-danger');
            errorMessage.textContent = trans(VIEW_AUTHENTICATION_ERROR_EMAIL_IN_USE);
            errorMessage.classList.remove('d-none');
            return false;
        }

        inputs.email.classList.remove('border-danger');
        errorMessage.classList.add('d-none');
        return true;
    }

    async function updateBtnNextState() {
        const validations = getValidations();
        const results = await Promise.all(validations.map(rule => rule.valid()));
        btnNext.disabled = results.includes(false);
    }

    Object.entries(inputs).forEach(([key, input]) => {
        if (!input) return;

        if (key === 'email') {
            input.addEventListener('input', async () => {
                await validateEmailAvailability();
                await updateBtnNextState();
            });
        } else {
            input.addEventListener('input', async e => {
                await validateField(e.target);
                await updateBtnNextState();
            });
        }
    });

    if (inputs.password) {
        inputs.password.addEventListener('input', () => {
            updatePasswordStrength(inputs.password.value);
        });
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        const isFieldsValid = await validateFields();
        if (!isFieldsValid) {
            await updateBtnNextState();
            return;
        }

        const isEmailAvailable = inputs.email ? await validateEmailAvailability() : true;
        if (!isEmailAvailable) {
            await updateBtnNextState();
            return;
        }

        form.submit();
    });

    updateBtnNextState();

    function updatePasswordStrength(password) {
        if (!progressBar) return;

        if (password.length === 0) {
            progressBar.classList.add('d-none');
            if (strengthMessage) strengthMessage.textContent = '';
            return;
        }

        progressBar.classList.remove('d-none');

        const strength = calculatePasswordStrength(password);
        const strengthPercentage = (strength / 5) * 100;

        progressBar.style.width = strengthPercentage + '%';

        if (strengthPercentage <= 40) {
            progressBar.classList.add('bg-danger');
            progressBar.classList.remove('bg-warning', 'bg-success');
            if (strengthMessage) strengthMessage.textContent = 'Senha fraca';
        } else if (strengthPercentage <= 80) {
            progressBar.classList.add('bg-warning');
            progressBar.classList.remove('bg-danger', 'bg-success');
            if (strengthMessage) strengthMessage.textContent = 'Senha média';
        } else {
            progressBar.classList.add('bg-success');
            progressBar.classList.remove('bg-danger', 'bg-warning');
            if (strengthMessage) strengthMessage.textContent = 'Senha forte';
        }
    }
});

function calculatePasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/\d/.test(password)) strength += 1;
    if (/[\W_]/.test(password)) strength += 1;
    return strength;
}

function validateName(name) {
    return name.length >= 2 && name.length <= 50;
}

function validateEmail(email) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailPattern.test(email) && email.length <= 100;
}

function validateCpf(cpf) {
    const cpfPattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
    const invalidCpfs = [
        '000.000.000-00','111.111.111-11','222.222.222-22','333.333.333-33',
        '444.444.444-44','555.555.555-55','666.666.666-66','777.777.777-77',
        '888.888.888-88','999.999.999-99'
    ];
    if (!cpfPattern.test(cpf) || 14 !== cpf.length || invalidCpfs.includes(cpf)) return false;
    const digits = cpf.replace(/\D/g, '');
    const firstSum = Array.from(digits.slice(0, 9)).reduce((acc, digit, index) => acc + digit * (10 - index), 0);
    const firstCheckDigit = ((firstSum * 10) % 11) % 10;
    if (firstCheckDigit !== parseInt(digits[9], 10)) return false;
    const secondSum = Array.from(digits.slice(0, 10)).reduce((acc, digit, index) => acc + digit * (11 - index), 0);
    const secondCheckDigit = ((secondSum * 10) % 11) % 10;
    return secondCheckDigit === parseInt(digits[10], 10);
}

function validatePhone(phone) {
    const phonePattern = /^\(\d{2}\)\s\d{1}\s\d{4}-\d{4}$/;
    return phonePattern.test(phone) && phone.length <= 16;
}

function validatePassword(password) {
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return passwordPattern.test(password) && password.length <= 255;
}

function validateConfirmPassword(password, confirmPassword) {
    return password === confirmPassword;
}

function validateCnpj(cnpj) {
    const cleaned = String(cnpj).replace(/[^\d]/g, '');
    if (cleaned.length !== 14) return false;
    if (/^(\d)\1{13}$/.test(cleaned)) return false;
    const weight = [6,5,4,3,2,9,8,7,6,5,4,3,2];
    let sum = 0;
    for (let i = 0; i < 12; i++) sum += Number(cleaned[i]) * weight[i+1];
    let remainder = sum % 11;
    const digit1 = remainder < 2 ? 0 : 11 - remainder;
    if (digit1 !== Number(cleaned[12])) return false;
    sum = 0;
    for (let i = 0; i < 13; i++) sum += Number(cleaned[i]) * weight[i];
    remainder = sum % 11;
    const digit2 = remainder < 2 ? 0 : 11 - remainder;
    if (digit2 !== Number(cleaned[13])) return false;
    return true;
}
