describe('Painel de Controle - Página de listar Idiomas e Culturas', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel');
        cy.get(':nth-child(10) > .nav-link > .font-title').contains('Linguagem cultural').click();
    });

    it('Garante que a tela de listar linguagens culturais existe e funciona ', () => {
        cy.get('h2').should('contain.text', 'Linguagem cultural');

        cy.get('.table-dark > tr > :nth-child(1)').should('contain.text', 'Nome');
        cy.get('.table-dark > tr > :nth-child(2)').should('contain.text', 'Descrição');
        cy.get('.table-dark > tr > :nth-child(3)').should('contain.text', 'Ações');

        cy.contains('tr', 'Tecnologia e Ferramentas')
            .within(() => {
                cy.get(':nth-child(2)').should('contain.text', 'Maneiras específicas de usar tecnologias ou criar ferramentas que têm significado cultural.');
                cy.get(':nth-child(3) > .btn-outline-warning').should('contain.text', 'Editar');
                cy.get(':nth-child(3) > .btn-outline-danger').should('contain.text', 'Excluir');
            });
    });
})