describe('Página de Detalhes do Espaço', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/espacos/b4a49f4d-25ca-40f9-bac2-e72383b689ed');
    });

    it('Garante que a página de detalhes do espaço existe', () => {
        cy.get('.breadcrumb a:nth-child(1)').contains('Início').should('be.visible');
        cy.get('.breadcrumb a:nth-child(2)').contains('Espaços').should('be.visible');
        cy.get('[href="/espacos/b4a49f4d-25ca-40f9-bac2-e72383b689ed"]').contains('Dragão do Mar').should('be.visible');
        cy.get('.profile-tabs > span').contains('Número identificador: b4a49f4d-25ca-40f9-bac2-e72383b689ed').should('be.visible');
    });

    it('Garante que as informações do espaço estão disponíveis no header', () => {
        cy.get('[href="/espacos/b4a49f4d-25ca-40f9-bac2-e72383b689ed"]').contains('Dragão do Mar').should('be.visible');
        cy.get('.entity-description > p').contains('Aqui se encontra alguma descrição magnifica sobre esse belíssimo espaço, cuidado, altas probabilidades de se emocionar').should('be.visible');
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            { tab: '#pills-general_data-tab'},
            { tab: '#pills-seals-tab'},
            { tab: '#pills-connections-tab'},
            { tab: '#pills-events-tab'}
        ];

        tabs.forEach(({ tab }) => {
            cy.get(tab).click();
            cy.get(tab).should('have.class', 'active');
        });
    });

    it('Garante que o dono do espaço esteja visível', () => {
        cy.get('.entity-owner > .px-4').contains('Este espaço pertence a Alessandro').should('be.visible');
    });

    it('Garante que as informações do campo "Apresentação estão visíveis', () => {
        cy.get('.space-public > :nth-child(1)').contains('Apresentação').should('be.visible');
        cy.get('.space-public > :nth-child(2) > .fw-bold').contains('Áreas de Atuação').should('be.visible');
        cy.get(':nth-child(2) > .space-public-info').contains('DESIGN, ARTES VISUAIS, ARTES GRÁFICAS').should('be.visible');
        cy.get('.space-public > :nth-child(3) > .fw-bold').contains('Funções na Cultura').should('be.visible');
        cy.get(':nth-child(3) > .space-public-info').contains('ASSISTENTE DE ARTISTA GRÁFICO').should('be.visible');
        cy.get(':nth-child(4) > .fw-bold').contains('Tags').should('be.visible');
        cy.get(':nth-child(4) > .space-public-info').contains('Design, ACESSIBILIDADE, DESIGNINCLUSIVO').should('be.visible');
        cy.get('.profile-description > .fw-bold').contains('Descrição').should('be.visible');
        cy.get('.description-agent-profile').contains('Lorem ipsum dolor sit amet').should('be.visible');
        cy.get('.gap-3 > :nth-child(1) > .text-decoration-none').contains('Portólio Anne Elisa').should('be.visible');
        cy.get('.space-public > .gap-3 > :nth-child(2) > :nth-child(2)').contains('(00) 12345-6789').should('be.visible');
    });

    it('Garante que as redes sociais do espaço estão visíveis', () => {
        cy.get(':nth-child(1) > :nth-child(1) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(1) > :nth-child(2) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(1) > :nth-child(3) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(1) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(2) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(2) > :nth-child(3) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(1) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(2) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
        cy.get(':nth-child(3) > :nth-child(3) > .social-link > .text-decoration-none').contains('Dragão do Mar').should('be.visible');
    });

    it('Garante que as conexões do espaço estão presentes', () => {
        cy.get('.space-initiative > .content-wrapper > :nth-child(1) > .text-truncate').contains('IIV Congresso Estadual de Desenvolvimento').should('be.visible');
        cy.get('.space-initiative > .content-wrapper > :nth-child(2) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.space-initiative > .content-wrapper > :nth-child(3) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.space-events > .content-wrapper > :nth-child(1) > .text-truncate').contains('IIV Congresso Estadual de Desenvolvimento').should('be.visible');
        cy.get('.space-events > .content-wrapper > :nth-child(2) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
        cy.get('.space-events > .content-wrapper > :nth-child(3) > .text-truncate').contains('III Festival de Coros de Brasília').should('be.visible');
    });
});
