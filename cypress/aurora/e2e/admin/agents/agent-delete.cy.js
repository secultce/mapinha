describe('Painel de Controle - Página de listar Agentes', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('paulodetarso@example.com', 'Aurora@2024');
        cy.visit('/painel/agentes');
    });

    it('Garante que seja possível deletar um agente', () => {
        cy.get('button[data-cy="remove-1"]').click();
        cy.get('[data-modal-button="confirm-link"]').click();
    
        cy.get('.table').should('not.contain', 'Paulo');
        cy.get('.success.snackbar').contains('O Agente foi excluído').should('be.visible');
    });
})
