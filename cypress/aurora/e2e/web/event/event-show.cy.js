describe('Página de detalhes do evento', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/eventos/4b92555b-9f6b-4163-8977-c38af0df36b0');
    });

    it('Garante que as principais informações da página existem', () => {
        cy.get('.name__entity-details').contains('Músical o vento da Caatinga').should('be.visible');
        cy.get('.id__entity-details').contains('ID do evento: 4b92555b-9f6b-4163-8977-c38af0df36b0').should('be.visible');
        cy.get('.entity-info-wrapper button').contains('Compartilhar').should('exist');
        cy.get('.entity-image').should('exist');
    });

    it('Garante que as abas da página estão visíveis', () => {
        cy.get('.nav-pills > li > a').contains('Dados gerais').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Selos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Pessoas').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Iniciativas').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Evidências').should('be.visible');
    });

    it('Garante que os conteúdos das abas existem', () => {
        cy.get('.nav-pills [href="#generalData"]').click();
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Apresentação').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="event-cultural-languages"]').should('exist');
        cy.get('.tab-content .card__entity-details [data-cy="event-description"]').should('exist');
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Redes sociais').should('be.visible');
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Outras informações').should('be.visible');
        cy.get('.tab-content .card__entity-details h6').contains('Local').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="event-location"]').contains('Ritmos do Mundo').should('be.visible');
        cy.get('.tab-content [data-cy="change-history-btn"]').contains('histórico de alterações').should('be.visible');
        cy.get('.tab-content [data-cy="report-btn"]').contains('denunciar').should('be.visible');

        cy.get('.nav-pills [href="#seals"]').click();
        cy.get('.tab-content .card__entity-details [data-cy="seal-creator"]').should('exist');
        cy.get('.tab-content .card__entity-details [data-cy="seal-validity"]').should('exist');

        cy.get('.nav-pills [href="#people"]').click();
        cy.get('.tab-content .card__entity-details .card-title__entity-details').contains('Pessoas').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="createdBy-name"]').contains('Henrique').should('be.visible');

        cy.get('.nav-pills [href="#initiatives"]').click();
        cy.get('.tab-content .card__entity-details [data-cy="initiative-name"]').contains('Musicalizando').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-status"]').contains('Em andamento').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-type"]').contains('Oficina Musical').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-period"]').contains('01/08/2024 até 31/08/2024').should('be.visible');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-location"]').should('exist');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-seals"]').should('exist');
        cy.get('.tab-content .card__entity-details [data-cy="initiative-shortDescription"]').should('exist');

        cy.get('.nav-pills [href="#evidences"]').click();
        cy.get('.nav-pills > li > a').contains('Arquivos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Links').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Videos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Imagens').should('be.visible');
    });

    it('Garante que é possível se inscrever no evento uma única vez no evento', () => {
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/eventos/4b92555b-9f6b-4163-8977-c38af0df36b0');
        cy.get('button').contains('Inscreva-se').click();
        cy.get('.toast').should('contain', 'Inscrição realizada');
        cy.get('button').contains('Inscreva-se').click();
        cy.get('.toast').should('contain', 'Você já se inscreveu no evento');
    });
});
