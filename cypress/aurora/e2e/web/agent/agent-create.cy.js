describe('Criar um agente (preenchendo campos das imagens 2 e 3) e atualizar o dashboard', () => {
    beforeEach(() => {
        cy.visit('/login');
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.url().should('include', '/');
    });

    it('Deve preencher as infos das imagens 2 e 3 e salvar o agente', () => {
        cy.visit('/agentes');
        cy.url().should('include', '/agentes');

        cy.get('.dashboard-card:contains("Registrados nos últimos 7 dias") h2.quantity', { timeout: 10000 })
            .invoke('text')
            .then((quantityBefore) => {
                const totalBefore = parseInt(quantityBefore.trim(), 10);

                createAgent();

                cy.visit('/agentes');
                cy.get('.dashboard-card:contains("Registrados nos últimos 7 dias") h2.quantity', { timeout: 10000 })
                    .invoke('text')
                    .should((quantityAfter) => {
                        const totalAfter = parseInt(quantityAfter.trim(), 10);
                        expect(totalAfter).to.eq(totalBefore + 1);
                    });
            });
    });

    function createAgent() {
        cy.contains('button, a', 'Criar um agente', { timeout: 10000 })
            .should('be.visible')
            .click();

        cy.url().should('include', '/painel/agentes/adicionar');

        cy.get('form[id="agent-create-form"]', { timeout: 10000 }).should('be.visible');
        cy.get('input[data-cy="agent-name"]').should('be.visible').type('teste');
        cy.get('textarea[data-cy="agent-shortBio"]').should('be.visible').type('teste');
        cy.get('input[data-cy="agent-site"]').should('be.visible').type('teste');

        cy.get('input[name="social_name"]').type('teste');
        cy.get('input[name="full_name"]').type('teste teste');
        cy.get('input[name="cpf"]').type('541.889.690-32');
        cy.get('input[name="mei"]').type('00.000.000/0001-00');
        cy.get('input[name="public_email"]').type('sarajcamilo1014@gmail.com');
        cy.get('input[name="private_phone1"]').type('(11) 99999-8888');
        cy.get('input[name="private_phone2"]').type('(11) 98888-7777');

        cy.get('input[name="postal_code"]').type('61658710');
        cy.get('input[name="street"]').type('Rua');
        cy.get('input[name="number"]').type('341');
        cy.get('input[name="neighborhood"]').type('Bairro Teste');
        cy.get('input[name="complement_or_reference_point"]').type('Teste');

        cy.get('input[id="public-location"]').check();

        cy.contains('button', 'Salvar', { timeout: 10000 })
            .should('be.visible')
            .click();
    }
});
