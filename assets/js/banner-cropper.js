document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-cropper-input')
        .forEach(input => new ImageCropper(input));
});

class ImageCropper {
    constructor(inputElement) {
        this.input = inputElement;

        this.config = {
            modalId: this.input.dataset.modalId,
            previewSelector: this.input.dataset.preview,
            aspectRatio: parseFloat(this.input.dataset.aspectRatio) || (16 / 9)
        };

        this.modalEl = document.getElementById(this.config.modalId);

        if (!this.modalEl) {
            return console.error(`ImageCropper: Modal "${this.config.modalId}" não encontrado.`);
        }

        this.imageEl = this.modalEl.querySelector('.image-to-crop');
        this.saveBtn = this.modalEl.querySelector('.crop-save-btn');
        this.previewEl = document.querySelector(this.config.previewSelector);

        this.cropper = null;
        this.bsModal = this.getBootstrapModal();

        this.init();
    }

    init() {
        if (!this.imageEl) return console.error('ImageCropper: Imagem .image-to-crop não encontrada.');

        this.input.addEventListener('change', (e) => this.handleInputChange(e));
        this.modalEl.addEventListener('shown.bs.modal', () => this.initCropperInstance());
        this.modalEl.addEventListener('hidden.bs.modal', () => this.destroyCropperInstance());

        if (this.saveBtn) {
            this.saveBtn.addEventListener('click', () => this.handleSave());
        }
    }

    getBootstrapModal() {
        if (typeof bootstrap !== 'undefined') return new bootstrap.Modal(this.modalEl);
        if (window.bootstrap) return new window.bootstrap.Modal(this.modalEl);
        return null;
    }

    handleInputChange(e) {
        const files = e.target.files;

        if (!files || files.length === 0) return;
        const file = files[0];
        this.input.value = '';
        this.imageEl.src = URL.createObjectURL(file);
        if (this.bsModal) this.bsModal.show();
    }

    initCropperInstance() {
        if (this.cropper) this.cropper.destroy();

        this.cropper = new Cropper(this.imageEl, {
            aspectRatio: this.config.aspectRatio,
            viewMode: 1,
            autoCropArea: 1,
        });
    }

    destroyCropperInstance() {
        if (!this.cropper) return;

        this.cropper.destroy();
        this.cropper = null;
        this.imageEl.src = '';
    }

    handleSave() {
        if (!this.cropper) return;

        this.cropper.getCroppedCanvas({
            width: 1920,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob((blob) => this.processBlob(blob), 'image/jpeg', 0.9);
    }

    processBlob(blob) {
        if (!blob) return;

        const file = new File([blob], "imagem-editada.jpg", {
            type: "image/jpeg",
            lastModified: Date.now()
        });

        const container = new DataTransfer();
        container.items.add(file);
        this.input.files = container.files;
        this.updatePreview(blob);

        if (this.bsModal) this.bsModal.hide();
    }

    updatePreview(blob) {
        if (!this.previewEl) return;

        const url = URL.createObjectURL(blob);

        if (this.previewEl.tagName === 'IMG') {
            this.previewEl.src = url;
            return;
        }

        this.previewEl.style.backgroundImage = `url('${url}')`;
    }
}