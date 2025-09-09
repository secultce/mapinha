describe('Teste para Gerenciar as Perguntas Frequentes (FaQ)', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/faq/adicionar');
    });

    it('Cadastrar uma nova pergunta frequente', () => {
        cy.get('#question').should('exist');
        cy.get('#answer').should('exist');

        cy.get('#question').type('Pergunta 01');
        cy.get('#answer').type('Resposta 01');
        cy.get('[type="submit"]').click();

        cy.contains('Criar').click();

        //testar se a validação funciona
        cy.get('#question').should('exist');
        cy.get('#answer').should('exist');

        cy.get('#question').type('P');
        cy.get('#answer').type('Resposta 01');
        cy.get('[type="submit"]').click();
        cy.get('.danger.snackbar').contains('O valor é muito curto. Deveria de ter 2 caracteres ou mais.').should('be.visible');
    });
});
