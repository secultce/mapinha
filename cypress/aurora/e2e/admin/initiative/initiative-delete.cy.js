describe('Teste de exclusão de Iniciativas', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080)
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024')

        cy.intercept('GET', '/painel/iniciativas/*/remove').as('deleteInitiative')

        cy.visit('/painel/iniciativas')
    })

    it('remove uma iniciativa e atualiza a lista', () => {
        cy.get('[data-cy=table-initiative-list] tbody tr')
            .its('length')
            .then(initialCount => {
                cy.get('[data-cy=remove-a65a9657-c527-4f33-a06e-60c2e219136e]').contains('Excluir').click();

                cy.get('#modalRemoveConfirm').should('be.visible')

                cy.get('[data-modal-button="confirm-link"]').click()

                cy.wait('@deleteInitiative')

                cy.url().should('include', '/painel/iniciativas')

            })
    })
})
