describe('Teste para Deletar Tag', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/tags/');
    });

    it('Deletar uma tag', () => {
        cy.get('tbody tr').contains('Tradição').parent().within(() => {
            cy.get('[data-cy^="remove-"]').click();
        });

        cy.get('.btn-danger').click();

        cy.get('.toast-body').contains('Tag foi excluída').should('be.visible');
        cy.get('.table').contains('Tradição').should('not.exist');
    });
});
