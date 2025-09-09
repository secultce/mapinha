describe('Página de detalhes da oportunidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/oportunidades/083ef392-4c63-4200-a57f-818a1a75211c');
    });

    it('Garante que as principais informações da página existem', () => {
        cy.get('.name__entity-details').contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino').should('be.visible');
        cy.get('.id__entity-details').contains('ID da oportunidade: 083ef392-4c63-4200-a57f-818a1a75211c').should('be.visible');
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
        cy.get('.tab-content .card__entity-details [data-cy="createdBy-name"]').contains('Paulo').should('be.visible');

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
