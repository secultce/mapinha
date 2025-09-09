describe('Painel de Controle - Criar Agente', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/agentes');
    });

    it('Garante que a página de Agentes existe', () => {
        cy.get('h2').contains('Meus Agentes').should('be.visible');

        cy.contains('Criar').click();

        cy.wait(100);

        cy.get('[data-cy="agent-name"]').should('be.visible');
        cy.get('[data-cy="agent-site"]').should('be.visible');

        cy.get('[data-cy="agent-submit"]').click();
        cy.contains('Este campo é obrigatório.');

        cy.get('[data-cy="agent-name"]').type('Novo Agente Teste');
        cy.get('[data-cy="agent-site"]').type('http://agenteteste.com');
        cy.get('[data-cy="agent-shortBio"]').type('Um agente teste');
        cy.get('[data-cy="agent-submit"]').click();

        cy.wait(100);

        cy.contains('Novo Agente criado').should('be.visible');
        cy.contains('Novo Agente Teste');
    });
});
