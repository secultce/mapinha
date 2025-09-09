describe('Teste de acesso à FAQ e interação com os cards', () => {
    beforeEach(() => {
        cy.visit('/faq');
    });

    it('Deve abrir os modais ao clicar nos cards', () => {
        cy.scrollTo('bottom');

        cy.get('.card-faq-item')
            .should('be.visible')
            .each(($card) => {
                const modalTarget = $card;
                cy.wrap($card).click();
                cy.wait(500);
                cy.get(modalTarget)
                    .should('be.visible');
                cy.get('#faqModalAnswer button.btn-close').click();
                cy.get('#faqModalAnswer button.btn-close').should('not.be.visible');
            });
    });
});
