describe('Teste para Editar Tipo de Espaço', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(8) > .nav-link')
            .contains('Tipo de Espaço')
            .click();
    });

    it('Editar um tipo de espaço existente', () => {
        cy.get('tbody tr').first().as('linhaAlvo');

        cy.get('@linhaAlvo')
            .find('td')
            .eq(1)
            .invoke('text')
            .then((nomeOriginal) => {
                cy.get('@linhaAlvo').within(() => {
                    cy.contains('Editar').click();
                });

                cy.get('[data-cy="name"]').should('have.value', nomeOriginal);

                cy.get('[data-cy="name"]').clear().type('Anfiteatro');
                cy.get('[data-cy="submit"]').click();

                cy.get('.success.snackbar')
                    .should('be.visible')
                    .and('contain', 'O Tipo de Espaço foi atualizado');

                cy.contains('Anfiteatro').should('be.visible');
            });
    });

    it('Tenta editar com um nome inválido', () => {
        cy.get('tbody tr').first().within(() => {
            cy.contains('Editar').click();
        });

        cy.get('[data-cy="name"]').clear().type('P');
        cy.get('[data-cy="submit"]').click();

        cy.get('.danger.snackbar')
            .should('be.visible')
            .and(
                'contain',
                'O valor é muito curto. Deveria de ter 2 caracteres ou mais.'
            );
    });
});
