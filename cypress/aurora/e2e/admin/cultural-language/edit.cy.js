describe('Painel de Controle - Página de criar Linguagem Cultural', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(10) > .nav-link > .font-title').contains('Linguagem cultural').click();
    });

    it('Garante que possível editar uma linguagem cultural', () => {
        cy.get('tbody > :nth-child(1) > :nth-child(1)').should('contain.text', 'Arquitetura e Design');
        cy.get(':nth-child(1) > :nth-child(3) > .btn-outline-warning').should('contain.text', 'Editar').click();
        cy.get('[data-cy="description"]').should('contain.value', 'Formas e estilos que refletem valores estéticos, religiosos ou funcionais de uma cultura.');

        cy.get('[data-cy="name"]').clear().type('Arquitetura e Design Moderno');
        cy.get('[data-cy="description"]').clear().type('Estilos arquitetônicos contemporâneos que refletem a inovação e a funcionalidade.');

        cy.get('[data-cy="submit"]').click();

        cy.get('tbody > :nth-child(1) > :nth-child(1)').should('contain.text', 'Arquitetura e Design Moderno');
        cy.get('tbody > :nth-child(1) > :nth-child(2)').should('contain.text', 'Estilos arquitetônicos contemporâneos que refletem a inovação e a funcionalidade.');
    });
})