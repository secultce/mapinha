describe('Teste de navegação e validação da página de Minhas FAQs', () => {
    it('Deve acessar a página de FaQ a partir de Oportunidades após login', () => {
        cy.visit('/');

        cy.contains('Entrar').click();

        cy.url().should('include', '/login');

        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');

        cy.url().should('include', '/');

        cy.get('.navbar').contains('Francisco').should('be.visible');
        cy.get('.navbar').contains('Francisco').click();

        cy.contains('Minhas Oportunidades', { timeout: 10000 }).should('be.visible').click();

        cy.url({ timeout: 10000 }).should('include', '/painel/oportunidades');

        cy.scrollTo('bottom');

        cy.contains('FaQ', { timeout: 10000 }).should('be.visible').click();

        cy.url({ timeout: 10000 }).should('include', '/painel/faq/');

        cy.get('table', { timeout: 10000 }).should('be.visible');
    });
});
