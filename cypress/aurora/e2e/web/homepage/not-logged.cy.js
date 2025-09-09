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
        cy.get('h1').contains('Boas vindas, você chegou na Aurora!').should('be.visible');
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

    it('Garante que a seção "Acontecendo agora" esteja visível e funcionando', () => {
        cy.get('h1').contains('Acontecendo agora').should('be.visible');
        cy.get('button').contains('chevron_left').should('be.visible');
        cy.get('button').contains('chevron_right').should('be.visible');
        cy.get('a[href="/agentes/d691f373-9ddf-4b1f-861a-3539c9df9e64"]').contains('Conferir').should('exist');
        cy.get('a[href="/eventos/9f0e3630-f9e1-42ca-8e6b-b1dcaa006797"]').contains('Conferir').should('exist');
        cy.get('a[href="/iniciativas/8c4c48bd-6e63-4b62-858b-066969c49f66"]').contains('Conferir').should('exist');
        cy.get('a[href="/oportunidades/083ef392-4c63-4200-a57f-818a1a75211c"]').contains('Conferir').should('exist');
        cy.get('a[href="/espacos/b4a49f4d-25ca-40f9-bac2-e72383b689ed"]').contains('Conferir').should('exist');
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

    it('Garante que o botão "Conferir" da seção "Confira as Oportunidades" está redirecionando para a página correta', () => {
        cy.get('.carousel-item [data-cy="show-opportunity-btn"]').first().click();

        cy.get('.name__entity-details').contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino').should('be.visible');
    });

    it('Garante que seção "Acontecendo agora" estão com os dados das fixtures', () => {
        cy.get('#happening-now').contains('Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino');
    });
});
