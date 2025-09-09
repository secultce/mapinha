describe('Painel de Controle - Página de detalhes de uma Oportunidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/oportunidades/39994097-41d8-463c-a7eb-7d9a0b40e953');
    });

    it('Garante que a página de detalhes da oportunidade está funcionando corretamente', () => {
        cy.get('h2')
            .contains('Chamada para Oficinas de Artesanato - Feira de Cultura Popular')
            .should('be.visible');

        cy.get('.nav-pills > li > a').contains('Fases').click();
        cy.get('#phases').should('be.visible');

        cy.get('a').contains('Timeline').click();

        cy.url().should('include', '/timeline');
        cy.url().should('include', '/phases');
        cy.get('h2').contains('Fase de submissão - Timeline').should('be.visible');
    });
});
