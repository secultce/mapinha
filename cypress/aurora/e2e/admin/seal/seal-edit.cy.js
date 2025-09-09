describe('Teste de navegação, validação e edição da página de Selos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');

        Cypress.on('uncaught:exception', (err) => {
            if (err.message.includes('algum-erro-específico')) {
                return false;
            }
        });

        cy.visit('/painel/oportunidades');
        cy.contains('Selos').should('be.visible').click();
        cy.url().should('include', '/painel/selos/');
        cy.contains('Editar').first().click();
        cy.url().should('include', '/editar');

        cy.get('form').invoke('attr', 'novalidate', true);
    });

    it('Verifica e edita os campos do formulário de selos', () => {
        cy.get('input#active')
            .should('exist')
            .should('have.attr', 'type', 'checkbox')
            .should('be.visible');

        cy.get('input#active').check().should('be.checked');
        cy.get('input[name="name"]').clear().type('Selo Teste Atualizado');
        cy.get('textarea[name="description"]')
            .clear()
            .type('Selo que destaca eventos com impacto em comunidades locais.');

        cy.contains('Salvar').click();
        cy.url().should('include', '/painel/selos/');
        cy.contains('Selo Teste Atualizado').parent().contains('Editar').click();
        cy.get('input[name="name"]').should('have.value', 'Selo Teste Atualizado');
        cy.get('textarea[name="description"]').should(
            'have.value',
            'Selo que destaca eventos com impacto em comunidades locais.'
        );

        cy.get('input#active').should('be.checked');
    });

    it('Verifica os botões Salvar e Cancelar', () => {
        cy.contains('Salvar')
            .should('exist')
            .should('have.attr', 'type', 'submit');

        cy.contains('Cancelar')
            .should('exist')
            .should('have.attr', 'href', '/painel/selos/')
            .should('be.visible');

        cy.contains('Cancelar').click();
        cy.url().should('include', '/painel/selos/');
    });
});
