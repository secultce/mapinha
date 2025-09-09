describe('Painel de Controle - Página de listar Tags', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/tags');
    });

    it('Garante que a página de Tags existe', () => {
        cy.get('h2').contains('Tags').should('be.visible');

        cy.get('tbody > :nth-child(1) > :nth-child(1)').contains('Cultura').should('be.visible');
    });
});
