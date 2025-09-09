describe('Teste de navegação e validação da página de Selos', () => {
    it('Deve acessar a página de Selos a partir de Oportunidades após login', () => {
        cy.visit('/');

        cy.contains('Entrar').click();
        cy.url().should('include', '/login');
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.url().should('include', '/');
        cy.get('.navbar').contains('Francisco').should('be.visible');

        cy.get('.navbar').contains('Francisco').click();
        cy.contains('Minhas Oportunidades', { timeout: 10000 })
            .should('be.visible')
            .click();
        cy.url({ timeout: 10000 }).should('include', '/painel/oportunidades');
        cy.scrollTo('bottom');
        cy.contains('Selos', { timeout: 10000 })
            .should('be.visible')
            .click();
        cy.url({ timeout: 10000 }).should('include', '/painel/selos/');
        cy.get('table', { timeout: 10000 }).should('be.visible');

        cy.contains('Criar', { timeout: 10000 })
            .should('be.visible')
            .click();
        cy.url({ timeout: 10000 }).should('include', '/painel/selos/adicionar');

        cy.get('button').contains('Salvar').should('be.visible').click();
        cy.get('input:invalid').should('have.length', 1);
        cy.get('textarea:invalid').should('have.length', 1);

        cy.get('input[name="name"]').type('Selo de qualidade');
        cy.get('textarea[name="description"]').type('Descrição do selo');
        cy.get('button').contains('Salvar').should('be.visible').click();

        cy.url({ timeout: 10000 }).should('include', '/painel/selos/');
    });
});
