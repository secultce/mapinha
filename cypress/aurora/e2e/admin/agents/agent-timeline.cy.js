describe('Painel de Controle - Página de timeline de Agentes', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/agentes/');
    });

    it('Garante que a página de timeline do agente existe e que exibe os detalhes corretamente', () => {
        cy.contains('Timeline').click({force: true});
        cy.get('h2').contains('Agente - Henrique - Timeline').should('be.visible');
        cy.get('.d-flex > div > .btn').contains('Voltar').should('be.visible');

        cy.get('tr > :nth-child(1) > a').contains('A entidade foi criada').should('be.visible');
        cy.get('tbody > tr > :nth-child(2)').contains(/\d{2}\/\d{2}\/\d{4}/).should('be.visible');
        cy.get('tbody > tr > :nth-child(3)').contains('unknown').should('be.visible');
        cy.get(':nth-child(5) > .btn').contains('Detalhes').should('be.visible')

        cy.get(':nth-child(1) > :nth-child(5) > .btn').click();
        cy.get('.modal-body > .table > thead > tr > :nth-child(2)').contains('De');
        cy.get('.modal-body > .table > thead > tr > :nth-child(3)').contains('Para');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(2)').contains('N/A');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(3)').contains('Henrique');
    });
})
