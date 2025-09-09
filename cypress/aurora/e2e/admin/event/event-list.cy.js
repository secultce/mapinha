describe('Painel de Controle - Página de listar Eventos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/eventos');
    });

    it('Garante que os eventos estejam visíveis', () => {
        cy.get('h2').contains('Meus Eventos').should('be.visible');

        cy.get('tbody > tr > :nth-child(1)').contains('Nordeste Literário').should('be.visible');
        cy.get('tbody > tr > :nth-child(2)').contains('Publicado').should('be.visible');
        cy.get('tbody > tr > :nth-child(3)').contains('14/08/2024 10:00:00').should('be.visible');
    });

    it('Garante que é possível publicar e despublicar um evento', () => {
        cy.contains('td:first-child', 'Cultura em ação')
            .parent('tr')
            .within(() => {
                cy.get('td:last-child').contains('button', 'Publicar').click();
            });

        cy.get('[data-modal-button="confirm-link-toggle-publish"]').click();

        cy.contains('td:first-child', 'Cultura em ação')
            .parent('tr')
            .within(() => {
                cy.get('td:last-child').contains('button', 'Despublicar').should('be.visible');
            });

        cy.contains('td:first-child', 'Festival da Rapadura')
            .parent('tr')
            .within(() => {
                cy.get('td:last-child').contains('button', 'Despublicar').click();
            });

        cy.get('[data-modal-button="confirm-link-toggle-publish"]').click();

        cy.contains('td:first-child', 'Festival da Rapadura')
            .parent('tr')
            .within(() => {
                cy.get('td:last-child').contains('button', 'Publicar').should('be.visible');
            });
    });
})
