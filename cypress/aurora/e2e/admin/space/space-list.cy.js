describe('Painel de Controle - Página de listar Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/espacos');
    });

    it('Garante que a página de listar Espaços existe e funciona', () => {
        cy.get('h2').contains('Meus Espaços').should('be.visible');

        cy.contains('Dragão do Mar').should('be.visible');
        cy.contains('Rascunho').should('be.visible');
    });

    it('Garante que é possível publicar e despublicar um Espaço', () => {
        cy.get('table').contains('tr', 'Dragão do Mar').within(() => {
           cy.contains('Rascunho').should('be.visible');
           cy.contains('button', 'Publicar').click();
        });

        cy.get('#modalTogglePublishConfirm').contains('Confirmar').click();

        cy.get('table').contains('tr', 'Dragão do Mar').within(() => {
            cy.contains('Publicado').should('be.visible');
            cy.contains('button', 'Despublicar').click();
        });

        cy.get('#modalTogglePublishConfirm .btn-danger').contains('Confirmar').click();
        cy.get('table').contains('tr', 'Dragão do Mar').within(() => {
            cy.contains('Rascunho').should('be.visible');
        });
    });
});
