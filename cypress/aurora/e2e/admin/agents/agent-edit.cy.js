describe('Teste de navegação, validação e edição da página de Agentes', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('alessandrofeitoza@example.com', 'Aurora@2024');
        cy.visit('/painel/');
        cy.get('.nav > .nav-item').contains('Meus Agentes').click();
        cy.get('table').find('tr', 'Alessandro').contains('Editar').click();
    });

    it('Garante que a página de editar agentes funciona', () => {
        cy.get('.accordion').find('.accordion-button', 'Informações de apresentação').should('be.visible');

        cy.get('[for="name"]')
            .contains('Nome do Perfil')
            .should('be.visible');

        cy.get('#name')
            .should('be.visible');

        cy.get('[for="add-areas_of_expertise-btn"]')
            .contains('Áreas de Atuação')
            .should('be.visible');

        cy.get('#add-areas_of_expertise-btn')
            .should('be.visible');

        cy.get('[for="add-roles_in_culture-btn"]')
            .contains('Função(ões) na Cultura')
            .should('be.visible');
        
        cy.get('#add-roles_in_culture-btn')
            .should('be.visible');

        cy.get('[for="add-tags-btn"]')
            .contains('Tags')
            .should('be.visible');

        cy.get('#add-tags-btn')
            .should('be.visible');

        cy.get('[for="short-description"]')
            .contains('Descrição curta')
            .should('be.visible');

        cy.get('#short-description')
            .should('be.visible');

        cy.get('[for="long-description"]')
            .contains('Descrição longa')
            .should('be.visible');

        cy.get('#long-description')
            .should('be.visible');

        cy.get('.row > p').contains('Site')
            .should('be.visible');

        cy.get('[for="site"]')
            .contains('Link (URL)')
            .should('be.visible');
        
        cy.get('#site')
            .should('be.visible');

        cy.get('[for="link_description"]')
            .contains('Descrição do link')
            .should('be.visible');
        
        cy.get('#link_description')
            .should('be.visible');

        cy.get('#panel-personal-data').should('not.be.visible');
        cy.get('.accordion').find('.accordion-button').contains('Dados pessoais').click();
        cy.get('#panel-personal-data').should('be.visible');

        cy.get('[for="social_name"]')
            .contains('Nome Social')
            .should('be.visible');

        cy.get('#social_name')
            .should('be.visible');

        cy.get('[for="full_name"]')
            .contains('Nome completo')
            .should('be.visible');

        cy.get('#full_name')
            .should('be.visible');

        cy.get('[for="cpf"]')
            .contains('CPF')
            .should('be.visible');

        cy.get('#cpf')
            .should('be.visible');

        cy.get('[for="private_email"]')
            .contains('Email privado')
            .should('be.visible');

        cy.get('#private_email')
            .should('be.visible');

        cy.get('[for="private_phone"]')
            .contains('Telefone privado (com DDD)')
            .should('be.visible');

        cy.get('#private_phone')
            .should('be.visible');

        cy.get('[for="register_address"]')
            .contains('Cadastrar um endereço')
            .should('be.visible');

        cy.get('#register_address')
            .should('be.visible');

        cy.get('[for="link_space"]')
            .contains('Vincular à um espaço da plataforma')
            .should('be.visible');

        cy.get('#link_space')
            .should('be.visible');

        cy.get('[for="is_itinerant"]')
            .contains('Sim')
            .should('be.visible');

        cy.get('#is_itinerant')
            .should('be.visible');

        cy.get('[for="not_is_itinerant"]')
            .contains('Não')
            .should('be.visible');

        cy.get('#not_is_itinerant')
            .should('be.visible');

        cy.get('[for="account_type"]')
            .contains('Tipo da conta bancária para pagamentos')
            .should('be.visible');

        cy.get('#account_type')
            .should('be.visible');

        cy.get('[for="bank_number"]')
            .contains('Número do banco para pagamentos')
            .should('be.visible');

        cy.get('#bank_number')
            .should('be.visible');

        cy.get('[for="bank_account"]')
            .contains('Número da conta bancária para pagamentos')
            .should('be.visible');

        cy.get('#bank_account')
            .should('be.visible');

        cy.get('[for="account_digit"]')
            .contains('Dígito verificador')
            .should('be.visible');

        cy.get('#account_digit')
            .should('be.visible');

        cy.get('[for="agency"]')
            .contains('Número da agência bancária')
            .should('be.visible');

        cy.get('#agency')
            .should('be.visible');

        cy.get('[for="agency_digit"]')
            .contains('Dígito verificador')
            .should('be.visible');

        cy.get('#agency_digit')
            .should('be.visible');

        cy.get('[for="monthly_income"]')
            .contains('Renda mensal')
            .should('be.visible');

        cy.get('#monthly_income')
            .should('be.visible');
        
        cy.get('#panel-sensitive-data').should('not.be.visible');
        cy.get('.accordion').find('.accordion-button').contains('Dados sensíveis').click();
        cy.get('#panel-sensitive-data').should('be.visible');

        cy.get('label[for="birthday"]')
            .contains('Data de nascimento')
            .should('be.visible');

        cy.get('#birthday')
            .should('be.visible');

        cy.get('label[for="birthday_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('#birthday_public')
            .should('exist');

        cy.get('label[for="gender"]')
            .contains('Gênero')
            .should('be.visible');

        cy.get('select')
            .should('be.visible');

        cy.get('label[for="gender_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');
        cy.get('#gender_public')
            .should('exist');

        cy.get('label[for="sexual_orientation"]')
            .contains('Orientação Sexual')
            .should('be.visible');

        cy.get('select')
            .should('be.visible');

        cy.get('label[for="sexual_orientation_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('#sexual_orientation_public')
            .should('exist');

        cy.get('label[for="race"]')
            .contains('Raça/Cor')
            .should('be.visible');

        cy.get('select')
            .should('be.visible');

        cy.get('label[for="race_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('#race_public')
            .should('exist');

        cy.get('label[for="education"]')
            .contains('Escolaridade')
            .should('be.visible');

        cy.get('select')
            .should('be.visible');

        cy.get('label[for="education_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('#education_public')
            .should('exist');

        cy.get('p')
            .contains('É pessoa com deficiência?')
            .should('be.visible');

        cy.get('#is_disabled')
            .should('exist');

        cy.get('label[for="is_disabled"]')
            .contains('Sim')
            .should('be.visible');

        cy.get('#not_is_disabled')
            .should('exist');

        cy.get('label[for="not_is_disabled"]')
            .contains('Não')
            .should('be.visible');

        cy.get('#disabled_public')
            .should('exist');

        cy.get('label[for="disabled_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('p')
            .contains('Se considera indígena?')
            .should('be.visible');

        cy.get('#is_indigenous')
            .should('exist');

        cy.get('label[for="is_indigenous"]')
            .contains('Sim')
            .should('be.visible');

        cy.get('#not_is_indigenous')
            .should('exist');

        cy.get('label[for="not_is_indigenous"]')
            .contains('Não')
            .should('be.visible');

        cy.get('#indigenous_public')
            .should('exist');

        cy.get('label[for="indigenous_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('p')
            .contains('Se considera quilombola?')
            .should('be.visible');

        cy.get('#is_quilombola')
            .should('exist');

        cy.get('label[for="is_quilombola"]')
            .contains('Sim')
            .should('be.visible');

        cy.get('#not_is_quilombola')
            .should('exist');

        cy.get('label[for="not_is_quilombola"]')
            .contains('Não')
            .should('be.visible');

        cy.get('#quilombola_public')
            .should('exist');

        cy.get('label[for="quilombola_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('p')
            .contains('Pertence a algum povo ou comunidade tradicional?')
            .should('be.visible');

        cy.get('#is_traditional_people')
            .should('exist');

        cy.get('label[for="is_traditional_people"]')
            .contains('Sim')
            .should('be.visible');

        cy.get('#not_is_traditional_people')
            .should('exist');

        cy.get('label[for="not_is_traditional_people"]')
            .contains('Não')
            .should('be.visible');

        cy.get('#traditional_people_public')
            .should('exist');

        cy.get('label[for="traditional_people_public"]')
            .contains('Mostrar Publicamente')
            .should('be.visible');

        cy.get('#panel-social-media').should('not.be.visible');
        cy.get('.accordion').find('.accordion-button').contains('Redes sociais').click();
        cy.get('#panel-social-media').should('be.visible');

        cy.get('label[for="instagram"]')
            .contains('Instagram')
            .should('be.visible');

        cy.get('#instagram')
            .should('be.visible');

        cy.get('label[for="x"]')
            .contains('X')
            .should('be.visible');

        cy.get('#x')
            .should('be.visible');

        cy.get('label[for="facebook"]')
            .contains('Facebook')
            .should('be.visible');

        cy.get('#facebook')
            .should('be.visible');

        cy.get('label[for="vimeo"]')
            .contains('Vimeo')
            .should('be.visible');

        cy.get('#vimeo')
            .should('be.visible');

        cy.get('label[for="youtube"]')
            .contains('YouTube')
            .should('be.visible');

        cy.get('#youtube')
            .should('be.visible');

        cy.get('label[for="linkedin"]')
            .contains('LinkedIn')
            .should('be.visible');

        cy.get('#linkedin')
            .should('be.visible');

        cy.get('label[for="spotify"]')
            .contains('Spotify')
            .should('be.visible');

        cy.get('#spotify')
            .should('be.visible');

        cy.get('label[for="pinterest"]')
            .contains('Pinterest')
            .should('be.visible');

        cy.get('#pinterest')
            .should('be.visible');

        cy.get('label[for="tiktok"]')
            .contains('TikTok')
            .should('be.visible');

        cy.get('#tiktok')
            .should('be.visible');
    });

    it('Garantir que se tiver erro no formulário o registro não será editado', () => {
        cy.get('textarea[name="short_description"]').type('Fomentador da comunidade de desenvolvimento, um dos fundadores da maior comunidade de PHP do Ceará (PHP com Rapadura)');
        cy.get('#agent-edit-form').submit();
        cy.get('.toast-container > :nth-child(1)').contains('O valor é muito longo. Deveria ter 100 caracteres ou menos.');
    });
});
