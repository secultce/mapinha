describe('Dashboard Notifications Tab', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/login');

        cy.get('[data-cy="email"]').type('alessandrofeitoza@example.com');
        cy.get('[data-cy="password"]').type('Aurora@2024');
        cy.get('[data-cy="submit"]').click();

        cy.url().should('include', '/painel');
        cy.contains('Painel de Controle').should('exist');
    });

    it('Accesses and validates the Notifications tab in the dashboard', () => {
        cy.get('#pills-notifications-tab').click();

        cy.get('#pills-notifications').should('be.visible');

        cy.contains('Notificações não lidas').should('exist');
        cy.contains('Você foi avaliado no edital “Arte Viva”.').should('exist');
        cy.contains('Sua inscrição foi confirmada.').should('exist');

        cy.contains('Notificações lidas').click();
        cy.get('#readNotifications').should('be.visible');
        cy.contains('Proposta enviada com sucesso.').should('exist');
    });
});
