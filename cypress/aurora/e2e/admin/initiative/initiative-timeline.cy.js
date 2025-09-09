describe('Painel de Controle – Timeline de Iniciativas', () => {
    const initiativeId   = 'f0774ecd-4860-4b8c-9607-32090dc31f71';
    const initiativeName = 'Vozes do Sertão';

    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/iniciativas/');
    });

    it('deve exibir a página de timeline da iniciativa', () => {
        cy.get(`[data-cy="btn-timeline-${initiativeId}"]`).click();

        cy.contains('h2', `Iniciativa - ${initiativeName} - Timeline`)
            .should('be.visible');

        const firstRow = () => cy.get('tbody > tr').first();

        firstRow()
            .find('td').eq(0)
            .should('contain.text', 'A entidade foi criada');

        firstRow()
            .find('td').eq(1)
            .invoke('text')
            .should('match', /^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/);

        firstRow()
            .find('td').eq(2)
            .should('contain.text', 'unknown');

        firstRow()
            .find('td').eq(4)
            .find('.btn')
            .should('contain.text', 'Detalhes');
    });

    it('deve abrir e exibir o modal de detalhes da timeline', () => {
        cy.get(`[data-cy="btn-timeline-${initiativeId}"]`).click();
          cy.get('tbody > tr').eq(1)
            .find('.btn')
            .contains('Detalhes')
            .click();

        cy.get('#modal-timeline').should('be.visible');

        cy.get('.modal-body .table thead th').eq(1)
            .should('contain.text', 'De');
        cy.get('.modal-body .table thead th').eq(2)
            .should('contain.text', 'Para');

        cy.get('#modal-timeline-table-body tr').eq(1)
            .within(() => {
                cy.get('td').eq(1).should('contain.text', 'Voz');
                cy.get('td').eq(2).should('contain.text', 'Vozes do Sertão');
            });
    });
});
