describe('Página de Minhas Inscrições', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/inscricoes');
    });

    it('Deve verificar a URL da página de Minhas Inscrições', () => {
        cy.url().should('include', '/painel/inscricoes');
        cy.get('h1').contains('Minhas Inscrições');
        cy.get('input[placeholder="Busque por palavras-chave"]').should('exist');
        cy.get('button').contains('Acompanhar').should('exist');
        cy.get('.resource-card').as("cardInscricao");
        cy.get('@cardInscricao').should('be.visible');
        cy.get('@cardInscricao').contains("Chamada para Oficinas de Artesanato - Feira de Cultura Popular").should('be.visible');
        cy.get('@cardInscricao').contains("Fase de documentos").should("be.visible");
        cy.get('@cardInscricao').contains("Fase de documentos para as oficinas de Artesanato na Feira de Cultura Popular").should("be.visible");
        cy.get('@cardInscricao').contains("15/08/2024 até 20/08/2024").should("be.visible");
    });
});
