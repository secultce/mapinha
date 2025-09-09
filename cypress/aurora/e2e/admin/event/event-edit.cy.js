describe('Teste de navegação, validação e edição da página de Eventos', () => {
    const EMAIL    = 'henriquelopeslima@example.com';
    const PASSWORD = 'Aurora@2024';

    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login(EMAIL, PASSWORD);

        cy.visit('/painel/eventos');
        cy.get('[data-cy^="edit-event-btn-"]')
            .first()
            .should('be.visible')
            .click();
    });

    it('Garante que a página de editar eventos funciona', () => {

        cy.contains('button.accordion-button', 'Informações de apresentação')
            .should('be.visible');

        cy.get('#panelsStayOpen-collapseOne')
            .should('have.class', 'show')
            .within(() => {
                cy.get('[for="name"]').should('contain.text', 'Nome do evento');
                cy.get('[for="subtitle"]').should('contain.text', 'Subtítulo do evento');

                cy.get('#add-culturalLanguages-btn').click();
                cy.get('ul.dropdown-menu.show')
                    .should('be.visible')
                    .find('li')
                    .not('.d-none')
                    .first()
                    .find('button.dropdown-item')
                    .click({ force: true });

                cy.get('#add-tags-btn').click();
                cy.get('ul.dropdown-menu.show')
                    .should('be.visible')
                    .find('li')
                    .not('.d-none')
                    .first()
                    .find('button.dropdown-item')
                    .click({ force: true });

                cy.get('#event-type')
                    .should('be.visible')
                    .select('Presencial');

                cy.get('[for="short-description"]').should('contain.text', 'Descrição curta');
                cy.get('[for="long-description"]').should('contain.text', 'Descrição longa');
                cy.get('label[for="site"]').should('contain.text', 'Site (URL)');
                cy.get('label[for="link-description"]').should('contain.text', 'Descrição do link');
            });

        cy.contains('button.accordion-button', 'Informações sobre o evento')
            .scrollIntoView()
            .click({ force: true });

        cy.get('#panelsStayOpen-collapseTwo')
            .should('have.class', 'show')
            .within(() => {
                [
                    'Classificação etária',
                    'Capacidade máxima de pessoas',
                    'Telefone para informações',
                    'Informações sobre a inscrição'
                ].forEach(txt =>
                    cy.contains('.form-label', txt).should('be.visible')
                );

                cy.get('label[for="librasYes"]').click();
                cy.get('label[for="audioYes"]').click();
            });

        cy.contains('button.accordion-button', 'Data, hora e local do evento')
            .scrollIntoView()
            .click({ force: true });

        cy.get('#panelsStayOpen-collapseThree')
            .should('have.class', 'show')
            .within(() => {
                cy.contains('p.mb-3', 'Adicione data, hora e local da ocorrência')
                    .should('be.visible');
                cy.contains('button', 'Adicionar Ocorrência')
                    .should('be.visible');
            });

        cy.contains('button.accordion-button', 'Redes sociais')
            .scrollIntoView()
            .click({ force: true });

        cy.get('#panelsStayOpen-collapseFour')
            .should('have.class', 'show')
            .within(() => {
                const services = [
                    'Instagram','X','Facebook',
                    'Vimeo','YouTube','LinkedIn',
                    'Spotify','Pinterest','TikTok'
                ];

                services.forEach(service => {
                    cy.contains('label', service).should('be.visible');
                });
            });

        cy.get("button[form='event-edit-form']")
            .contains('Salvar')
            .should('be.visible')
            .click();

        cy.url().should('include', '/painel/eventos');
        cy.get('.toast')
            .should('be.visible')
            .and('contain.text', 'O Evento foi atualizado');
    });
});
