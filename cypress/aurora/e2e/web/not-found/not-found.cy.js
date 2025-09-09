describe('Página de Error 404', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/');
    });

    it('Acessa e exibe o conteúdo da página de Error 404', () => {
        cy.visit('/random', { failOnStatusCode: false });
        cy.request({
            url: '/random',
            failOnStatusCode: false,
        }).then((response) => {
            expect(response.status).to.eq(404);
        });

        cy.get('h1').should('contain', 'Error 404');

        cy.get('h2').should('contain', 'Ops, página não encontrada.');

        cy.get('p').should('contain', 'Essa página não existe mais ou mudou de endereço.');

        cy.get('a.btn-primary').should('contain', 'Voltar para a página inicial');

        cy.get('img').should('be.visible');
    });
});
