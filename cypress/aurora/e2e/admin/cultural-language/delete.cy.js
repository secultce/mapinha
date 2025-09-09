describe('Painel de Controle - Página de criar Linguagem Cultural', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(10) > .nav-link > .font-title').contains('Linguagem cultural').click();
    });

    it('Garante que é possível deletar uma linguagem cultural', () => {
        cy.contains('tr', 'Arte e Música')
            .within(() => {
                cy.get('[data-cy="btn-delete"]').click();
            });

        cy.get('.modal-header').should('contain.text', 'Excluir');
        cy.get('.btn-danger').click();

        cy.contains('tr', 'Arte e Música').should('not.exist');
    });

});