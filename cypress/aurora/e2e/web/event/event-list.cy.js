describe('Pagina de listar Eventos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/eventos');
    });

    it('Garante que a página de lista de eventos existe', () => {
        cy.get('a.name-one').contains('Início').should('be.visible');
        cy.get('a.name-one').contains('Eventos').should('be.visible');
        cy.get('h2').contains('Eventos').should('be.visible');
        cy.get('a').contains('Criar um evento').should('be.visible');
    });

    it('Garante que o dashboard de eventos esteja presente', () => {
        cy.get('.entity-dashboard').should('be.visible');

        const expectedTexts = [
            'Eventos Encontrados',
            'Eventos em Andamento',
            'Eventos Finalizados',
            'Registrados nos últimos 7 dias'
        ];

        expectedTexts.forEach(text => {
            cy.get('span.text').contains(text).should('be.visible');
        });
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            { tab: '#pills-list-tab'},
            { tab: '#pills-calendar-tab'},
            { tab: '#pills-map-tab'},
            { tab: '#pills-indicators-tab'}
        ];

        tabs.forEach(({ tab }) => {
            cy.get(tab).click();
            cy.get(tab).should('have.class', 'active');
        });
    });

    it('Garante que o conteúdo da tab Lista está visível', () => {
        cy.get('#pills-list-tab').click();
        cy.get('#pills-list-tab').should('have.class', 'active');

        cy.get('#pills-list > #scroll-container > [data-cy=pills-list-content]').should('be.visible');

        cy.get('[data-cy=pills-list-content]')
            .contains(/^\d+ Eventos Encontrados/)
            .should('be.visible');

        cy.get('.event-card').should('have.length.greaterThan', 0);
        cy.get('.event-name').should(($events) => {
            const found = Cypress._.some($events, (el) => el.innerText.includes('Nordeste Literário'));
            expect(found).to.be.true;
        });
    });

    it('Garante que o filtro funciona', () => {
        cy.get('[id=open-filter]').click();
        cy.scrollTo('top');
        cy.contains('Ver calendário').click();
        cy.get('[data-cy=dropdown-calendar]').should('be.visible');
        cy.get('[data-cy=dropdown-calendar] .air-datepicker-nav--title')
            .click()
            .click();
        cy.get('[data-cy=dropdown-calendar]').contains('2024').click();
        cy.get('[data-cy=dropdown-calendar]').contains('Jul').click();
        cy.get('[data-cy=dropdown-calendar]').contains('9').click();
        cy.get('[data-cy=dropdown-calendar] [data-action=next]').click();
        cy.get('[data-cy=dropdown-calendar] [data-date="2"]').click();
        cy.get('#period').should('have.value', '2024-07-09,2024-08-02');
        cy.get('#period').should('contain.text', '09/07/2024 - 02/08/2024');
        cy.get('#event-name').type('sertão');
        cy.get('#apply-filters').click();
        cy.get('.total-events').contains('1 Eventos Encontrados').should('be.visible');
        cy.get('.event-name').contains('Festival Sertão Criativo').should('be.visible');
    });

    it('Garante que o botão de limpar filtros funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#event-name').type('Festival da Rapadura');
        cy.get('.total-events').contains(/^\d+ Eventos Encontrados/).should('be.visible');
        cy.get('#apply-filters').click();
        cy.get('.total-events').contains('1 Eventos Encontrados').should('be.visible');
        cy.get('#open-filter').click();
        cy.get('.total-events').contains(/^\d+ Eventos Encontrados/).should('be.visible');
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
