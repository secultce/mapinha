describe('Painel de Controle - Página de listar Agentes', () => {
    before(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/agentes');
    });

    it('Garante que a página de Agentes existe e funciona', () => {
        cy.get('h2').contains('Meus Agentes').should('be.visible');

        cy.get('tbody > tr > :nth-child(1)')
            .contains('Henrique')
            .should('be.visible');

        cy.get('tbody > tr > :nth-child(3)')
            .contains('11/07/2024 10:49:00')
            .should('be.visible');

        cy.get('.table').should('not.contain', 'Excluir');
    });
});
