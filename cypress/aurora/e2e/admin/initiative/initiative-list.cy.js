describe('Painel de Controle – Página de listar Iniciativas', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080)
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024')
        cy.visit('/painel/iniciativas')
    })

    it('Deve exibir o título e pelo menos uma iniciativa com colunas válidas', () => {
        cy.get('h2')
            .should('contain.text', 'Minhas Iniciativas')

        cy.get('[data-cy=name-AxeZumbi] > a').contains('AxeZumbi').should('be.visible');
        cy.get('[data-cy=d68dc96e-a864-4bb1-ab3d-dec2c2dbae7b] > :nth-child(3)').contains('17/07/2024 15:12:00').should('be.visible');
    });
});
