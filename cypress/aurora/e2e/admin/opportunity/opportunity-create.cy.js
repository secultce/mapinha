describe('Painel de Controle - Página de criar Oportunidades', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/oportunidades/adicionar');

        Cypress.on('uncaught:exception', (err) => {
            if (err.message.includes('i.createPopper is not a function')) {
                return false;
            }
        });
    });

    it('Garante que a página de criar oportunidade existe', () => {
        cy.get('h2').contains('Criar oportunidade').should('be.visible');
    });

    it('Garante que os campos para criar uma oportunidade existem', () => {
        cy.get('label:contains("Selecione o tipo da oportunidade")').should('be.visible');
        cy.get('select#opportunityType').should('be.visible');

        cy.get('label:contains("Titulo")').should('be.visible');
        cy.get('input:text#opportunityTitle').should('be.visible');

        cy.get('label:contains("Agente Responsável")').should('be.visible');
        cy.get('select#opportunityCreatedBy').should('be.visible');

        cy.get('label:contains("Áreas de Atuação")').should('be.visible');
        cy.get('#add-activityAreas-btn').should('be.visible');

        cy.get('label:contains("Imagem de capa")').should('be.visible');
        cy.get('input:file#opportunityCoverImage').should('be.visible');

        cy.get('label:contains("Vincule a oportunidade a uma entidade")').should('be.visible');
        cy.get('label:contains("Iniciativa") > input:radio#entity-initiative').should('be.visible');
        cy.get('label:contains("Evento") > input:radio#entity-event').should('be.visible');
        cy.get('label:contains("Espaço") > input:radio#entity-space').should('be.visible');
        cy.get('label:contains("Agente") > input:radio#entity-agent').should('be.visible');

        cy.get('button:contains("Criar e Publicar")').should('be.visible');
        cy.get('button:contains("Criar em Rascunho")').should('be.visible');
        cy.get('a:contains("Cancelar")').should('be.visible');
    });

    it('Garante que a validação dos campos está funcionando', () => {
        cy.get('button:contains(Criar e Publicar)').should('be.disabled');
        cy.get('button:contains(Criar em Rascunho)').should('be.disabled');

        cy.contains('Selecione o tipo da oportunidade').click();
        cy.get('#opportunityType').select('Processo de Seleção');
        cy.contains('O título deve ter entre 2 e 100 caracteres').should('be.visible');
        cy.get('button:contains(Criar e Publicar)').should('be.disabled');

        cy.contains('Titulo')
            .click()
            .type('T');
        cy.contains('O título deve ter entre 2 e 100 caracteres').should('be.visible');
        cy.contains('Titulo')
            .click()
            .type('T');
        cy.contains('Deve associar um agente responsável').should('be.visible');
        cy.get('button:contains(Criar e Publicar)').should('be.disabled');

        cy.contains('Agente Responsável').click();
        cy.get("[name=createdBy]").select('Alessandro');
        cy.contains('Vincule a oportunidade a uma entidade').should('be.visible');
        cy.get('button:contains(Criar e Publicar)').should('be.disabled');

        cy.get('label.radioEntity:contains(Evento)').click()
            .next().click();
        cy.get('.custom-search-input:visible').click()
            .type('Nordeste');
        cy.contains('Nordeste Literário').should('be.visible')
            .click();
        cy.contains('Evento: Nordeste Literário').should('be.visible')
            .parent().click();
        cy.get('li:contains(Nordeste Literário)').should('not.be.visible');
        cy.get('button:contains(Criar e Publicar)').should('not.be.disabled');

        cy.contains('Titulo')
            .click()
            .type('este Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste Teste');
        cy.contains('O título deve ter entre 2 e 100 caracteres').should('be.visible');
        cy.contains('Titulo').type('{backspace}{backspace}');
        cy.get('#error-message').should('not.be.visible');

        cy.get('button:contains(Criar e Publicar)').should('not.be.disabled');
        cy.get('button:contains(Criar em Rascunho)').should('not.be.disabled');

    });

    it('Garante que é possível criar uma oportunidade', () => {
        cy.get('#opportunityType')
            .select('Curso');
        cy.get('#opportunityTitle')
            .type('Titulo da oportunidade para o teste');
        cy.get('#opportunityCreatedBy')
            .select('Alessandro');
        cy.get('label.radioEntity:contains(Evento)').click()
            .next().click();
        cy.contains('Nordeste Literário').should('be.visible')
            .click();

        cy.contains('Áreas de Atuação').click();
        cy.contains('Fotografia').click();
        cy.contains('Áreas de Atuação').click();
        cy.get('#search-activityAreas-items').click().type('Pintura');
        cy.get('.new-tag-item > .dropdown-item').click();
        cy.contains('Pintura').click();

        cy.get('button:contains(Criar em Rascunho)')
            .should('not.be.disabled')
            .click();

        cy.location('pathname')
            .should('eq', '/painel/oportunidades/');
        cy.contains('A oportunidade foi criada com sucesso')
            .should('be.visible');
        cy.contains('Titulo da oportunidade para o teste')
            .should('be.visible');
    });
});
