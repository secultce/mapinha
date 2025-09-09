describe('Painel de Controle - Página de listar Organizações', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/organizacoes');
    });

    it('Garante que a página de listar organizações existe e funciona', () => {
        cy.get('h2').contains('Minhas Organizações').should('be.visible');

        cy.get('tbody > tr > :nth-child(1)').contains('PHPeste').should('be.visible');
        cy.get('tbody >  tr > :nth-child(3)').contains('22/07/2024 16:20:00').should('be.visible');
        //Garante que vai excluir
        cy.get('button[data-cy="remove-2"]').click();
        cy.get('[data-modal-button="confirm-link"]').click();

        cy.get('.table').should('not.contain', 'Cultura em ação');
        cy.get('.success.snackbar').contains('A Organização foi excluída').should('be.visible');
    });
});
