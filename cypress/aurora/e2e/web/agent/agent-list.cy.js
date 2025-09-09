describe('Página de Listar de Agentes', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/agentes');
    });

    it('Garante que a página de lista de agentes existe', () => {
        cy.get('.breadcrumb a:nth-child(1)').contains('Início').should('be.visible');
        cy.get('.breadcrumb a:nth-child(2)').contains('Agentes').should('be.visible');
        cy.get('.page-title').contains('Agentes').should('be.visible');
        cy.contains('Agentes Encontrados');
    });

    it('Garante que o dashboard de agentes esteja presente', () => {
        cy.get('.entity-dashboard').should('be.visible');

        const expectedTexts = [
            'Agentes Encontrados',
            'Agentes Culturais',
            'Agentes Inativos',
            'Registrados nos últimos 7 dias'
        ];

        expectedTexts.forEach(text => {
            cy.get('span.text').contains(text).should('be.visible');
        });
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            { tab: '#pills-list-tab'},
            { tab: '#pills-map-tab'},
            { tab: '#pills-indicators-tab'}
        ];

        tabs.forEach(({ tab }) => {
            cy.get(tab).click();
            cy.get(tab).should('have.class', 'active');
        });
    });

    it('Garante que os cards de agentes estão visíveis', () => {
        cy.intercept('GET', '/api/agentes').as('getAgentes');

        cy.get('.agent-card').should('have.length.greaterThan', 0);

        cy.get('.agent-name').contains('Feitozo').scrollIntoView().should('be.visible');

        cy.get('.agent-area .agent-sub-area').contains('DESENVOLVIMENTO').should('be.visible');

        cy.get('.agent-location').contains('Goiânia (GO)').should('be.visible');

        cy.get('.entity-seals').should('be.visible');

        cy.get('.agent-description').should('exist').then(($descriptions) => {
            const found = Cypress._.some($descriptions, (desc) => desc.innerText.includes('Capoeirista apaixonado'));
            expect(found).to.be.true;
        });

        cy.get('.access-profile-container .btn').contains('Acessar').should('be.visible');
    });

    it('Garante que o filtro funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#agent-name').type('Talyson');
        cy.get('#apply-filters').click();
        cy.get('.justify-content-between > .fw-bold').contains('1 Agentes Encontrados').should('be.visible');
        cy.get('.agent-name').contains('Talyson').should('be.visible');
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
