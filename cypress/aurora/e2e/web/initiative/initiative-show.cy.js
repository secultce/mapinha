describe('Página de detalhes da iniciativa', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/iniciativas/8c4c48bd-6e63-4b62-858b-066969c49f66');
    });

    it('Garante que as principais informações da página existem', () => {
        cy.get('.name__entity-details').contains('Arte da Caatinga').should('be.visible');
        cy.get('[data-cy="short-description"]')
            .contains('Arte da Caatinga é uma exposição de arte que reúne artistas de todo o Brasil para celebrar a cultura nordestina.')
            .should('be.visible');
        cy.get('.id__entity-details').contains('ID da iniciativa: 8c4c48bd-6e63-4b62-858b-066969c49f66').should('be.visible');
        cy.get('.entity-info-wrapper button').contains('Compartilhar').should('exist');
        cy.get('.entity-image').should('exist');
    });

    it('Garante que as abas da página estão visíveis', () => {
        cy.get('.nav-pills > li > a').contains('Dados gerais').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Plano de trabalho').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Eventos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Pessoas').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Portfólio').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Selos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Conexões').should('be.visible');
    });

    it('Garante que os conteúdos das abas existem', () => {
        cy.get('.nav-pills [href="#generalData"]').click();
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Apresentação').should('be.visible');
        cy.get('.tab-content [data-cy="change-history-btn"]').contains('histórico de alterações').should('be.visible');
        cy.get('.tab-content [data-cy="report-btn"]').contains('denunciar').should('be.visible');

        cy.get('.nav-pills [href="#people"]').click();
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Pessoas').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="createdBy-name"]').contains('Alessandro').should('be.visible');

        cy.get('.nav-pills [href="#portfolio"]').click();
        cy.get('.nav-pills > li > a').contains('Arquivos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Links').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Videos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Imagens').should('be.visible');

        cy.get('.nav-pills [href="#seals"]').click();
        cy.get('.tab-content .card__entity-details [data-cy="seal-creator"]').should('exist');
        cy.get('.tab-content .card__entity-details [data-cy="seal-validity"]').should('exist');

        cy.get('.nav-pills [href="#connections"]').click();
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Conexões').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="entity-connection"]').contains('Oportunidades').should('be.visible');
    });
});
