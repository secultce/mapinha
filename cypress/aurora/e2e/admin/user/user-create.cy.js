describe('Painel de Controle – Criação de Usuário', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/usuarios');
    });

    it('Deve criar um novo usuário com CPF válido e estático', () => {
        cy.contains('Criar Usuário').click();
        cy.url().should('include', '/painel/admin/usuarios/adicionar');

        const uniqueEmail = `teste.${Date.now()}@gmail.com`;

        cy.get('input[placeholder="Nome"]').type('teste');
        cy.get('input[placeholder="Sobrenome"]').type('teste');
        cy.get('input[placeholder="CPF"]').type('111.444.777-35');
        cy.get('input[placeholder="Posição"]').type('Assistente');
        cy.get('input[placeholder="Email"]').type(uniqueEmail);
        cy.get('input[placeholder="Senha"]').type('SenhaForte@2025');
        cy.get('input[placeholder="Confirmar"]').type('SenhaForte@2025');

        cy.visit('/painel/admin/usuarios');
    });
});
