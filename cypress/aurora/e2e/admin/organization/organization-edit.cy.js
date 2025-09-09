describe('Painel de Controle - Página de editar Organizações', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/');
        cy.get('.nav > .nav-item').contains('Minhas Organizações').click();
    })

    it('Garante que a página de editar organizações esteja com todos os componentes visíveis', () => {
        cy.contains('tr', '30praum').find('[data-cy=edit-btn]').click();
        cy.get('.entity-edit-header > .fs-3').should('contain.text', 'Edição de 30praum');

        cy.get('.manage-people > :nth-child(1)').should('contain.text', 'Pessoas (1)');
        cy.get('.people > .fw-bold').should('contain.text', 'Alessandro');
        cy.get('.align-self-center').should('contain.text', 'gerenciar pessoas');

        cy.get(':nth-child(1) > .accordion-header > .accordion-button').should('contain.text', 'Informações de apresentação');
        cy.get('[for="name"] > .required').should('contain.text', 'Nome da Organização');
        cy.get('#name').should('contain.value', '30praum');
        cy.get('.mb-2 > label').should('contain.text', 'Área de Atuação');
        cy.get('.mb-2 > .d-flex').should('contain.text', 'adicionar nova');
        cy.get('.entity-introduction-data > :nth-child(4) > label').should('contain.text', 'Tags');
        cy.get('.entity-introduction-data > label.required').should('contain.text', 'Descrição curta');
        cy.get('#short-description').should('contain.value','Gravadora independente de trap, localizada em Fortaleza-CE');
        cy.get('[for="long-description"]').should('contain.text', 'Descrição longa');
        cy.get('#long-description').should('contain.value', 'Gravadora independente de trap, localizada em Fortaleza-CE');
        cy.get(':nth-child(9) > :nth-child(1) > label').should('contain.text', 'Site (URL)');
        cy.get('#site').should('contain.value', 'https://30praum.com.br/');
        cy.get(':nth-child(9) > :nth-child(2) > label').should('contain.text', 'Descrição do link:');
        cy.get('#link-description').should('contain.value', '');
        cy.get('.mt-1 > :nth-child(1) > label').should('contain.text', 'Email público');
        cy.get('#email').should('contain.value', '333@fashionlog.com.br');
        cy.get('.mt-1 > :nth-child(2) > label').should('contain.text', 'Telefone Público (com DDD)');
        cy.get('#phone').should('contain.value', '(85) 99999-0009');

        cy.get(':nth-child(2) > .accordion-header > .accordion-button').should('contain.text', 'Dados da organização').click();
        cy.get('#panelsStayOpen-collapseTwo > .accordion-body > .responsive-size').should('contain.text', 'Os dados inseridos abaixo não serão exibidos publicamente, exceto nos casos em que forem selecionadas as opções "Mostrar publicamente".')
        cy.get('#panelsStayOpen-collapseTwo > .accordion-body > .container-fluid > :nth-child(1)').should('contain.text', 'Dados gerais');
        cy.get(':nth-child(2) > .col > .form-label').should('contain.text', 'É pessoa jurídica?');
        cy.get('#organization-yes').should('be.checked');
        cy.get('#organization-no').should('not.be.checked');
        cy.get('.row.mt-2 > :nth-child(1) > .form-label').should('contain.text', 'Email privado');
        cy.get('#private-email').should('contain.value', '');
        cy.get('.row.mt-2 > :nth-child(2) > .form-label').should('contain.text', 'Telefone privado (com DDD)');
        cy.get('#private-phone').should('contain.value', '');
        cy.get('.container-fluid > :nth-child(5)').should('contain.text', 'Dados de endereço');
        cy.get('#link-platform').should('not.be.checked');
        cy.get('#register-address').should('not.be.checked');
        cy.get(':nth-child(7) > .col > .form-label').should('contain.text', 'É itinerante?');
        cy.get('#itinerant-yes').should('not.be.checked');
        cy.get('#itinerant-no').should('not.be.checked');
        cy.get('.col > .mt-2').should('contain.text', 'Mostrar publicamente');
        cy.get('.container-fluid > :nth-child(9)').should('contain.text', 'Dados bancários');
        cy.get(':nth-child(10) > :nth-child(1) > .form-label').should('contain.text', 'Tipo de conta bancária para pagamentos');
        cy.get(':nth-child(1) > #type-of-bank-account').should('contain.text', 'Selecione');
        cy.get(':nth-child(10) > :nth-child(2) > .form-label').should('contain.text', 'Número do banco para pagamentos');
        cy.get(':nth-child(2) > #type-of-bank-account').should('contain.text', 'Selecione');
        cy.get('.my-4 > :nth-child(1) > .form-label').should('contain.text', 'Número da conta bancária');
        cy.get('#bank-number').should('contain.value', '');
        cy.get('.my-4 > :nth-child(2) > .form-label').should('contain.text', 'Dígito verificador');
        cy.get('#check-digit').should('contain.value', '');
        cy.get('.my-4 > :nth-child(3) > .form-label').should('contain.text', 'Número da agência bancária');
        cy.get('#branch-number').should('contain.value', '');
        cy.get(':nth-child(4) > .form-label').should('contain.text', 'Dígito verificador');
        cy.get('#branch-check-digit').should('contain.value', '');
        cy.get('#other-details').should('contain.text', 'adicionar outros dados bancários');
        cy.get('.container-fluid > :nth-child(14)').should('contain.text', 'Renda da organização');
        cy.get(':nth-child(15) > .col-md-6 > .form-label').should('contain.text', 'Faturamento anual');
        cy.get('#annual_revenue').should('contain.text', 'Selecione');

        cy.get(':nth-child(3) > .accordion-header > .accordion-button').should('contain.text', 'Redes sociais').click();
        cy.get('#panelsStayOpen-collapseThree > .accordion-body > .responsive-size').should('contain', 'Os dados inseridos abaixo serão exibidos para todos os usuários da plataforma.');
        cy.get('.mb-3 > .fw-bold').should('contain.text', 'Redes sociais');
        cy.get('#panelsStayOpen-collapseThree > .accordion-body > .container-fluid > :nth-child(2) > :nth-child(1) > .form-label').should('contain.text', 'Instagram');
        cy.get('#instagram').should('contain.value', '');
        cy.get(':nth-child(2) > :nth-child(2) > .form-label').should('contain.text', 'X');
        cy.get('#x').should('contain.value', '');
        cy.get(':nth-child(2) > :nth-child(3) > .form-label').should('contain.text', 'Facebook');
        cy.get('#facebook').should('contain.value', '');
        cy.get('#panelsStayOpen-collapseThree > .accordion-body > .container-fluid > :nth-child(3) > :nth-child(1) > .form-label').should('contain.text', 'Vimeo');
        cy.get('#vimeo').should('contain.value', '');
        cy.get('#panelsStayOpen-collapseThree > .accordion-body > .container-fluid > :nth-child(3) > :nth-child(2) > .form-label').should('contain.text', 'YouTube');
        cy.get('#youtube').should('contain.value', '');
        cy.get(':nth-child(3) > :nth-child(3) > .form-label').should('contain.text', 'LinkedIn');
        cy.get('#linkedin').should('contain.value', '');
        cy.get(':nth-child(4) > :nth-child(1) > .form-label').should('contain.text', 'Spotify');
        cy.get('#spotify').should('contain.value', '');
        cy.get(':nth-child(4) > :nth-child(2) > .form-label').should('contain.text', 'Pinterest');
        cy.get('#pinterest').should('contain.value', '');
        cy.get(':nth-child(4) > :nth-child(3) > .form-label').should('contain.text', 'TikTok');
        cy.get('#tiktok').should('contain.value', '');

        cy.get('.delete-archive > .btn').should('contain.text', 'Excluir');
        cy.get('.logout-submit > .btn-outline-light').should('contain.text', 'Sair');
        cy.get('.logout-submit > .btn-light').should('contain.text', 'Salvar');
    });
});