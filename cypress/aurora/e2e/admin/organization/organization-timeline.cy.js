describe('Painel de Controle – Timeline de Organizações', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080)

        cy.login('alessandrofeitoza@example.com', 'Aurora@2024')

        cy.visit('/painel/organizacoes')
        cy.contains('tbody tr', 'Publicado')
            .first()
            .within(() => {
                cy.contains('Timeline').click()
            })

        cy.url().should('match', /\/painel\/organizacoes\/[0-9a-f\-]+\/timeline$/)
    })

    it('Deve abrir o modal de detalhes da timeline', () => {
        cy.get('tbody tr')
            .first()
            .within(() => {
                cy.contains('Detalhes').click()
            })

        cy.get('#modal-timeline').should('be.visible')

        cy.get('#modal-timeline .modal-body table thead').within(() => {
            cy.contains('De').should('be.visible')
            cy.contains('Para').should('be.visible')
        })

        cy.get('#modal-timeline-table-body tr')
            .its('length')
            .should('be.gte', 1)

        cy.get('#modal-timeline')
            .find('.btn-close')
            .click()
    })
})
