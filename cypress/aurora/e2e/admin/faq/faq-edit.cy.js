describe('Teste de navegação, validação e edição da página de Minhas FAQs', () => {
    beforeEach(() => {
        cy.visit('/');
        cy.contains('Entrar').click();
        cy.url().should('include', '/login');
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.url().should('include', '/');
        cy.contains('Francisco').should('be.visible').click();
        cy.contains('Minhas Oportunidades').should('be.visible').click();
        cy.url().should('include', '/painel/oportunidades');
        cy.scrollTo('bottom');
        cy.contains('FaQ').should('be.visible').click();
        cy.url().should('include', '/painel/faq/');
        cy.contains('Editar').first().click();
        cy.url().should('include', '/editar');

        cy.get('form').invoke('attr', 'novalidate', true);
    });

    it('Garante que é possivel editar uma FaQ', () => {
        cy.get('input[name="question"]').clear().type('A');
        cy.get('textarea[name="answer"]').clear().type('B');

        cy.contains('Salvar').click();

        cy.get('.danger.snackbar').contains('O valor é muito curto.').should('be.visible');

        cy.wait(100);

        cy.get('input[name="question"]')
            .clear()
            .type('Pergunta válida de teste');
        cy.get('textarea[name="answer"]')
            .clear()
            .type('Resposta válida de teste.');

        cy.contains('Salvar').click();
        cy.url().should('include', '/painel/faq/');
        cy.contains('Pergunta válida de teste').should('be.visible');
        cy.contains('Resposta válida de teste.').should('be.visible');

        //Garante que a edição funciona para a tela de faq
        cy.visit('/faq/');
        cy.scrollTo('bottom');
        cy.contains('Pergunta válida de teste').should('be.visible');
    });
});
