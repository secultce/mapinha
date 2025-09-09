describe('Painel de Controle - Página de detalhes de uma Oportunidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/oportunidades/39994097-41d8-463c-a7eb-7d9a0b40e953');
    });

    it('Garante que a página de detalhes de uma oportunidade existe e funciona', () => {
        cy.get('h2').contains('Chamada para Oficinas de Artesanato - Feira de Cultura Popular').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Dados gerais').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Inscrições').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Fases').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Calendário').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Selos').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Inscrições').click();
        cy.get('#inscriptions table').contains('Feitozo').should('be.visible');
    });
});
