describe('Página de Lista de Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/espacos');
    });

    it('Garante que a página de lista de espaços existe', () => {
        cy.get('a.name-one').contains('Início').should('be.visible');
        cy.get('a.name-one').contains('Espaços').should('be.visible');
        cy.get('h2').contains('Espaços').should('be.visible');
    });

    it('Garante que as opções de visualização estão presentes', () => {
        cy.contains('a', 'Lista').should('be.visible');
        cy.contains('a', 'Mapa').should('be.visible');
    });

    it('Garante que as tabs estão funcionando', () => {
        const tabs = [
            '#pills-list-tab',
            '#pills-map-tab',
            '#pills-indicators-tab'
        ];

        tabs.forEach((tab) => {
            cy.get(tab).click().should('have.class', 'active');
        });
    });

    it('Garante que os cards de espaços estão visíveis', () => {
        cy.get('.space-card').should('have.length.greaterThan', 0);
        cy.get('.space-card__title').should(($titles) => {
            const found = Cypress._.some($titles, (el) => el.innerText.includes('Dragão do Mar'));
            expect(found).to.be.true;
        });

        cy.get('.space-card').first().within(() => {
            cy.get('.space-card__title').should('be.visible');
            cy.get('.space-card__type').should('be.visible');
            cy.get('.btn').contains('Acessar espaço').should('be.visible');
        });
    });

    it('Garante que as opções de ordenação funcionam', () => {
        cy.get('#order-select')
            .should('exist')
            .should('be.visible');

        cy.get('#order-select').select('Mais Recente').should('have.value', 'DESC');
        cy.get('#order-select').select('Mais Antigo').should('have.value', 'ASC');
    });
});
