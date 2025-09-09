describe('Painel de Controle - Página de criar Oportunidades', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/oportunidades');

        Cypress.on('uncaught:exception', (err) => {
            if (err.message.includes('i.createPopper is not a function')) {
                return false;
            }
        });
    });

    it('Garante que é possível criar uma oportunidade e atualizar o dashboard', () => {
        cy.get('.dashboard-card:contains("Registradas nos últimos 7 dias") h2.quantity', { timeout: 10000 })
            .invoke('text')
            .then((quantityBefore) => {
                const totalBefore = parseInt(quantityBefore.trim(), 10);

                createOpportunity();

                cy.visit('/oportunidades');

                cy.get('.dashboard-card:contains("Registradas nos últimos 7 dias") h2.quantity', { timeout: 15000 })
                    .should(($quantityAfter) => {
                        const totalAfter = parseInt($quantityAfter.text().trim(), 10);
                        expect(totalAfter).to.eq(totalBefore + 1);
                    });
            });
    });

    function createOpportunity() {
        cy.contains('button, a', 'Criar oportunidade', { timeout: 10000 })
            .should('be.visible')
            .click();

        cy.url().should('include', '/painel/oportunidades/adicionar');
        cy.get('form').should('be.visible');
        cy.get('#opportunityType').select('Curso');
        cy.get('#opportunityTitle').type('Titulo da oportunidade para o teste');
        cy.get('#opportunityCreatedBy').select('Alessandro');

        cy.get('label.radioEntity:contains(Evento)').click().next().click();
        cy.contains('Festival da Rapadura').should('be.visible').click();

        cy.contains('Áreas de Atuação').click();
        cy.contains('Fotografia').click();
        cy.contains('Áreas de Atuação').click();
        cy.get('#search-activityAreas-items').click().type('Pintura');
        cy.get('.new-tag-item > .dropdown-item').click();
        cy.contains('Pintura').click();

        cy.get('button:contains(Criar em Rascunho)')
            .should('not.be.disabled')
            .click()
            .then(() => {
                cy.location('pathname').should('eq', '/painel/oportunidades/');
            });
    }
});
