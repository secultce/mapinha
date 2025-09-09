describe('Página de Cadastrar Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);

        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');

        cy.visit('/painel/espacos/adicionar');
    });

    it('Deve criar um espaço com sucesso', () => {
        cy.get('[data-cy="space-form"]').should('exist').and('be.visible');

        cy.get('[data-cy="name"]').type('Espaço Teste Cypress');
        cy.get('[data-cy="createdBy"]').select('Alessandro');
        cy.get('[data-cy="shortDescription"]').type('Descrição de teste do espaço');

        cy.get('[data-cy="submit"]').click();

        cy.url().should('include', '/painel/espacos');
        cy.contains('Espaço Teste Cypress').should('be.visible');
    });
});
