describe('Painel de Controle - Página de listar FAQs', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/faq');
    });

    it('Garante que seja possível remover uma FAQ', () => {
        cy.get('h2').contains('Minhas FAQs').should('be.visible');
        cy.contains('Quais oportunidades existem para pesquisadores acadêmicos no Brasil?').should('be.visible');

        cy.get('tbody > tr').last().within(() => {
            cy.contains('Excluir').click();
        });

        cy.get('.modal-dialog', { timeout: 10000 }).should('be.visible');

        cy.get('.modal-dialog').within(() => {
            cy.contains('Confirmar').click();
        });

        cy.get('.success.snackbar').contains('A pergunta foi excluída').should('be.visible');
    });
});
