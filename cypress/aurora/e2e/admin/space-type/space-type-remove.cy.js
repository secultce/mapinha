describe('Testar fluxo de Deletar Tipo de Espaço', () => {
    const email = 'alessandrofeitoza@example.com';
    const senha = 'Aurora@2024';

    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login(email, senha);
        cy.visit('/painel');
        cy.get(':nth-child(8) > .nav-link')
            .contains('Tipo de Espaço')
            .click();
        cy.url().should('include', '/admin/tipo-de-espaco');
    });

    it('Cria um tipo de espaço único e depois o exclui sem afetar dados pré-existentes', () => {
        const sufixo   = Cypress._.random(1000, 9999);
        const nomeUnico = `Espaco${sufixo}`; // ex: "Espaco1234"

        cy.contains('Criar').click();

        cy.url().should('include', '/tipo-de-espaco/adicionar');

        cy.get('input[name="name"]')
            .should('be.visible')
            .type(nomeUnico);

        cy.contains('button', 'Criar e Publicar').click();

        cy.visit('/painel/admin/tipo-de-espaco');
        cy.get('tbody').contains(nomeUnico).should('be.visible');

        cy.get('tbody tr')
            .contains(nomeUnico)
            .parent()
            .within(() => {
                cy.contains('Excluir').click();
            });
        cy.get('.btn-danger').click();

        cy.get('.toast-body', { timeout: 10000 })
            .should('contain', 'Tipo de espaço foi deletado');
        cy.get('tbody').contains(nomeUnico).should('not.exist');
    });
});
