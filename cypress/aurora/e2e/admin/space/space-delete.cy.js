describe('Teste de exclusão de Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');

        cy.intercept('GET', '/painel/espacos/*/remove').as('removeSpace');

        cy.visit('/painel/espacos');
    });

    it('remove um espaço e atualiza a lista', () => {
        cy.get('table.table-hover tbody tr')
            .its('length')
            .then((initialCount) => {
                cy.get('table.table-hover tbody tr')
                    .first()
                    .within(() => {
                        cy.contains('Excluir').click();
                    });

                cy.get('#modalRemoveConfirm').should('be.visible');

                cy.get('[data-modal-button="confirm-link"]').click();
                cy.wait('@removeSpace');

                cy.url().should('include', '/painel/espacos');

                cy.get('.success.snackbar').contains('O Espaço foi excluído').should('be.visible');
            });
    });
});
