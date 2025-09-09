function confirmDecision(event, isApproved = true) {
    const modal = new bootstrap.Modal(event.getAttribute('data-target'), {
        backdrop: 'static',
        keyboard: false
    });

    document.getElementById('approved').value = isApproved;
    const confirmDecisionTitle = document.getElementById('confirmDecisionTitle');
    const confirmDecisionButton = document.getElementById('confirmDecisionButton');
    const modalBody = document.getElementById('confirmDecisionQuestion');

    if (isApproved) {
        confirmDecisionTitle.textContent = 'Confirmar Aprovação';
        confirmDecisionButton.textContent = 'Aprovar';
        confirmDecisionButton.classList.remove('btn-danger');
        confirmDecisionButton.classList.add('btn-primary');
        modalBody.textContent = 'Você tem certeza que deseja aprovar o documento?';
    } else {
        confirmDecisionTitle.textContent = 'Confirmar Recusa';
        confirmDecisionButton.textContent = 'Recusar';
        confirmDecisionButton.classList.remove('btn-primary');
        confirmDecisionButton.classList.add('btn-danger');
        modalBody.textContent = 'Você tem certeza que deseja recusar o documento?';
    }

    modal.show();
}
