describe('Teste de navegação e validação da página de Selos', () => {
    beforeEach( () => {
        cy.viewport(1920,1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/selos');
    });

    it('Deve acessar a página de Selos após login', () => {
        cy.url().should('include', '/painel/selos');

        cy.scrollTo('bottom');

        cy.contains('Selos', { timeout: 10000 }).should('be.visible').click();

        cy.url({ timeout: 10000 }).should('include', '/painel/selos/');

        cy.get('table', { timeout: 10000 }).should('be.visible');

        // Garante exclusão do selo
        cy.get('tr:contains(Sustentabilidade)').contains('Excluir').as('delButton');
        cy.get('@delButton').should('be.visible');
        cy.get('@delButton').click();
        cy.contains('Confirmar').click();
        cy.get('tr:contains(Sustentabilidade)').contains('Inativo').should('be.visible');
        cy.contains('O selo foi excluído').should('be.visible');
    });
});
