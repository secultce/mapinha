describe('Criar um espaço e atualizar o dashboard', () => {
    beforeEach(() => {
        cy.visit('/login');
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.url().should('include', '/');
    });

    it('Deve criar um espaço e verificar se o número de espaços aumentou', () => {
        cy.visit('/espacos');

        cy.get('.dashboard-card:contains("Registrados nos últimos 7 dias") h2.quantity')
            .invoke('text')
            .then((quantityBefore) => {
                const totalBefore = parseInt(quantityBefore.trim(), 10);

                createNewSpace();

                cy.visit('/espacos');

                cy.get('.dashboard-card:contains("Registrados nos últimos 7 dias") h2.quantity')
                    .invoke('text')
                    .should((quantityAfter) => {
                        const totalAfter = parseInt(quantityAfter.trim(), 10);
                        expect(totalAfter).to.eq(totalBefore + 1);
                    });
            });
    });

    function createNewSpace() {
        cy.contains('button, a', 'Criar espaço').click();
        cy.url().should('include', '/painel/espacos/adicionar');

        cy.get('input#name').type('Espaço de Teste Cypress');
        cy.get('[data-cy="createdBy"]').select('Alessandro');
        cy.get('[data-cy="shortDescription"]').type('Descrição de teste do espaço');
        cy.get('button[data-cy="submit"]').click();
    }
});
