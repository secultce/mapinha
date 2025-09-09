describe('Exclusão de Evento', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080)
        cy.login('henriquelopeslima@example.com', 'Aurora@2024')
        cy.visit('/painel/eventos')
    })

    it('deve excluir o primeiro evento da lista', () => {
        cy.get('[data-cy=table-event-list] tbody tr').first().within(() => {
            cy.contains('button', 'Excluir').click()
        })

        cy.get('#modalRemoveConfirm').should('be.visible')

        cy.get('[data-modal-button="confirm-link"]').click()

        cy.get('.toast', { timeout: 10000 })
            .should('be.visible')
            .and('contain.text', 'O Evento foi excluído')

        cy.url().should('match', /\/painel\/eventos\/?$/)

        cy.get('[data-cy=table-event-list] tbody tr').first()
            .find('td').eq(1)
            .should('contain.text', 'Na lixeira')
    })
})
