describe('Painel de Controle - Criar Área de Atuação', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/area-atuacao');
    });

    it('Garante que a página de Área de Atuação existe e permite criar uma nova', () => {
        cy.get('h2').contains('Área de Atuação').should('be.visible');

        cy.contains('Criar').click();
        cy.url().should('include', '/painel/area-atuacao/adicionar');
        cy.get('input[name="name"]').should('be.visible');
        cy.get('button').contains('Salvar').click();
        cy.get('input[name="name"]:invalid').should('exist');

        cy.get('input[name="name"]').type('Área Teste Cypress');
        cy.get('button').contains('Salvar').click();
        cy.url().should('include', '/painel/area-atuacao');
    });
});
