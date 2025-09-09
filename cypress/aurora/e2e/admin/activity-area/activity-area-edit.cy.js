describe('Painel de Controle - Editar Área de Atuação', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/area-atuacao');
    });

    it('Garante que a página de edição de Área de Atuação funciona corretamente', () => {
        cy.get('h2').contains('Área de Atuação').should('be.visible');

        cy.contains('Editar').first().click();
        cy.url().should('include', '/painel/area-atuacao/').and('include', '/editar');
        cy.get('input[name="name"]').should('be.visible');

        cy.get('input[name="name"]').clear();
        cy.get('button').contains('Salvar').click();
        cy.get('input[name="name"]:invalid').should('exist');

        cy.get('input[name="name"]').type('Área Editada Cypress');
        cy.get('button').contains('Salvar').click();
        cy.url().should('include', '/painel/area-atuacao');
        cy.contains('Área Editada Cypress').should('be.visible');
    });
});
