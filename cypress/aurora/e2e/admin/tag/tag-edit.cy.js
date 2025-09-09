describe('Teste para Editar Tag', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/tags');
    });

    it('Editar uma tag existente', () => {
        cy.get(':nth-child(4) > :nth-child(2) > .btn-outline-warning').click();

        cy.get('[data-cy="name"]').should('exist').and('have.value', 'Juventude');

        cy.get('[data-cy="name"]').clear()
        cy.get('[data-cy="name"]').type('Juventude Digital');
        cy.get('[type="submit"]').click();

        cy.get('.success.snackbar').contains('A Tag foi atualizada').should('be.visible');

        cy.contains('Juventude Digital').should('be.visible');
    });

    it('Tenta editar com um nome inválido', () => {
        cy.get(':nth-child(4) > :nth-child(2) > .btn-outline-warning').click();

        cy.get('[data-cy="name"]').clear()
        cy.get('[data-cy="name"]').type('P');
        cy.get('[type="submit"]').click();

        cy.get('.danger.snackbar').contains('O valor é muito curto. Deveria de ter 2 caracteres ou mais.').should('be.visible');
    });
});
