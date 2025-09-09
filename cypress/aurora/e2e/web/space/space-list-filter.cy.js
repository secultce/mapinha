describe('Página de Lista de Espaços', () => {
    beforeEach(() => {
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.viewport(1920, 1080);
        cy.visit('/espacos');
    });

    it('Garante que filtrar por nome funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#space-name').type('Dragão do Mar');
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('1 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('Dragão do Mar').should('be.visible');
    });

    it('Garante que filtrar por tipo de espaço funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#spaceType').select('Teatro');
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('0 Espaços Encontrados').should('be.visible');
        cy.contains('Nenhum espaço encontrado').should('be.visible');
    });

    it('Garante que filtrar por acessibilidade funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#accessibilities').select('Elevadores');
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('5 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('Dragão do Mar').should('be.visible');
    });

    it('Garante que filtrar por area de atuação funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#activityAreas').select('Dança');
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('4 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('Casa da Capoeira').should('be.visible');
    });

    it('Garante que filtrar por selo funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#tags').select('Oficina');
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('1 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('Recanto do Cordel').should('be.visible');
    });

    it('Garante que filtrar por estado funciona', () => {
        cy.get('#open-filter').click();
        cy.get('#state').select('Ceará', {force: true});
        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('1 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('SECULT').should('be.visible');
    });

    it('Garante que filtrar por cidade funciona', () => {
        cy.get('#open-filter').click();

        cy.get('#state-ts-control').click();
        cy.contains('.ts-dropdown .option', 'Goiás').click();

        cy.get('#city-ts-control')
            .click()
            .type('Goiânia', { delay: 200 });

        cy.get('#apply-filters').click();

        cy.get('.align-items-end > .fw-bold').contains('1 Espaços Encontrados').should('be.visible');
        cy.get('.space-card__title').contains('Centro Cultural Asa Branca').should('be.visible');
    });
});
