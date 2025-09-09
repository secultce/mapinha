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

        cy.get('[for="name"]')
            .contains('Nome do espaço')
            .should('be.visible');

        cy.get('#add-activityAreas-btn')
            .should('be.visible')
            .click();

        cy.get("button[data-label='Fotografia']")
            .should('be.visible')
            .click();

        cy.get('#add-tags-btn')
            .should('be.visible')
            .click();

        cy.get("button[data-label='Social']")
            .should('be.visible')
            .click();

        cy.get('[for="short-description"]')
            .contains('Descrição curta')
            .should('be.visible')
            .type('Descrição curta');

        cy.get('[for="long-description"]')
            .contains('Descrição longa')
            .should('be.visible')
            .type('Descrição longa');

        cy.get(':nth-child(9) > :nth-child(1) > label')
            .contains('Site')
            .should('be.visible');

        cy.get(':nth-child(9) > :nth-child(2) > label')
            .contains('Descrição do link')
            .should('be.visible');

        cy.get('.entity-introduction-data > .mt-1 > :nth-child(1) > label')
            .contains('Email público')
            .should('be.visible');

        cy.get('.entity-introduction-data > .mt-1 > :nth-child(2) > label')
            .contains('Telefone Público')
            .should('be.visible');

        cy.get(':nth-child(2) > .accordion-header > .accordion-button')
            .contains('Dados de endereço')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');

        cy.intercept('GET', 'https://viacep.com.br/ws/57600210/json/', {
            statusCode: 200,
            body: {
                cep: "57600-210",
                logradouro: "Rua Frei Domingos",
                complemento: "",
                unidade: "",
                bairro: "Centro",
                localidade: "Palmeira dos Índios",
                uf: "AL",
                estado: "Alagoas",
                regiao: "Nordeste",
                ibge: "2706307",
                gia: "",
                ddd: "82",
                siafi: "2825"
            }
        }).as('getCep');

        cy.get('#cep').type('57600210');
        cy.get('#cep').blur();

        cy.wait('@getCep');

        cy.get('#street').should('have.value', 'Rua Frei Domingos');
        cy.get('#neighborhood').should('have.value', 'Centro');
        cy.get('#address_complement').should('have.value', '');
        cy.get('#number').should('have.value', '');
        cy.get("#no_number").click()
        cy.get('#state').parent().find('.ts-control').should('contain.text', 'Alagoas');
        cy.get('#city').parent().find('.ts-control').should('contain.text', 'Palmeira dos Índios');

        cy.get(':nth-child(3) > .accordion-header > .accordion-button')
            .contains('Capacidade e Acessibilidade')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');

        cy.get('.entity-accessibility > :nth-child(1) > .col-md-4 > .form-label')
            .contains('Capacidade de pessoas')
            .should('be.visible');

        cy.get(':nth-child(4) > .accordion-header > .accordion-button')
            .contains('Horário de funcionamento')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');

        cy.get('.opening-hours-row .col-md-4 .form-label')
            .should('be.visible')
            .and('contain', 'Dias da semana');

        cy.get('.opening-hours-row .col-md-3:nth-child(2) .form-label')
            .should('be.visible')
            .and('contain', 'Abre às');

        cy.get('.opening-hours-row .col-md-3:nth-child(3) .form-label')
            .should('be.visible')
            .and('contain', 'Fecha às');

        cy.get(':nth-child(5) > .accordion-header > .accordion-button')
            .contains('Permissões')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');

        cy.get('.mb-3 > .form-label')
            .contains('Permitir livre vinculação com')
            .should('be.visible');

        cy.get('.mb-3 > :nth-child(2) > .form-check-label')
            .contains('Pessoas')
            .should('be.visible');

        cy.get(':nth-child(3) > .form-check-label')
            .contains('Organizações')
            .should('be.visible');

        cy.get(':nth-child(4) > .form-check-label')
            .contains('Eventos')
            .should('be.visible');

        cy.get(':nth-child(5) > .form-check-label')
            .contains('Espaços')
            .should('be.visible');

        cy.get(':nth-child(6) > .accordion-header > .accordion-button')
            .contains('Redes sociais')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');

        cy.get('.container-fluid > :nth-child(2) > :nth-child(1) > .form-label')
            .contains('Instagram')
            .should('be.visible');

        cy.get(':nth-child(4) > :nth-child(3) > .form-label')
            .contains('TikTok')
            .should('be.visible');

        cy.get("button[form='space-edit-form']").click();

        cy.url().should('include', '/painel/espacos');
        cy.get('.toast')
            .should('be.visible')
            .and('contain', 'O Espaço foi atualizado');
    });

    it ('Garante que o filtro de tags funciona corretamente', () => {
        cy.get('#add-activityAreas-btn').click();
        cy.get('#search-activityAreas-items').click().type('Cinema');
        cy.get('#span-message-activityAreas > .tags-selector-error').should('contain.text', 'Já existe uma tag com esse nome');
        cy.get('#search-activityAreas-items').clear().type('        ');
        cy.get(':nth-child(3) > .tags-selector > .dropdown > .dropdown-menu > .new-tag-item > .dropdown-item > span').should('not.be.visible');
    });
});
describe('Formulário de Endereço - Integração ViaCEP e Validação Completa', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/espacos');
        cy.contains('Editar').first().click();
        cy.url().should('include', '/editar');

        cy.get(':nth-child(2) > .accordion-header > .accordion-button')
            .contains('Dados de endereço')
            .should('be.visible')
            .click()
            .should('have.attr', 'aria-expanded', 'true');
    });

    it('1. Deve preencher os campos de endereço, estado e cidade com um CEP válido (ViaCEP mocado)', () => {
        cy.intercept('GET', 'https://viacep.com.br/ws/01001000/json/', {
            statusCode: 200,
            body: {
                cep: "01001-000",
                logradouro: "Praça da Sé",
                complemento: "lado ímpar",
                unidade: "",
                bairro: "Sé",
                localidade: "São Paulo",
                uf: "SP",
                estado: "São Paulo",
                regiao: "Sudeste",
                ibge: "3550308",
                gia: "1004",
                ddd: "11",
                siafi: "7107"
            }
        }).as('getCepMocked');

        cy.get('#cep').type('01001000');
        cy.get('#cep').blur();

        cy.wait('@getCepMocked');

        cy.get('#street').should('have.value', 'Praça da Sé');
        cy.get('#neighborhood').should('have.value', 'Sé');
        cy.get('#address_complement').should('have.value', 'lado ímpar');
        cy.get('#number').should('have.value', '');
        cy.get("#no_number").click()
        cy.get('#state').parent().find('.ts-control').should('contain.text', 'São Paulo');
        cy.get('#city').parent().find('.ts-control').should('contain.text', 'São Paulo');

        cy.get('#cep-error-message').should('not.be.visible').and('not.have.text');
    });

    it('2. Deve exibir mensagem de erro e limpar campos se o CEP não for encontrado', () => {
        cy.intercept('GET', 'https://viacep.com.br/ws/99999999/json/', {
            statusCode: 200,
            body: { erro: true }
        }).as('getCepNotFound');

        cy.get('#cep').type('99999999');
        cy.get('#cep').blur();

        cy.wait('@getCepNotFound');

        cy.get('#cep-error-message')
            .should('be.visible')
            .and('have.class', 'text-danger')
            .and('contain.text', 'CEP não encontrado. Por favor, preencha o formulário manualmente.');

        cy.get('#street').should('have.value', '');
        cy.get('#neighborhood').should('have.value', '');
        cy.get('#address_complement').should('have.value', '');
        cy.get('#number').should('have.value', '');
        cy.get('#state').parent().find('.ts-control').should('contain.text', '');
        cy.get('#city').parent().find('.ts-control').should('contain.text', '');
    });

    it('3. Deve exibir mensagem de erro se o CEP for inválido (menos de 8 dígitos)', () => {
        cy.get('#cep').type('12345');
        cy.get('#cep').blur();

        cy.get('#cep-error-message')
            .should('be.visible')
            .and('have.class', 'text-danger')
            .and('contain.text', 'Por favor, digite um CEP válido com 8 dígitos.');

        cy.get('#street').should('have.value', '');
        cy.get('#neighborhood').should('have.value', '');
        cy.get('#address_complement').should('have.value', '');
        cy.get('#number').should('have.value', '');
        cy.get('#state').parent().find('.ts-control').should('contain.text', '');
        cy.get('#city').parent().find('.ts-control').should('contain.text', '');
    });

    it('4. Deve exibir mensagem de erro em caso de falha na requisição da API', () => {
        cy.intercept('GET', 'https://viacep.com.br/ws/00000000/json/', {
            statusCode: 500,
            body: { message: 'Internal Server Error' }
        }).as('getCepApiError');

        cy.get('#cep').type('00000000');
        cy.get('#cep').blur();

        cy.wait('@getCepApiError');

        cy.get('#cep-error-message')
            .should('be.visible')
            .and('have.class', 'text-danger')
            .and('contain.text', 'Erro ao buscar CEP. Verifique sua conexão ou tente novamente.');

        cy.get('#street').should('have.value', '');
        cy.get('#neighborhood').should('have.value', '');
        cy.get('#address_complement').should('have.value', '');
        cy.get('#number').should('have.value', '');
        cy.get('#state').parent().find('.ts-control').should('contain.text', '');
        cy.get('#city').parent().find('.ts-control').should('contain.text', '');
    });

    it('5. Deve limpar estado/cidade e exibir erro se o retorno do ViaCEP não corresponder às opções do Tom-Select', () => {
        cy.intercept('GET', 'https://viacep.com.br/ws/99999998/json/', {
            statusCode: 200,
            body: {
                cep: "99999-998",
                logradouro: "Rua Inexistente Teste",
                complemento: "Sem complemento",
                unidade: "",
                bairro: "Bairro Fictício",
                ddd: "00",
                estado: "Estado Inexistente",
                gia: "",
                ibge: "0000000",
                localidade: "Cidade Não Mapeada",
                regiao: "",
                siafi: "",
                uf: "ZZ"
            }
        }).as('getCepUnmatchedOptions');

        cy.get('#cep').type('99999998');
        cy.get('#cep').blur();

        cy.wait('@getCepUnmatchedOptions');

        cy.wait(700);

        cy.get('#street').should('have.value', 'Rua Inexistente Teste');
        cy.get('#neighborhood').should('have.value', 'Bairro Fictício');
        cy.get('#address_complement').should('have.value', 'Sem complemento');
        cy.get('#state').parent().find('.ts-control').should('contain.text', '');
        cy.get('#city').parent().find('.ts-control').should('contain.text', '');

        cy.get('#cep-error-message')
            .should('be.visible')
            .and('have.class', 'text-danger')
            .and('satisfy', ($el) => {
                const text = $el.text();
                return text.includes('Estado não encontrado. Por favor, selecione manualmente.') || text.includes('Cidade não encontrada. Por favor, selecione manualmente.');
            });
    });

    it('6. Validação do campo "Número" e checkbox "Sem Número"', () => {
        cy.get('#number').type('123').should('have.value', '123');
        cy.get('#no_number').check();
        cy.get('#number').should('have.value', '').and('be.disabled');
        cy.get('#number-error-message').should('not.be.visible').and('not.have.text');

        cy.get('#no_number').uncheck();
        cy.get('#number').should('not.be.disabled');
        cy.get('#number-error-message')
            .should('be.visible')
            .and('contain.text', 'O numero do endereço é obrigatório, por favor, preencha o campo. Caso o seu endereço não possua número, selecione a opção "Sem número".');

        cy.get('#number').type('123').blur();
        cy.get('#number-error-message').should('not.be.visible').and('not.have.text');

        cy.get('#number').clear();
        cy.get('#no_number').check();
        cy.get('#number').should('be.disabled');
        cy.get('#number-error-message').should('not.be.visible').and('not.have.text');
    });
});
