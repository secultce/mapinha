describe('Página de FAQ', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/faq');
    });

    it('Garante que o título da página de FAQ existe', () => {
        cy.get('h1').contains('Está com dúvidas?').should('be.visible');
    });

    it('Garante que o campo de busca está presente e visível', () => {
        cy.get('input.custom-search-input').should('be.visible');
    });

    it('Garante que as sugestões principais estão visíveis', () => {
        cy.contains('Sugestões').should('be.visible');
        cy.get('h3').contains('Como se cadastrar').should('be.visible');
    });

    it('Garante que as perguntas frequentes estão visíveis', () => {
        cy.contains('Inscrições em Editais e Oportunidades').should('be.visible');
    });
});
