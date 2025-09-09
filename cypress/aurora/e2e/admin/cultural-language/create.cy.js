describe('Painel de Controle - Página de criar Linguagem Cultural', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(10) > .nav-link > .font-title').contains('Linguagem cultural').click();
    })

    it('Garante que é possível criar uma nova linguagem cultural ', () => {
        cy.get('.card > .d-flex > div > .btn').should('contain.text', 'Criar').click();
        cy.url().should('include', '/linguagem-cultural/adicionar');
        cy.get('h2').should('contain.text', 'Criar linguagem cultural');
        cy.get('.mb-3 > .mb-1').should('contain.text', 'Nome');
        cy.get('[data-cy="name"]').type('Futebol');
        cy.get('[data-cy="submit"]').click();
        cy.get('tbody > :nth-child(4) > :nth-child(1)').should('contain.text', 'Futebol');
    });
})