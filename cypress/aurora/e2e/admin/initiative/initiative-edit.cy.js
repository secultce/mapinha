describe('Teste de navegação, validação e edição da página de Iniciativas', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/iniciativas');
        cy.contains('Editar').first().click();
        cy.url().should('include', '/editar')
    });

    it('Garante que a página de editar iniciativas funciona', () => {
        cy.get(':nth-child(1) > .accordion-header > .accordion-button').contains('Informações de apresentação').should('be.visible');
        cy.get('[for="name"]').contains('Nome da iniciativa').should('be.visible');
        cy.get('[for="short-description"]').contains('Descrição curta').should('be.visible');
        cy.get('[for="long-description"]').contains('Descrição longa').should('be.visible');
        cy.get(':nth-child(9) > :nth-child(1) > label').contains('Site').should('be.visible');
        cy.get(':nth-child(9) > :nth-child(2) > label').contains('Descrição do link').should('be.visible');
        cy.get('.entity-introduction-data > .mt-1 > :nth-child(1) > label').contains('Email público').should('be.visible');
        cy.get('.entity-introduction-data > .mt-1 > :nth-child(2) > label').contains('Telefone Público').should('be.visible');

        cy.get(':nth-child(2) > .accordion-header > .accordion-button').contains('Período de execução').should('be.visible');
        cy.get(':nth-child(2) > .accordion-header > .accordion-button').click();
        cy.get('#panelsStayOpen-collapseTwo > .accordion-body > .container-fluid > .row > :nth-child(1) > .form-label').contains('Data de início').should('be.visible');
        cy.get('#panelsStayOpen-collapseTwo > .accordion-body > .container-fluid > .row > :nth-child(2) > .form-label').contains('Data de término').should('be.visible');

        cy.get(':nth-child(3) > .accordion-header > .accordion-button').contains('Dados da Iniciativa').should('be.visible');
        cy.get(':nth-child(3) > .accordion-header > .accordion-button').click();
        cy.get('#panelsStayOpen-collapseThree > .accordion-body > .container-fluid > :nth-child(1)').contains('Informações do Projeto').should('be.visible');
        cy.get(':nth-child(2) > .col > .form-label').contains('Objetivo Geral').should('be.visible');
        cy.get(':nth-child(3) > .col > .form-label').contains('Objetivo Específico').should('be.visible');
        cy.get('.row.mt-4 > .col > .text-primary').contains('Adicionar Objetivo Específico').should('be.visible');
        cy.get('.container-fluid > :nth-child(8)').contains('Perfil do Público').should('be.visible');
        cy.get(':nth-child(9) > .col > .form-label').contains('Perfil do público a ser atingido pelo projeto').should('be.visible');
        cy.get(':nth-child(10) > .col > .form-label').contains('Perfis de público prioritário').should('be.visible');
        cy.get('.container-fluid > :nth-child(12)').contains('Acessibilidade').should('be.visible');
        cy.get(':nth-child(13) > .col > .form-label').contains('Acessibilidade Arquitetônica').should('be.visible');
        cy.get(':nth-child(14) > .col > .form-label').contains('Acessibilidade comunicacional').should('be.visible');
        cy.get(':nth-child(15) > .col > .form-label').contains('Acessibilidade atitudinal').should('be.visible');
        cy.get(':nth-child(16) > .col > .form-label').contains('Como essas medidas de acessibilidades serão implementadas ou disponibilizadas de acordo com o projeto proposto').should('be.visible');
        cy.get('.container-fluid > :nth-child(18)').contains('Divulgação').should('be.visible');
        cy.get(':nth-child(19) > .col > .form-label').contains('Estratégia de divulgação').should('be.visible');
        cy.get('.container-fluid > .mb-4').contains('Local').should('be.visible');
        cy.get(':nth-child(22) > .col > .form-label').contains('locais onde a iniciativa será executada').should('be.visible');
        cy.get(':nth-child(22) > .col > :nth-child(2) > :nth-child(1) > .form-check-label').contains('Vincular à um espaço da plataforma').should('be.visible');
        cy.get(':nth-child(22) > .col > :nth-child(2) > :nth-child(2) > .form-check-label').contains('Cadastrar um local').should('be.visible');
        cy.get('.border > .text-primary').contains('Nome do espaço').should('be.visible');
        cy.get('.col > .mt-4').contains('Adicionar endereço alternativo').should('be.visible');
        cy.get(':nth-child(25) > .col > .form-label').contains('Haverão ações da iniciativa em algum território prioritário?').should('be.visible');
        cy.get(':nth-child(25) > .col > :nth-child(2) > :nth-child(1) > .form-check-label').contains('Sim').should('be.visible');
        cy.get(':nth-child(25) > .col > :nth-child(2) > :nth-child(2) > .form-check-label').contains('Não').should('be.visible');
        cy.get(':nth-child(26) > .col > .form-label').contains('Especifique o território prioritário').should('be.visible');
        cy.get('h5.mt-4').contains('Financeiro').should('be.visible');
        cy.get(':nth-child(29) > .col > .form-label').contains('Valor total').should('be.visible');


        cy.get(':nth-child(4) > .accordion-header > .accordion-button').contains('Contatos').should('be.visible');
        cy.get(':nth-child(4) > .accordion-header > .accordion-button').click();
        cy.get('#panelsStayOpen-collapseFour > .accordion-body > .container-fluid > .row > :nth-child(1) > .form-label').contains('Email privado').should('be.visible');
        cy.get('#panelsStayOpen-collapseFour > .accordion-body > .container-fluid > .row > :nth-child(2) > .form-label').contains('Telefone privado').should('be.visible');


        cy.get(':nth-child(5) > .accordion-header > .accordion-button').contains('Redes sociais').should('be.visible');
        cy.get(':nth-child(5) > .accordion-header > .accordion-button').click();
        cy.get('.container-fluid > :nth-child(2) > :nth-child(1) > .form-label').contains('Instagram').should('be.visible');
        cy.get(':nth-child(4) > :nth-child(3) > .form-label').contains('TikTok').should('be.visible');
    });
});
