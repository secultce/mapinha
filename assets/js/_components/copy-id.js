import { COPY_SUCCESS, COPY_ERROR, COPY_AGAIN, trans } from '../../translator.js';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.copy-id[data-id]').forEach(button => {
        const id = button.dataset.id;

        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(id);
                showToast(`${trans(COPY_SUCCESS)} ${id}`, 'success');
            } catch (err) {
                console.error(`${trans(COPY_ERROR)}:`, err);
                showToast(trans(COPY_AGAIN), 'danger');
            }
        });
    });
});

function showToast(message, type = 'success') {
    const container = document.querySelector('.toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type} snackbar`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.setAttribute('data-bs-delay', '4000');
    toast.setAttribute('data-bs-autohide', 'true');

    toast.innerHTML = `
    <div class="d-flex">
      <span class="icon material-icons align-self-start">check_circle</span>
      <div class="toast-body">${message}</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  `;

    container.appendChild(toast);
    new bootstrap.Toast(toast).show();
}
