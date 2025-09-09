describe('Página de Perfil de Agentes', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/agentes/d737554f-258e-4360-a653-177551f5b1a5');
    });

    it('Garante que a página de perfil do agente existe', () => {
        cy.get('.breadcrumb a:nth-child(1)').contains('Início').should('be.visible');
        cy.get('.breadcrumb a:nth-child(2)').contains('Agentes').should('be.visible');
        cy.get('[href="/agentes/d737554f-258e-4360-a653-177551f5b1a5"]').contains('Paulo').should('be.visible');
        cy.get('.profile-tabs > span').contains('Número identificador: d737554f-258e-4360-a653-177551f5b1a5').should('be.visible');
    });

    it('Garante que as informações do agente estão disponíveis no header', () => {
        cy.get('[href="/agentes/d737554f-258e-4360-a653-177551f5b1a5"]').contains('Paulo').should('be.visible');
        cy.get('.entity-description > p').contains('Especializado em teológia, organiza exposições por todos o Ceará.').should('be.visible');
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            { tab: '#pills-perfil-tab'},
            { tab: '#pills-portfolio-tab'},
            { tab: '#pills-seals-tab'},
            { tab: '#pills-connections-tab'},
            { tab: '#pills-events-tab'}
        ];

        tabs.forEach(({ tab }) => {
            cy.get(tab).click();
            cy.get(tab).should('have.class', 'active');
        });
    });

    it('Garante que as organizações do agente estejam visíveis', () => {
        cy.get('.agent-organizations__content > :nth-child(1)').contains('Anne Elisa Design').should('be.visible');
        cy.get('.agent-organizations__content > :nth-child(2)').contains('Designers do Hack').should('be.visible');
        cy.get('.agent-organizations__content > :nth-child(3)').contains('Coral Madrigal').should('be.visible');
    });

    it('Garante que as informações do campo "Apresentação estão visíveis', () => {
        cy.get('.agent-public > :nth-child(1)').contains('Apresentação').should('be.visible');
        cy.get('.agent-public > :nth-child(2) > .fw-bold').contains('Áreas de Atuação').should('be.visible');
        cy.get(':nth-child(2) > .agent-public-info').contains('DESIGN, ARTES VISUAIS, ARTES GRÁFICAS').should('be.visible');
        cy.get('.agent-public > :nth-child(3) > .fw-bold').contains('Funções na Cultura').should('be.visible');
        cy.get(':nth-child(3) > .agent-public-info').contains('ASSISTENTE DE ARTISTA GRÁFICO').should('be.visible');
        cy.get(':nth-child(4) > .fw-bold').contains('Tags').should('be.visible');
        cy.get(':nth-child(4) > .agent-public-info').contains('Design, ACESSIBILIDADE, DESIGNINCLUSIVO').should('be.visible');
        cy.get('.profile-description > .fw-bold').contains('Descrição').should('be.visible');
        cy.get('.description-agent-profile').contains('Lorem ipsum dolor sit amet').should('be.visible');
        cy.get('.agent-site > .text-decoration-none').contains('Portólio Anne Elisa').should('be.visible');
        cy.get('.agent-profile-contact > :nth-child(2) > :nth-child(2)').contains('(00) 12345-6789').should('be.visible');
    });

    it('Garante que as redes sociais do agente estão visíveis', () => {
        cy.get(':nth-child(1) > :nth-child(1) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(1) > :nth-child(2) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(1) > :nth-child(3) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(1) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(2) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(3) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(1) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(2) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(3) > .social-link > .text-decoration-none').contains('Anne Elisa').should('be.visible');
    });

    it('Garante que as conexões do agente estão presentes', () => {
        cy.get('.agent-initiative > .content-wrapper > :nth-child(1) > .text-truncate').contains('IIV Congresso Estadual de Desenvolvimento').should('be.visible');
        cy.get('.agent-initiative > .content-wrapper > :nth-child(2) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.agent-initiative > .content-wrapper > :nth-child(3) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.agent-spaces > .content-wrapper > .connection-content > .text-truncate').contains('IIV Congresso Estadual de Desenvolvimento').should('be.visible');
        cy.get('.agent-events > .content-wrapper > :nth-child(1) > .text-truncate').contains('IIV Congresso Estadual de Desenvolvimento').should('be.visible');
        cy.get('.agent-events > .content-wrapper > :nth-child(2) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.agent-events > .content-wrapper > :nth-child(3) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
    });

    it('Garante que os eventos do agente estão visíveis', () => {
        cy.get('#pills-events-tab').click();
        cy.get('#pills-events-tab').should('have.class', 'active');

        cy.get('#pills-list-sublist-tab').should('have.class', 'active');
        cy.get('#sublist-list').should('have.class', 'active');
        cy.get('#sublist-list .event-card').contains('Vozes do Interior').should('be.visible');
        cy.get('#sublist-list .event-card').contains('Encontro de Saberes').should('be.visible');

        cy.get('#pills-cards-sublist-tab').click();
        cy.get('#sublist-cards').should('be.visible');
        cy.get('#sublist-cards > .row > .col-md-4').contains('Vozes do Interior').should('be.visible');
        cy.get('#sublist-cards > .row > .col-md-4').contains('Encontro de Saberes').should('be.visible');

        cy.get('#pills-calendar-sublist-tab').should('be.visible');
    });
});
