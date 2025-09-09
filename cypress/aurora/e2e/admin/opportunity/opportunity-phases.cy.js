describe('Acessar Fases de uma Oportunidade', () => {
    it('Deve acessar as fases de uma oportunidade', () => {
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');

        cy.contains('Henrique').click();
        cy.contains('Minhas Oportunidades').click();
        cy.url().should('include', '/painel/oportunidades');
        cy.contains('Credenciamento de Quadrilhas Juninas - São João do Nordeste').click();
        cy.url().should('include', '/painel/oportunidades');
        cy.contains('Fases').click();

        cy.get('#phases').within(() => {
            cy.contains('Fase de submissão').should('be.visible');
            cy.contains('Fase de documentação').should('be.visible');
        });
    });
});
