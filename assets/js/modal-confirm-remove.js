function confirmRemove(triggerButton, title = null) {
    const url = triggerButton.getAttribute('data-href');
    const confirmLink = document.querySelector('[data-modal-button="confirm-link"]');
    confirmLink.setAttribute('href', url);

    if (title) {
        const modalTitle = document.getElementById('staticBackdropLabel');
        if (modalTitle) {
            modalTitle.textContent = title;
        }
    }
}

window.confirmRemove = confirmRemove;
