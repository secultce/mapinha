describe('Painel de Controle - Página de listar Usuários', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/admin/usuarios');
    });

    it('Garante que a página de Usuários existe', () => {
        cy.get('h2').contains('Usuários').should('be.visible');

        cy.get('tbody > tr > :nth-child(1)').contains('Talyson').should('be.visible');

        cy.get('tbody > :nth-child(1) a[data-bs-target="#modal-photo"]').click();
        cy.get('.modal.fade.show img').should('be.visible');
    });
});
