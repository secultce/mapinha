describe('Painel de Controle - Página de listar Tipo de Espaço', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(8) > .nav-link').contains('Tipo de Espaço').click()
    });

    it('Garante que a página de Tipo de Espaço existe', () => {
        cy.get('h2').contains('Tipo de Espaço').should('be.visible');

        cy.get('tbody > :nth-child(2) > :nth-child(2)').contains('Laboratório').should('be.visible');
    });
});
