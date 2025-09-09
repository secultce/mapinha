describe('Painel de Controle - Página de listar Organizações', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/');
        cy.get('.nav > .nav-item').contains('Minhas Organizações').click();
    });

    it('Garante que a página de detalhes de uma organização existe e funciona', () => {
        cy.get('tbody', { timeout: 10000 })
            .contains('a', '30praum')
            .should('be.visible')
            .click();
        cy.get('h2').contains('Organização - 30praum').should('be.visible');
        cy.get('#pills-info-tab > .ms-2').contains('Informações').should('be.visible');
        cy.get('#pills-members-tab > .ms-2').contains('Membros').should('be.visible');
        cy.get('#pills-timeline-tab > .ms-2').contains('Linha do tempo').should('be.visible');
    });

    it('Garante que é possível remover um agente de uma organização', () => {
        cy.get('tbody', { timeout: 10000 })
            .contains('a', 'SECULT CE')
            .should('be.visible')
            .click();

        cy.get('h2').contains('Organização - SECULT CE').should('be.visible');
        cy.get('#pills-members-tab > .ms-2').contains('Membros').should('be.visible').click();
        cy.contains('tr', 'Henrique')
            .within(() => {
                cy.get(':nth-child(1) > .btn-outline-danger').should('contain.text', 'Excluir').click();
            });

        cy.get('#modalRemoveConfirm')
            .should('be.visible')
            .find('.btn-danger')
            .click();

        cy.get('#pills-members-tab > .ms-2').contains('Membros').should('be.visible').click();

        cy.contains('tr', 'Henrique').should('not.exist');
    });
});
