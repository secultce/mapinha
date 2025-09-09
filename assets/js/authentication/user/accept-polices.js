document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    const acceptButtons = document.querySelectorAll('.modal-footer .btn-primary');
    const acceptAllCheckbox = document.getElementById('acceptPolicies');
    const errorMessage = document.getElementById('error-message');
    const submitBtn = document.getElementById('submitPolicies');
    let acceptedPolicies = new Set();

    const requiredPolicies = ['modalTerms', 'modalPrivacy', 'modalImage'];

    function updateSubmitButton() {
        submitBtn.disabled = !(requiredPolicies.every((policy) => acceptedPolicies.has(policy)));
    }

    function acceptAllPolicies() {
        requiredPolicies.forEach((policy) => acceptedPolicies.add(policy));
        updateSubmitButton();
    }

    function clearAcceptedPolicies() {
        acceptedPolicies.clear();
        updateSubmitButton();
    }

    function updateAcceptAllCheckbox() {
        acceptAllCheckbox.checked = requiredPolicies.every((policy) => acceptedPolicies.has(policy));
    }

    acceptButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            const modalId = e.target.closest('.modal').id;
            acceptedPolicies.add(modalId);
            updateAcceptAllCheckbox();
            updateSubmitButton();
        });
    });

    acceptAllCheckbox.addEventListener('change', (e) => {
        if (e.target.checked) {
            acceptAllPolicies();
        } else {
            clearAcceptedPolicies();
        }
    });

    form.addEventListener('submit', (e) => {
        const missingPolicies = requiredPolicies.filter((policy) => !acceptedPolicies.has(policy));

        if (missingPolicies.length > 0) {
            e.preventDefault();
            errorMessage.textContent = 'Você deve aceitar todas as políticas antes de continuar.';
            errorMessage.classList.remove('d-none');
            errorMessage.scrollIntoView({ behavior: 'smooth' });
        } else {
            errorMessage.textContent = '';
            errorMessage.classList.add('d-none');
        }
    });

    updateSubmitButton();
});
