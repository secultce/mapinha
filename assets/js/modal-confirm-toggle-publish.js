function confirmTogglePublish(event) {
    const href = event.getAttribute('data-href');
    document.querySelector('[data-modal-button="confirm-link-toggle-publish"]').setAttribute('href', href);
    document.getElementById('modalTitleConfirmTogglePublish').textContent = event.textContent;
}
