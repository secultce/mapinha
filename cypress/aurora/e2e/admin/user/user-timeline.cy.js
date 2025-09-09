describe('Painel de Controle - Página de timeline dos Usuários', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/usuarios/4ed47ceb-8415-4710-a072-3de52843c616/timeline');
    });

    it('Garante que a página de timeline dos usuários existe e que exibe os detalhes corretamente', () => {
        cy.get('h2').contains('Usuário - Talyson Soares - Timeline').should('be.visible');
        cy.get('.d-flex > div > .btn').contains('Voltar').should('be.visible');

        cy.get('tr > :nth-child(1) > a').contains('A entidade foi criada').should('be.visible');
        cy.get('tbody > tr > :nth-child(2)').contains(/\d{2}\/\d{2}\/\d{4}/).should('be.visible');
        cy.get('tbody > tr > :nth-child(3)').contains('unknown').should('be.visible');
        cy.get(':nth-child(5) > .btn').contains('Detalhes').should('be.visible');
        cy.get(':nth-child(1) > :nth-child(5) > .btn').click();
        cy.get('.modal-body > .table > thead > tr > :nth-child(2)').contains('De');
        cy.get('.modal-body > .table > thead > tr > :nth-child(3)').contains('Para');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(2)').contains('N/A');
        cy.get('#modal-timeline-table-body > :nth-child(2) > :nth-child(3)').contains('Talyson');
    });

    it.only('Garante que a timeline de autenticação existe', () => {
        cy.visit('/painel/admin/usuarios/2604e656-57dc-4e1c-9fa8-efdf4a00b203/timeline');

        cy.contains('Autenticação')
            .should('be.visible')
            .click();

        cy.get('[data-cy=auth-timeline] > thead > tr > :nth-child(1)')
            .contains('Ação')
            .should('be.visible');
        cy.get('[data-cy=auth-timeline] > thead > tr > :nth-child(2)')
            .contains('Atividade')
            .should('be.visible');
        cy.get('[data-cy=auth-timeline] > thead > tr > :nth-child(3)')
            .contains('Dispositivo')
            .should('be.visible');

        cy.get('[data-cy=auth-timeline] > tbody > tr > :nth-child(1)')
            .contains('login success')
            .should('be.visible');
        cy.get('[data-cy=auth-timeline] > tbody > tr > :nth-child(2)')
            .contains(/\d{2}\/\d{2}\/\d{2,4} \d{1,2}:\d{2}:\d{2}/);
    })
});
