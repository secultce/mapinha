describe('Pagina de edição de Oportunidade', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/oportunidades/');

        cy.get('table > tbody').contains('tr', 'Chamada para Oficinas de Teatro de Rua - Encontro de Artes Cênicas Nordestinas').as('row');
        cy.get('@row').should('be.visible');

        cy.get('@row').find('[data-column-id="ações"]').contains('Editar').should('not.be.visible');
        cy.get('@row').find('[data-column-id="ações"]').contains('Ações').click();
        cy.get('@row').find('[data-column-id="ações"]').contains('Editar').click();

        Cypress.on('uncaught:exception', (err) => {
            if (err.message.includes('i.createPopper is not a function')) {
                return false;
            }
        });
    });

    it('Garante que a página de edição existe', () => {
        cy.url().should('to.match', /\/painel\/oportunidades\/(....................................)\/editar$/gm);

        cy.contains('Chamada para Oficinas de Teatro de Rua - Encontro de Artes Cênicas Nordestinas').should('exist');
    });

    describe('Garante aba "Informações gerais"', () => {
        it('Garante que a aba existe', () => {
            cy.get('#pills-general_info-tab:contains(Informações gerais)')
                .should('exist')
                .click();
        });

        it('Garante que os campos de "Informações gerais" existem', () => {
            cy.contains('Modalidade e Objeto do Fomento').should('be.visible');

            cy.contains('Modalidade de Fomento').click();
            cy.focused().should('have.attr', 'name', 'extraFields[promotionModality]');

            cy.contains('Objeto de Fomento').click();
            cy.focused().should('have.attr', 'name', 'extraFields[promotionObject]');

            cy.contains('Data de início').should('be.visible');

            cy.contains('Data e hora').click();
            cy.focused().should('have.attr', 'name', 'extraFields[period][startDate]');

            cy.contains('Quem poderá participar').should('be.visible');

            cy.contains('Previsão de propostas selecionadas').click();
            cy.focused().should('have.attr', 'name', 'extraFields[selectedLimit]');

            cy.contains('Limite de inscrições por agente').click();
            cy.focused().should('have.attr', 'name', 'extraFields[inscriptionsPerAgentLimit]');

            cy.contains('Propostas selecionadas por agente').click();
            cy.focused().should('have.attr', 'name', 'extraFields[selectedPerAgentLimit]');

            cy.contains('Valor do Edital').should('be.visible');

            cy.get('[name="extraFields[grantAmount]"]').should('be.visible');
        });
    });

    describe('Garante aba "Informações públicas"', () => {
        it('Garante que a aba existe', () => {
            cy.get('#pills-public_info-tab:contains(Informações públicas)')
                .should('be.visible')
                .click();
        });

        it('Garante que os campos de "Informações públicas" existem', () => {
            const [ profilePath, coverPath ] = [ 'profile-image.jpg', 'cover-image.jpg' ];

            cy.get('#pills-public_info-tab:contains(Informações públicas)')
                .should('be.visible')
                .click();
            cy.contains('Informações de apresentação').should('be.visible');

            cy.contains('Adicionar imagem de capa')
                .should('be.visible')
                .click();
            cy.get('[id="extraFields[coverImage]"]')
                .should('exist')
                .attachFile(coverPath)
                .then($input => {
                    expect($input[0].files[0].name).to.equal('cover-image.jpg');
                });
            cy.get('#cover-image > img.image-preview').should('exist')
                .and($img => {
                    expect($img[0].naturalWidth).to.be.greaterThan(0);
                });

            cy.get('#profile-image')
                .should('be.visible')
                .click();
            cy.get('#image')
                .should('exist')
                .attachFile(profilePath)
                .then($input => {
                    expect($input[0].files[0].name).to.equal('profile-image.jpg');
                });
            cy.get('#profile-image > img.image-preview').should('exist')
                .and($img => {
                    expect($img[0].naturalWidth).to.be.greaterThan(0);
                });

            cy.contains('Nome da oportunidade').click();
            cy.focused().should('have.attr', 'name', 'name');

            cy.contains('Tipo da oportunidade').click();
            cy.focused().should('have.attr', 'name', 'extraFields[type]');

            cy.contains('Descrição curta').click();
            cy.focused().should('have.attr', 'name', 'extraFields[shortDescription]');

            cy.contains('Descrição longa').click();
            cy.focused().should('have.attr', 'name', 'extraFields[longDescription]');

            cy.contains('Áreas de Atuação').click();
            cy.contains('Artesanato').click();
            cy.get('[data-value="Artesanato"]').should('contain.text', 'Artesanato');

            cy.get(':nth-child(14) > .tags-selector > .dropdown > .dropdown-menu > :nth-child(8) > .dropdown-item')
                .should('contain', 'Outros');

            cy.contains('Anexos e links').should('be.visible');

            cy.contains('Descrição do link').click();
            cy.focused().should('have.attr', 'data-cy', 'link-name');

            cy.contains('Links').next().contains('Link (URL)').click();
            cy.focused().should('have.attr', 'data-cy', 'link-url');

            cy.contains('Descrição do vídeo').click();
            cy.focused().should('have.attr', 'data-cy', 'video-name');

            cy.contains('Videos').next().contains('Link (URL)').click();
            cy.focused().should('have.attr', 'data-cy', 'video-url');
        });
    });

    describe('Ediçao da opotunidade', () => {
        it('Garante que é possível editar uma oportunidade', () => {
            cy.get('#pills-public_info-tab:contains(Informações públicas)')
                .should('be.visible')
                .click();

            cy.contains('Nome da oportunidade').click();
            cy.focused()
                .clear()
                .type('Novo Titulo da Oportunidade');

            cy.contains('Tipo da oportunidade').click();
            cy.focused()
                .select('Festival');

            cy.contains('Descrição curta').click();
            cy.focused()
                .clear()
                .type('Nova descrição curta para a oportunidade.');

            cy.contains('Descrição longa').click();
            cy.focused()
                .clear()
                .type('Nova descrição longa e detalhada para a oportunidade.');

            cy.contains('Áreas de Atuação').click();
            cy.contains('Artesanato').click();
            cy.get('[data-value="Artesanato"]').should('contain.text', 'Artesanato');
            cy.contains('Descrição do link').click();
            cy.focused()
                .clear()
                .type('Novo Link Descrição');
            cy.contains('Links').next().contains('Link (URL)').click();
            cy.focused()
                .clear()
                .type('https://www.novourl.com');

            cy.contains('Salvar e Publicar').click();

            cy.get('.toast.success')
                .should('contain', 'A oportunidade foi atualizada com sucesso');

            cy.visit('/painel/oportunidades/');
            cy.get('tr').contains('Novo Titulo da Oportunidade').should('exist');
        });
    });
});
