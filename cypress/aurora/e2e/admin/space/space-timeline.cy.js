describe('Painel de Controle - Página de timeline dos Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/espacos/608756eb-4830-49f2-ae14-1160ca5252f4/timeline');
    });

    it('Garante que a página de timeline dos espaços existe e que exibe os detalhes corretamente', () => {
        cy.get('h2')
            .should('contain', 'Espaços - Galeria Caatinga - Timeline')
            .should('be.visible');

        cy.get('.btn')
            .contains('Voltar')
            .should('be.visible');

        cy.get('tr > :nth-child(1) > a').contains('A entidade foi criada').should('be.visible');
        cy.get('tbody > tr > :nth-child(2)').contains(/\d{2}\/\d{2}\/\d{4}/).should('be.visible');
        cy.get('tbody > tr > :nth-child(3)').contains('unknown').should('be.visible');
        cy.get(':nth-child(5) > .btn').contains('Detalhes').should('be.visible')

        cy.get('tbody > tr')
            .first()
            .within(() => {
                cy.get('td').eq(0).should('contain', 'A entidade foi criada').should('be.visible');
                cy.get('td').eq(1).invoke('text').should('match', /\d{2}\/\d{2}\/\d{4}/);
                cy.get('td').eq(2).invoke('text').then((text) => {
                });
                cy.get('td').eq(4).find('.btn').should('contain', 'Detalhes').should('be.visible');
            });

        cy.get('tbody > tr')
            .first()
            .within(() => {
                cy.get('td').eq(4).find('.btn').contains('Detalhes').click();
            });

        cy.get('.modal-body:visible').within(() => {
            cy.get('thead > tr > th').eq(1).should('contain', 'De').should('be.visible');
            cy.get('thead > tr > th').eq(2).should('contain', 'Para').should('be.visible');
            cy.get('tbody > tr').first().within(() => {
                cy.get('td').eq(1).should('contain', 'N/A').should('be.visible');
            });
        });
    });
});
