describe('Página de Home do ambiente web', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/');
    });

    const entities = [
        'Oportunidades',
        'Eventos',
        'Espaços',
        'Agentes',
        'Iniciativas'
    ];

    it('Garante que o navbar existe', () => {
        entities.forEach((entity) => {
            cy.get('a').contains(entity).should('be.visible');
        });
        cy.get('a').contains('Entrar').should('be.visible');
    });

    it('Garante que a seção boas-vindas e criar cadastro existam', () => {
        cy.get('h1').contains('Boas vindas, você chegou no Aurora!').should('be.visible');
        cy.get('a').contains('Cadastro').should('be.visible');
    });

    it('Garante que os componentes intro-card estão presentes e visíveis', () => {
        entities.forEach((entity) => {
            cy.get('.intro-card').contains(entity).should('be.visible');
        });
    });

    it('Garante que a seção "Confira as Oportunidades" esteja visível', () => {
        cy.get('h1').contains('Confira as Oportunidades').should('be.visible');
        cy.get('button').contains('chevron_left').should('be.visible');
        cy.get('button').contains('chevron_right').should('be.visible');
        cy.get('input[placeholder="Buscar"]').should('be.visible');
    });

    it('Garante que a seção "Acontecendo agora" esteja visível', () => {
        cy.get('h1').contains('Acontecendo agora').should('be.visible');
        cy.get('button').contains('chevron_left').should('be.visible');
        cy.get('button').contains('chevron_right').should('be.visible');
    });

    it('Garante que a seção "Contribua" esteja visível', () => {
        cy.get('h1').contains('Contribua').should('be.visible');
        cy.get('a').contains('Comece agora').should('be.visible');
    });

    it('Garante que a seção "Localize-se" esteja visível', () => {
        cy.get('h1').contains('Localize-se').should('be.visible');
        cy.get('iframe').should('be.visible');
    });

    it('Garante que a seção de software livre esteja visível', () => {
        cy.get('h1').contains('Sabia que a Aurora é um software livre?').should('be.visible');
        cy.get('a').contains('Conheça o repositório').should('be.visible');
    });

    it('Garante que seção "Confira as Oportunidades" estão com os dados das fixtures', () => {
        cy.get('.carousel-item').first().contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino');
    });

    it('Garante que seção "Acontecendo agora" estão com os dados das fixtures', () => {
        cy.get('#happening-now').contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino');
    });
});
