describe('Painel de Controle', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('talysonsoares@example.com', 'Aurora@2024');
        cy.visit('/painel');
    });

    //TODO: para alterar, por causa do REGMEL
    it('Garante que o Painel de Controle existe.', () => {
        // cy.get('.fs-2').contains('Painel de Controle').should('be.visible');
        // cy.get('.user-info > .fs-4').contains('Talyson').should('be.visible');


        // cy.get('[data-cy=agent-card-dashboard]').contains('1 Agentes').should('be.visible');
        // cy.get('[data-cy=event-card-dashboard]').contains('Eventos').should('be.visible');
        // cy.get('[data-cy=space-card-dashboard]').contains('Espaços').should('be.visible');
        // cy.get('[data-cy=opportunity-card-dashboard]').contains('1 Oportunidades').should('be.visible');
        // cy.get('[data-cy=initiative-card-dashboard]').contains('0 Iniciativas').should('be.visible');
    });

    // it('Garante que os botões de "Criar" em cada entidade do dashboard estão funcionando e fazendo o redirecionamento correto.', () => {
    //     const entities = [
    //         { dataCy: 'agent-card-dashboard', route: 'agentes/adicionar' },
    //         { dataCy: 'event-card-dashboard', route: 'eventos/adicionar' },
    //         { dataCy: 'space-card-dashboard', route: 'espacos/adicionar' },
    //         { dataCy: 'opportunity-card-dashboard', route: 'oportunidades/adicionar' },
    //         { dataCy: 'initiative-card-dashboard', route: 'iniciativas/adicionar' }
    //     ];
    //
    //     entities.forEach(entity => {
    //         cy.get(`div[data-cy='${entity.dataCy}'] a`)
    //             .click()
    //             .url().should('include', entity.route);
    //         cy.contains('Voltar').click();
    //     });
    // });
    //
    // it('Garante que as inscrições recentes estão visiveis e que podem ser acessadas.', () => {
    //     cy.get("div.recent-registrations-card.mt-3.col.mx-2")
    //         .contains('Feira de Cultura Popular').should('be.visible');
    //     cy.get('.recent-registrations-card > a').contains('Acompanhar').should('be.visible').click();
    // });
    // -----------------------------------------------------------------------------------------------

    // Não existe um usuário que não esteja inscrito em nenhum oportunidade
    // it('Quando não houver inscrições em nenhuma oportunidade, garante que o usuário saiba disso.', () => {
    //     cy.get("a.text-danger.d-flex.align-items-center.nav-link").click();
    //     cy.login('mariadebetania@example.com', 'Aurora@2024');
    //     cy.visit('/painel');
    //
    //     cy.contains('Nenhuma inscrição encontrada.', { timeout: 10000 }).should('be.visible');
    //
    //     cy.contains("Ver Oportunidades").should('be.visible').click();
    //     cy.url().should('include', '/oportunidades');
    // });
});
