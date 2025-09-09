describe('Teste para Criar Tipo de espaço', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(8) > .nav-link').contains('Tipo de Espaço').click()
    });

    it('Cadastrar um novo tipo de espaço', () => {
        cy.get('.d-flex > .btn').contains('Criar').click();

        cy.get('[data-cy="name"]').should('exist');
        cy.get('[data-cy="name"]').type('Lupanar');
        cy.get('[type="submit"]').click();

        cy.contains('Criar').click();

        cy.get('[data-cy="name"]').should('exist');

        cy.get('[data-cy="name"]').type('P');
        cy.get('[type="submit"]').click();
        cy.get('.danger.snackbar').contains('O valor é muito curto. Deveria de ter 2 caracteres ou mais.').should('be.visible');
    });
});
