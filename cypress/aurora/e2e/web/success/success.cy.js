describe('Teste de Cadastro com Sucesso', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
    });

    it('Deve acessar a Home, clicar em Entrar, ir para Cadastro e realizar o cadastro com sucesso', () => {
        cy.visit('/');

        cy.get('a').contains('Entrar').click();

        cy.url().should('include', '/login');

        cy.get('a').contains('Cadastro').click();

        cy.url().should('include', '/cadastro');

        const email = `user${Date.now()}@example.com`;
        cy.get("[name='first_name']").type('João');
        cy.get("[name='last_name']").type('Silva');
        cy.get("[name='birth_date']").type('1990-01-01');
        cy.get("[name='cpf']").type('881.434.860-01');
        cy.get("[name='phone']").type('(11) 99999-9999');
        cy.get("[name='email']").type(email);
        cy.get("[name='password']").type('Senha@12345678');
        cy.get("[name='confirm_password']").type('Senha@12345678');
        cy.get('.btn').contains('Continuar').click();

        cy.get('#acceptPolicies').click();

        cy.get('#submitPolicies').click();

        cy.wait(100);

        cy.contains('Seu cadastro foi criado com sucesso!').should('be.visible');
    });
});
