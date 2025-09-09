describe('Página de listar eventos por espaço', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/espacos/b4a49f4d-25ca-40f9-bac2-e72383b689ed');
        cy.get('#pills-events-tab').click();
        cy.get('#pills-events-tab').should('have.class', 'active');
    });

    it('Garante a grade de eventos', () => {
        cy.get('[data-cy="pills-list-content"] h2').should('be.visible');

        cy.get('#pills-list-sublist-tab').should('have.class', 'active');
        cy.get('#sublist-list').should('have.class', 'active');
        cy.get('#sublist-list .event-card').contains('Cultura em ação').should('be.visible');
        cy.get('#sublist-list .event-card').contains('Dragão do Mar').should('be.visible');

        cy.get('#pills-cards-sublist-tab').click();
        cy.get('#sublist-cards').should('be.visible');
        cy.get('#sublist-cards > .row > .col-md-4').contains('Cultura em ação').should('be.visible');
        cy.get('#sublist-cards > .row > .col-md-4').contains('Dragão do Mar').should('be.visible');

        cy.get('#pills-calendar-sublist-tab').should('be.visible');
    });

    it('Garante que as opções de visualização estão presentes', () => {
        cy.get('a').contains('Dados gerais').should('be.visible');
        cy.get('a').contains('Selos').should('be.visible');
        cy.get('a').contains('Conexões').should('be.visible');
    });
});
