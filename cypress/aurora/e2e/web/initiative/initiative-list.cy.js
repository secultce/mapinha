describe('Página de Listar Iniciativas', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/iniciativas');
    });

    it('Garante que a página de lista de iniciativas existe', () => {
        cy.get('a.name-one').contains('Início').should('be.visible');
        cy.get('a.name-one').contains('Iniciativas').should('be.visible');
        cy.get('h2').contains('Iniciativas').should('be.visible');
        cy.get('a').contains('Criar uma iniciativa').should('be.visible');
    });

    it('Garante que a dashboard de iniciativas está presente', () => {
        cy.get('.entity-dashboard').should('be.visible');

        const expectedTexts = [
            'Iniciativas Encontradas',
            'Iniciativas em Andamento',
            'Iniciativas Finalizadas',
            'Registradas nos últimos 7 dias'
        ];

        expectedTexts.forEach(text => {
            cy.contains(text).should('be.visible');
        });
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            { tab: '#pills-list-tab' },
            { tab: '#pills-indicators-tab' }
        ];

        tabs.forEach(({ tab }) => {
            cy.get(tab).click();
            cy.get(tab).should('have.class', 'active');
        });
    });

    it('Garante que o conteúdo da tab Lista está visível', () => {
        cy.get('#pills-list-tab').click();
        cy.get('#pills-list-tab').should('have.class', 'active');
        cy.get('.initiative-card').should('be.visible');

        cy.get('.initiative-card').last().within(() => {
            cy.get('.initiative-name').contains('Vozes do Sertão').should('be.visible');
            cy.get('.initiative-info').contains('Em andamento').should('be.visible');
            cy.get('.initiative-id').contains('ID:').should('be.visible');
            cy.get('strong').contains('Tipo:').should('be.visible');
            cy.get('span').contains('Musical').should('be.visible');
            cy.get('.initiative-date').contains('01/08/2024 até 31/08/2024').should('be.visible');
            cy.get('.initiative-location').contains('Recanto do Cordel').should('be.visible');
            cy.get('.initiative-seals').contains('Selos:').should('be.visible');
            cy.get('.initiative-description').contains('Vozes do Sertão é um festival de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.').should('be.visible');
            cy.get('a').contains('Acessar Iniciativa').should('be.visible');
        });
    });

    it('Garante que a lista de iniciativas está presente e os itens são visíveis', () => {
        cy.get('.initiative-card').should('be.visible');
        cy.get('.initiative-card').first().should('be.visible');
    });

    it('Garante que cada iniciativa tem botão de acessar visível', () => {
        cy.get('.initiative-card').each(($el) => {
            cy.wrap($el).find('a:contains("Acessar"), button:contains("Acessar")').should('be.visible');
        });
    });

    it('Garante que o filtro funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#initiative-name').type('Retalhos do Nordeste');
        cy.get('#apply-filters').click();
        cy.get('.justify-content-between > .fw-bold').contains('1 Iniciativas Encontradas').should('be.visible');
        cy.get(':nth-child(2) > .initiative-card-header > .d-flex > .initiative-name').contains('Retalhos do Nordeste').should('be.visible');
    });

    it('Garante que o botão de limpar filtros funciona', () => {
        cy.get('.justify-content-between > .fw-bold').contains(/^\d+ Iniciativas Encontradas/).should('be.visible');
        cy.get('#open-filter').click();
        cy.get('#initiative-name').type('Retalhos do Nordeste');
        cy.get('#apply-filters').click();
        cy.get('.justify-content-between > .fw-bold').contains('1 Iniciativas Encontradas').should('be.visible');
        cy.get('#open-filter').click();
        cy.get('.btn-outline-primary').click();
        cy.get('.justify-content-between > .fw-bold').contains(/^\d+ Iniciativas Encontradas/).should('be.visible');
    });

    it('Garante que as opções de ordenar funcionam', () => {
        cy.get('#order-select')
            .should('exist')
            .should('be.visible');
        cy.get('#order-select')
            .select('Mais Recente')
            .should('have.value', 'DESC');
        cy.get('#order-select')
            .select('Mais Antigo')
            .should('have.value', 'ASC');
    });
});
