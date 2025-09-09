describe('Página de listar Oportunidades, botão de inscrição', () => {
    it('Garante que os cards de oportunidades estão visíveis e o botão de inscrição funciona', () => {
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.viewport(1920, 1080);
        cy.visit('/oportunidades');

        cy.get('.opportunity-card').should('have.length.greaterThan', 0);
        cy.get('.opportunity-card-header .fw-bold').should(($titles) => {
            const found = Cypress._.some($titles, (el) =>
                el.innerText.includes('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino')
            );
            expect(found).to.be.true;
        });

        cy.get('.opportunity-card').contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino')
            .parents('.opportunity-card')
            .find('button[data-bs-target="#modalActionConfirm"]')
            .contains('Inscreva-se')
            .click();

        cy.get('.modal.show', { timeout: 6000 }).should('be.visible');
        cy.get('a[data-modal-button="confirm-link"]', { timeout: 6000 })
            .should('be.visible')
            .contains('Confirmar')
            .click();

        cy.url().then((currentUrl) => {
            if (currentUrl.includes('/painel/inscricoes')) {
                cy.url().should('include', '/painel/inscricoes');
                cy.contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino').should('be.visible');
            } else {
                cy.contains('Você já está inscrito nesta oportunidade').should('be.visible');
            }
        });
    });
});
