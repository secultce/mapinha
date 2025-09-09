describe('Página de Lista de Eventos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/eventos');
    });

    it('Garante que filtrar por nome funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#event-name').type('Festival Sertão Criativo');
        cy.get('#apply-filters').click();

        cy.get('.total-events').contains('1 Eventos Encontrados').should('be.visible');
        cy.get('.event-name').contains('Festival Sertão Criativo').should('be.visible');
    });

    it('Garante que filtrar por período funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#period_start').type('2024-07-01');
        cy.get('#period_end').type('2024-07-31');
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '4 Eventos Encontrados');
    });

    it('Garante que filtrar por linguagem cultural funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#event-cultural-languages').select('Gastronomia');
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '3 Eventos Encontrados');
        cy.get('.event-name').contains('PHP com Rapadura 10 anos').should('be.visible');
    });

    it('Garante que filtrar por classificação etária funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#age-rating').select('Livre');
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '2 Eventos Encontrados');
        cy.get('.event-name').contains('Vozes do Interior').should('be.visible');
    });

    it('Garante que filtrar por selo funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#tags').select('Oficina');
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '1 Eventos Encontrados');
        cy.get('.event-name').contains('Festival da Rapadura').should('be.visible');
    });

    it('Garante que filtrar por estado funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#state').select('Ceará', {force: true});
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '0 Eventos Encontrados');
    });

    it('Garante que filtrar por cidade funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#state-ts-control').click();
        cy.contains('.ts-dropdown .option', 'Goiás').click();
        cy.get('#city-ts-control').click().type('Goiânia', { delay: 200 });
        cy.get('#apply-filters').click();

        cy.get('.total-events').should('contain', '1 Eventos Encontrados');
        cy.get('.event-name').contains('Festival da Rapadura').should('be.visible');
    });
});

