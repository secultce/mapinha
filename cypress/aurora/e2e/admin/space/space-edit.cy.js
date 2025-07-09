describe('Teste de navegação, validação e edição da página de Espaços', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/espacos');
        cy.contains('Editar').first().click();
        cy.url().should('include', '/editar');
    });

    it('Garante que a página de editar espaços funciona', () => {
        cy.get(':nth-child(1) > .accordion-header > .accordion-button')
            .contains('Informações de apresentação')
            .should('be.visible');
        cy.get('#add-activityAreas-btn').click();
        cy.get("button[data-label='Fotografia']").click({ force: true });
        cy.get('#add-tags-btn').click();
        cy.get("button[data-label='Social']").click({ force: true });
        cy.get('[for="short-description"]').type('Descrição curta');
        cy.get('[for="long-description"]').type('Descrição longa');

        cy.get(':nth-child(2) > .accordion-header > .accordion-button')
            .contains('Dados de endereço')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
        cy.get('#cep').type('57600210').blur();
        cy.get('#no_number').click();

        cy.get(':nth-child(3) > .accordion-header > .accordion-button')
            .contains('Capacidade e Acessibilidade')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
        cy.get('.entity-accessibility .form-label')
            .contains('Capacidade de pessoas')
            .should('be.visible');

        cy.get(':nth-child(4) > .accordion-header > .accordion-button')
            .contains('Horário de funcionamento')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
        cy.get('.opening-hours-row .form-label').should('be.visible');

        cy.get(':nth-child(5) > .accordion-header > .accordion-button')
            .contains('Permissões')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
        cy.get('.mb-3 > .form-label')
            .contains('Permitir livre vinculação com')
            .should('be.visible');

        // Redes sociais
        cy.get(':nth-child(6) > .accordion-header > .accordion-button')
            .contains('Redes sociais')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
        cy.get('.container-fluid .form-label')
            .contains('Instagram')
            .should('be.visible');

        cy.get("button[form='space-edit-form']").click();
        cy.url({ timeout: 10000 }).should('include', '/painel/espacos');
        // TODO: Procurar o porque desse erro
        // cy.get('.toast', { timeout: 10000 }).should('be.visible');
    });
});

describe('Formulário de Endereço - Integração ViaCEP e Validação Completa', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/espacos');
        cy.contains('Editar').first().click();
        cy.get(':nth-child(2) > .accordion-header > .accordion-button')
            .contains('Dados de endereço')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
    });

    it('1. Deve preencher os campos com um CEP válido', () => {
        cy.get('#cep').type('01001000').blur();
        cy.wait(1000);
        cy.get('#street').should('have.value', 'Praça da Sé');
        cy.get('#neighborhood').should('have.value', 'Sé');
        cy.get('#address_complement').should('have.value', 'lado ímpar');
        cy.get('#no_number').click();
        cy.get('#state').parent().find('.ts-control')
            .should('contain.text', 'São Paulo');
        cy.get('#city').parent().find('.ts-control')
            .should('contain.text', 'São Paulo');
    });

    it('2. Deve exibir erro e limpar campos se o CEP não for encontrado', () => {
        cy.intercept('GET', '**/viacep.com.br/ws/99999999/json/**', {
            statusCode: 200, body: { erro: true }
        }).as('cepNotFound');
        cy.get('#cep').clear().type('99999999').blur();
        cy.wait('@cepNotFound');
        cy.get('#cep-error-message').should('be.visible')
            .and('contain.text', 'CEP não encontrado');
        cy.get('#street,#neighborhood,#address_complement,#number')
            .each($el => cy.wrap($el).should('have.value', ''));
    });

    it('3. Deve exibir erro se o CEP for inválido (menos de 8 dígitos)', () => {
        cy.get('#cep').clear().type('12345').blur();
        cy.get('#cep-error-message').should('be.visible')
            .and('contain.text', 'digite um CEP válido');
    });

    it('4. Deve exibir erro na API', () => {
        cy.intercept('GET', '**/viacep.com.br/ws/00000000/json/**', {
            statusCode: 500
        }).as('cepApiError');
        cy.get('#cep').clear().type('00000000').blur();
        cy.wait('@cepApiError');
        cy.get('#cep-error-message').should('be.visible')
            .and('contain.text', 'Erro ao buscar CEP');
    });

    it('5. Deve limpar estado/cidade e exibir erro se o retorno do ViaCEP não corresponder às opções do Tom-Select', () => {
        cy.intercept('GET', '**/viacep.com.br/ws/99999998/json/**', {
            statusCode: 200,
            body: {
                logradouro: 'Rua Inexistente Teste',
                complemento: 'Sem complemento',
                bairro: 'Bairro Fictício',
                localidade: 'Cidade Não Mapeada',
                uf: 'ZZ'
            }
        }).as('cepMismatch');
        cy.get('#cep').clear().type('99999998').blur();
        cy.wait('@cepMismatch');
        cy.wait(700);
        cy.get('#state').parent().find('.ts-control')
            .should('have.text', '');
        cy.get('#city').parent().find('.ts-control')
            .should('have.text', '');
        cy.get('#cep-error-message').should('be.visible');
    });

    it('6. Validação do campo "Número" e checkbox "Sem Número"', () => {
        cy.get('#number').clear().type('123').should('have.value', '123');
        cy.get('#no_number').check();
        cy.get('#number').should('be.disabled').and('have.value', '');
        cy.get('#no_number').uncheck();
        cy.get('#number-error-message').should('be.visible')
            .and('contain.text', 'obrigatório');
        cy.get('#number').clear().type('123').blur();
        cy.get('#number-error-message').should('not.be.visible');
    });
});
