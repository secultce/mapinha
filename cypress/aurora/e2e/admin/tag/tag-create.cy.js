describe('Teste para Criar Tag', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/tags/adicionar');
    });

    it('Cadastrar uma nova tag', () => {
        cy.get('[data-cy="name"]').should('exist');

        cy.get('[data-cy="name"]').type('Skate');
        cy.get('[type="submit"]').click();

        cy.contains('Criar').click();
        
        cy.get('[data-cy="name"]').should('exist');

        cy.get('[data-cy="name"]').type('P');
        cy.get('[type="submit"]').click();
        cy.get('.danger.snackbar').contains('O valor é muito curto. Deveria de ter 2 caracteres ou mais.').should('be.visible');
    });
});
