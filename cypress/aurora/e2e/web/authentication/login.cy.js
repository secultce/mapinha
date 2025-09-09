describe('Pagina de Login do ambiente web', () => {
    beforeEach(() => {
        cy.viewport(1920,1080);
        cy.visit('/login')
    });

    it('Garante que a página de login existe', () => {
        cy.contains('Oportunidades');
        cy.contains('Agentes');
        cy.contains('Eventos');
        cy.contains('Espaços');
        cy.contains('Iniciativas');

        cy.get('p')
            .contains('Olá!')
        cy.contains('Entre com a sua conta.')

        cy.get('[data-cy="email"]')
            .should('be.visible');
        cy.get('[data-cy="password"]')
            .should('be.visible');
        cy.contains('Entrar')
            .should('be.visible');
    })

    it('Garante que a mensagem de credenciais inválidas existe', () => {
        cy.get('[data-cy="email"]').type('chiquim@email.com');
        cy.get('[data-cy="password"]').type('12345678');

        cy.contains('Esqueci minha senha')
            .should('be.visible');
        cy.contains('Cadastro')
            .should('be.visible');

        cy.get('[data-cy="submit"]')
            .should('be.visible')
            .click();

        cy.contains('E-mail ou senha incorretos')
            .should('be.visible');
    })


    it('Garante que após o login ser efetuado será redirecionado para a tela home', () => {
        cy.get('[data-cy="email"]').type('paulodetarso@example.com');
        cy.get('[data-cy="password"]').type('Aurora@2024');

        cy.contains('Esqueci minha senha');
        cy.contains('Cadastro');

        cy.get('[data-cy="submit"]').click();

        cy.url().should('include', '/');
    });

    it('Garante que após o login é possivel deslogar', () => {
        cy.get('[data-cy="email"]').type('alessandrofeitoza@example.com');
        cy.get('[data-cy="password"]').type('Aurora@2024');

        cy.contains('Esqueci minha senha');
        cy.contains('Cadastro');

        cy.get('[data-cy="submit"]').click();

        cy.url().should('include', '/');
        cy.contains('Francisco');

        cy.get('#dropdownMenuButton').click();
        cy.get('a').contains('Sair').click();
        cy.get('a').contains('Entrar');

        cy.contains('Alessandro Feitoza').should('not.exist');
    });
})
