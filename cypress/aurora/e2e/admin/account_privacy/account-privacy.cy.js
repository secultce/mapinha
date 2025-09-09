describe('Painel de Controle - Página de Conta e Privacidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/login');
        cy.get('[data-cy="email"]').type('talysonsoares@example.com');
        cy.get('[data-cy="password"]').type('Aurora@2024');
        cy.get('[data-cy="submit"]').click();
        cy.visit('/painel');
    });

    it('Deve acessar a página de Conta e Privacidade a partir do menu do usuário', () => {
        cy.url().should('include', '/painel');
        cy.contains('Olá, Talyson Soares', { timeout: 10000 }).should('be.visible');

        cy.get('.navbar').contains('Talyson', { timeout: 10000 })
            .should('be.visible')
            .click();

        cy.contains('Conta e Privacidade', { timeout: 10000 })
            .should('be.visible')
            .click();
        cy.url().should('include', '/account-privacy');
        cy.contains('Configurações da conta do usuário', { timeout: 10000 }).should('be.visible');
    });
});
