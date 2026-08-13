document.addEventListener('DOMContentLoaded', function () {
    const profileInput = document.getElementById('profile-input');
    const profileImg = document.getElementById('profile-img');

    const bannerInput = document.getElementById('banner-input');
    const bannerDiv = document.querySelector('.banner');

    const submitButton = document.querySelector('button[type="submit"]');
    const errorElement = document.getElementById('image-error');

    if (profileInput) {
        profileInput.addEventListener('change', function (event) {
            handleImageUpload(event.target, 2000000, function(readerResult) {
                profileImg.src = readerResult;
            });
        });
    }

    if (bannerInput) {
        bannerInput.addEventListener('change', function (event) {
            handleImageUpload(event.target, 5000000, function(readerResult) {
                bannerDiv.style.backgroundImage = `url('${readerResult}')`;
            });
        });
    }

    function handleImageUpload(input, maxSize, updatePreviewCallback) {
        const file = input.files[0];
        const allowedTypes = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp'];

        clearError();

        if (!file) {
            return;
        }

        if (file.size > maxSize) {
            const sizeInMB = maxSize / 1000000;
            showError(`O tamanho da imagem não pode exceder ${sizeInMB}MB.`);
            resetInput(input);
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            showError('A imagem deve estar no formato png, jpg ou jpeg.');
            resetInput(input);
            return;
        }

        enableSubmit();

        const reader = new FileReader();
        reader.onload = function (e) {
            updatePreviewCallback(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    function showError(message) {
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('text-danger', 'mt-2');
            errorElement.style.display = 'block';
        } else {
            alert(message);
        }
    }

    function clearError() {
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
        }
    }

    function resetInput(inputElement) {
        inputElement.value = '';
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
});