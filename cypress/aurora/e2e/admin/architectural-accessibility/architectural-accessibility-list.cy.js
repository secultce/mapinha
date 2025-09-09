describe('Teste de navegação e validação da página de Acessibilidade Arquitetônica', () => {
    it('Deve acessar a página de Acessibilidade Arquitetônica a partir de Oportunidades após login', () => {
        cy.visit('/');

        cy.contains('Entrar').click();
        cy.url().should('include', '/login');
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');

        cy.url().should('include', '/');
        cy.get('.navbar').contains('Francisco').should('be.visible').click();

        cy.contains('Minhas Oportunidades', { timeout: 10000 }).should('be.visible').click();
        cy.url({ timeout: 10000 }).should('include', '/painel/oportunidades');

        cy.scrollTo('bottom');

        cy.contains('Acessibilidade Arquitetônica', { timeout: 10000 })
            .scrollIntoView()
            .should('be.visible')
            .click();

        cy.get('table', { timeout: 10000 }).should('be.visible');

        cy.get('table tbody').then(($tbody) => {
            if ($tbody.find('tr').length > 0) {
                cy.get('table tbody tr td').first().should('not.be.empty');
            } else {
                cy.contains('Nenhum registro encontrado').should('be.visible');
            }
        });
    });
});
