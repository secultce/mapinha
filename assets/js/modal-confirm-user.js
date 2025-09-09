function modalConfirmUser(id, name, email) {
    const modal = new bootstrap.Modal('#modalConfirmUser');
    const link = "{id}/confirmar";

    document.querySelector('#user-name').innerHTML = name;
    document.querySelector('#user-email').innerHTML = email;
    document.querySelector('[data-modal-button="confirm-link"]').setAttribute('href', link.replace("{id}", id));

    modal.show();
}
