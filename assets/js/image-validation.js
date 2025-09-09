const profileInput = document.getElementById('profile-input');
const submitButton = document.querySelector('button[type="submit"]');
const imgElement = document.getElementById('profile-img');
const errorElement = document.getElementById('image-error');

profileInput.addEventListener('change', function (event) {
    const input = event.target;
    const file = input.files[0];
    const maxSize = 2000000;
    const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];

    clearError();

    if (!file) {
        return;
    }

    if (file.size > maxSize) {
        showError('O tamanho da imagem não pode exceder 2MB.');
        resetInput();
        return;
    }

    if (!allowedTypes.includes(file.type)) {
        showError('A imagem deve estar no formato png, jpg ou jpeg.');
        resetInput();
        return;
    }

    enableSubmit();

    const reader = new FileReader();
    reader.onload = function () {
        imgElement.src = reader.result;
    };
    reader.readAsDataURL(file);
});

function showError(message) {
    errorElement.textContent = message;
    errorElement.classList.add('text-danger', 'mt-2');
}

function clearError() {
    errorElement.textContent = '';
}

function resetInput() {
    profileInput.value = '';
    imgElement.src = '';
    disableSubmit();
}

function disableSubmit() {
    if (submitButton) {
        submitButton.disabled = true;
    }
}

function enableSubmit() {
    if (submitButton) {
        submitButton.disabled = false;
    }
}
