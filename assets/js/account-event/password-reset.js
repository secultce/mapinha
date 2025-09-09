import "../../app.js";
import "../mask.js";


import {
    trans,
    VIEW_AUTHENTICATION_ERROR_INVALID_PASSWORD,
    VIEW_AUTHENTICATION_ERROR_PASSWORD_MISMATCH,
} from "../../translator.js";

document.addEventListener('DOMContentLoaded', function () {
    const btnSubmit = document.querySelector('.btn');
    const form = document.querySelector('form');
    const errorMessageElement = document.getElementById('error-message');

    const inputs = {
        password: document.querySelector('input[name="password"]'),
        confirmPassword: document.querySelector('input[name="confirm_password"]')
    };

    const progressBar = document.querySelector('#passwordStrength .progress-bar');

    btnSubmit.disabled = true;

    async function validateFields() {
        const password = inputs.password.value.trim();
        const confirmPassword = inputs.confirmPassword.value.trim();
        let errorMessage = '';

        Object.values(inputs).forEach(input => {
            input.classList.remove('border-danger');
        });

        if (!validatePassword(password)) {
            errorMessage = trans(VIEW_AUTHENTICATION_ERROR_INVALID_PASSWORD);
            inputs.password.classList.add('border-danger');
        } else if (!validateConfirmPassword(password, confirmPassword)) {
            errorMessage = trans(VIEW_AUTHENTICATION_ERROR_PASSWORD_MISMATCH);
            inputs.confirmPassword.classList.add('border-danger');
        }

        if (errorMessage) {
            console.log(errorMessage);
            errorMessageElement.textContent = errorMessage;
            errorMessageElement.classList.remove('d-none');
            btnSubmit.disabled = true;
        } else {
            console.log(errorMessageElement);
            errorMessageElement.classList.add('d-none');
            btnSubmit.disabled = false;
        }
    }

    Object.values(inputs).forEach(input => {
        input.addEventListener('input', validateFields);
    });

    inputs.password.addEventListener('input', function() {
        updatePasswordStrength(inputs.password.value);
    });

    form.addEventListener('submit', async function (event) {
        await validateFields();
        if (btnSubmit.disabled) {
            event.preventDefault();
        }
    });

    function updatePasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength += 1;
        if (/[a-z]/.test(password)) strength += 1;
        if (/[A-Z]/.test(password)) strength += 1;
        if (/\d/.test(password)) strength += 1;
        if (/[\W_]/.test(password)) strength += 1;

        const strengthPercentage = (strength / 5) * 100;
        progressBar.style.width = strengthPercentage + '%';

        if (strengthPercentage <= 40) {
            progressBar.classList.add('bg-danger');
            progressBar.classList.remove('bg-warning', 'bg-success');
        } else if (strengthPercentage <= 80) {
            progressBar.classList.add('bg-warning');
            progressBar.classList.remove('bg-danger', 'bg-success');
        } else {
            progressBar.classList.add('bg-success');
            progressBar.classList.remove('bg-danger', 'bg-warning');
        }
    }
});

function validatePassword(password) {
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return passwordPattern.test(password) && password.length <= 255;
}

function validateConfirmPassword(password, confirmPassword) {
    return password === confirmPassword;
}
