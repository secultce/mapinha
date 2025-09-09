describe('Página de Eventos Inscritos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/meus-eventos');
    });

    it('Deve verificar a página de Minhas Inscrições e a tab para eventos acontecendo agora', () => {
        cy.get('h1.title-inscriptions').contains('Minhas Inscrições');
        cy.get('p.subtitle-inscriptions').contains('Gerenciar as inscrições de eventos');
        cy.get('#pills-happening-now-tab').contains('Acontecendo agora').should('have.class', 'active');
        cy.get('#pills-past-tab').contains('Passados').should('not.have.class', 'active');
        cy.get('h3.resources-title').contains('Sem eventos');
    });

    it('Deve verificar a página de Minhas Inscrições e a tab para eventos passados', () => {
        cy.get('h1.title-inscriptions').contains('Minhas Inscrições');
        cy.get('p.subtitle-inscriptions').contains('Gerenciar as inscrições de eventos');
        cy.get('#pills-past-tab').click();
        cy.get('#pills-happening-now-tab').contains('Acontecendo agora').should('not.have.class', 'active');
        cy.get('#pills-past-tab').contains('Passados').should('have.class', 'active');
        cy.get('h3.resources-title').contains('Eventos encontrados');
    });

    it('Deve verificar o card para os eventos inscritos', () => {
        cy.get('h1.title-inscriptions').contains('Minhas Inscrições');
        cy.get('p.subtitle-inscriptions').contains('Gerenciar as inscrições de eventos');
        cy.get('#pills-past-tab').click();
        cy.get('[data-cy="pills-list-content"] > div.resource-card.mt-4').first().contains('Cores do Sertão');
        cy.get('[data-cy="pills-list-content"] > div.resource-card.mt-4').first()
            .find('a.btn-primary')
            .should('have.attr', 'href')
            .and('include', '/eventos/');
        cy.get('[data-cy="pills-list-content"] > div.resource-card.mt-4 > div.text-end > a.btn').first().click();
        cy.url().should('include', '/eventos/');
    });
});
