describe('Painel de Controle - Página de detalhes de uma Oportunidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/oportunidades/378cc989-c2ae-4118-9f19-54bacb8718c4');
    });

    it('Criação de fase e verificação na tabela', () => {
        cy.get('h2').contains('Inscrição para o Festival de Danças Folclóricas - Encontro Nordestino').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Dados gerais').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Inscrições').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Fases').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Calendário').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Selos').should('be.visible');

        cy.get('.nav-pills > li > a').contains('Fases').click();
        cy.get('table').should('be.visible');
        cy.get('table').contains('Fase de submissão').should('be.visible');
        cy.get('table').contains('Fase de documentação').should('be.visible');
        cy.get('table').contains('10/07/2024').should('be.visible');
        cy.get('table').contains('12/07/2024').should('be.visible');

        cy.contains('Nova Fase').click();
        cy.get('#createPhaseModal').invoke('show');
        cy.get('input#phaseName').type('Fase de Teste');
        cy.wait(250);
        cy.get('#phaseDescription').type('Descrição da fase de teste');
        cy.get('input#startDate').type('2025-01-25');
        cy.get('input#endDate').type('2025-02-10');
        cy.get('select#status').select('1');
        cy.get('button[type="submit"]').click();

        cy.contains('Fase criada com sucesso').should('be.visible');
        cy.get('.nav-pills > li > a').contains('Fases').click();
        cy.get('table').should('be.visible');

        cy.get('table').contains('Fase de Teste').should('be.visible');
        cy.get('table').contains('25/01/2025').should('be.visible');
        cy.get('table').contains('10/02/2025').should('be.visible');
    });
});
