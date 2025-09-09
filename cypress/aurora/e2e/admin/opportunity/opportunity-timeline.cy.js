describe('Painel de Controle - Página de timeline das Oportunidades', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
    });

    it('Garante que a página de timeline existe e que exibe os detalhes corretamente', () => {
        cy.visit('painel/oportunidades/');

        cy.get('table > tbody').contains('tr', 'Credenciamento de Quadrilhas Juninas - São João do Nordeste').as('row');
        cy.get('@row').should('be.visible');
    
        cy.get('@row').find('[data-column-id="ações"]').contains('Timeline').should('not.be.visible');
        cy.get('@row').find('[data-column-id="ações"]').contains('Ações').click();
        cy.get('@row').find('[data-column-id="ações"]').contains('Timeline').click();

        cy.get('h2').contains('Oportunidade - Credenciamento de Quadrilhas Juninas - São João do Nordeste - Timeline').should('be.visible');
        cy.get('.d-flex > div > .btn').contains('Voltar').should('be.visible');

        cy.get('tbody > :nth-child(1)').as('firstLine');
        cy.get('@firstLine').find('[data-column-id="titulo"]').contains('A entidade foi criada').should('be.visible');
        cy.get('@firstLine').find('[data-column-id="criadoEm"]').contains(/\d{2}\/\d{2}\/\d{4}/).should('be.visible');
        cy.get('@firstLine').find('[data-column-id="dispositivo"]').contains('unknown').should('be.visible');
        cy.get('@firstLine').find('[data-column-id="ações"]').contains('.btn', 'Detalhes').should('be.visible');

        cy.get('@firstLine').contains('Detalhes').click();
        cy.get('.modal-body > .table > thead > tr > :nth-child(2)').contains('De');
        cy.get('.modal-body > .table > thead > tr > :nth-child(3)').contains('Para');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(2)').contains('N/A');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(3)').contains('Credenciamento de Quadrilhas Juninas - São João do Nordeste');
    });
});
