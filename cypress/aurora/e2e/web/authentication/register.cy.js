function clickOnContinueButton() {
    cy.get('.btn').contains('Continuar').click();
    cy.wait(500);
}

describe('Página de Cadastro', () => {
    beforeEach(() => {
        cy.viewport(1920,1080);
        cy.visit('/cadastro');

        Cypress.on('uncaught:exception', (err, runnable) => {
            if (err.message.includes('i.createPopper is not a function')) {
                return false;
            }
        });
    });

    it('Clica no botão Voltar e verifica redirecionamento para a página inicial', () => {
        cy.contains('a', 'Voltar').click();
        cy.contains('Boas vindas, você chegou na Aurora!');
    });

    it('Verifica se a página de cadastro existe', () => {
        cy.title().should('include', 'Cadastro');

        cy.get('form.form-stepper').should('exist').and('be.visible');

        cy.get("[name = 'first_name']").should('exist');
        cy.get("[name = 'last_name']").should('exist');
        cy.get("[name = 'birth_date']").should('exist');
        cy.get("[name = 'cpf']").should('exist');
        cy.get("[name = 'phone']").should('exist');
        cy.get("[name = 'email']").should('exist');
        cy.get("[name = 'password']").should('exist');
        cy.get("[name = 'confirm_password']").should('exist');
    });

    it('Preenche os inputs, clica em continuar e faz o aceite de termos', () => {
        cy.get("[name = 'first_name']").type('João');
        cy.get("[name = 'last_name']").type('da Silva');
        cy.get("[name = 'birth_date']").type('1990-01-01');
        cy.gerarCPF().then((cpf) => {
            cy.get('input[name="cpf"]').type(cpf, { force: true });
        });
        cy.get("[name = 'phone']").type('11999999999');
        cy.get("[name = 'phone']").should('have.value', '(11) 9 9999-9999')

        cy.get("[name = 'email']").type('joaodasilva@test.com');
        cy.get("[name = 'password']").type('a204C_DB%l.@');
        cy.get("[name = 'confirm_password']").type('a204C_DB%l.@');

        clickOnContinueButton();

        cy.get('h4').should('contain.text', 'Aceite de políticas');
        cy.get('p').should('contain.text', 'Para criar o seu perfil é necessário ler e aceitar os termos');

        const politics = [
            { link: 'Termos e condições de uso', modal: '#modalTerms' },
            { link: 'Política de privacidade', modal: '#modalPrivacy' },
            { link: 'Autorização de Uso de Imagem', modal: '#modalImage' }
        ];

        politics.forEach((politic) => {
            cy.contains(politic.link).click();

            cy.wait(500);

            cy.get(politic.modal).contains('button', 'Aceitar').click();

        });

        cy.get('#submitPolicies').click();

        cy.wait(500);

        cy.url().then((url) => {
            cy.log('Current URL:', url);
        });

        cy.contains('a', 'Entrar').click();

        cy.url().should('include', '/login');

        cy.contains('a', 'Cadastro').click();

        cy.url().should('include', '/cadastro');
    });

    it('Verifica se as validações dos campos estão funcionando', () => {
        cy.get("[name = 'first_name']").type('J');
        cy.get("#error-message").should('contain.text', 'O nome deve ter entre 2 e 50 caracteres.');
        cy.get("[name='first_name']").clear().type('Jose');

        cy.get("[name = 'last_name']").type('S');
        cy.get("#error-message").should('contain.text', 'O sobrenome deve ter entre 2 e 50 caracteres.');
        cy.get("[name = 'last_name']").clear().type('Silva');

        cy.get("[name = 'cpf']").type('teste');
        cy.get("#error-message").should('contain.text', 'Insira um CPF válido.');
        cy.gerarCPF().then((cpf) => {
            cy.get('input[name="cpf"]').clear().type(cpf, { force: true });
        });

        cy.get("[name = 'phone']").type('123');
        cy.get("#error-message").should('contain.text', 'Insira um número de telefone válido.');
        cy.get("[name = 'phone']").clear().type('88912341234');

        cy.get("[name = 'email']").type('teste');
        cy.get("#error-message").should('contain.text', 'Insira um email válido com até 100 caracteres.');
        cy.get("[name = 'email']").clear().type('joaodasneves@test.com');

        cy.get("[name = 'email']").clear().type('alessandrofeitoza@example.com');
        cy.get('#error-message').should('contain.text', 'Este email já está em uso.');

        cy.get("[name = 'password']").type('123');
        cy.get("#error-message").should('contain.text', 'A senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, símbolos e números.');
        cy.get("[name = 'password']").clear().type('a204C_DB%l.@');

        cy.get("[name = 'confirm_password']").type('321');
        cy.get("#error-message").should('contain.text', 'As senhas não correspondem.');
        cy.get('.btn').contains('Continuar').should('be.disabled');
        cy.get("[name = 'confirm_password']").clear().type('a204C_DB%l.@');

        cy.get('.btn').contains('Continuar').click();
    });


    it('Registrar um usuário comum e testar o acesso as paǵinas', () => {
        cy.get("[name = 'first_name']").type('João');
        cy.get("[name = 'last_name']").type('da Silva');
        cy.get("[name = 'birth_date']").type('1990-01-01');
        cy.gerarCPF().then((cpf) => {
            cy.get('input[name="cpf"]').type(cpf, { force: true });
        });
        cy.get("[name = 'phone']").type('11999999999');
        cy.get("[name = 'phone']").should('have.value', '(11) 9 9999-9999')

        cy.get("[name = 'email']").type('joaodasilva@test.com');
        cy.get("[name = 'password']").type('a204C_DB%l.@');
        cy.get("[name = 'confirm_password']").type('a204C_DB%l.@');

        clickOnContinueButton();

        cy.get('h4').should('contain.text', 'Aceite de políticas');
        cy.get('p').should('contain.text', 'Para criar o seu perfil é necessário ler e aceitar os termos');

        const politics = [
            { link: 'Termos e condições de uso', modal: '#modalTerms' },
            { link: 'Política de privacidade', modal: '#modalPrivacy' },
            { link: 'Autorização de Uso de Imagem', modal: '#modalImage' }
        ];

        politics.forEach((politic) => {
            cy.contains(politic.link).click();

            cy.wait(500);

            cy.get(politic.modal).contains('button', 'Aceitar').click();
        });

        cy.get('#submitPolicies').click();

        cy.wait(500);

        cy.contains('a', 'Entrar').click();

        cy.url().should('include', '/login');

        cy.visit('/login');
        cy.get('input[name="email"]').type('alessandrofeitoza@example.com');
        cy.get('input[name="password"]').type('Aurora@2024');
        cy.get('button[data-cy="submit"]').click();
        cy.wait(1000);

        cy.get('a').contains('Usuários').click();

        cy.get('input[type="search"]').type('João da Silva');

        cy.get('table tbody tr').contains('Aguardando confirmação').parents('tr').within(() => {
            cy.contains('Confirmar usuário').click();
        });
        cy.get('a[data-modal-button="confirm-link"]').click();

        cy.get('input[type="search"]').type('João da Silva');
        cy.get('table tbody tr').contains('João da Silva').parents('tr').within(() => {
            cy.contains('Ativo').should('exist');
        });

        cy.visit('/logout');
        cy.visit('/login');
        cy.get('input[name="email"]').type('joaodasilva@test.com');
        cy.get('input[name="password"]').type('a204C_DB%l.@');
        cy.get('button[data-cy="submit"]').click();
        cy.wait(500);

        cy.get('[data-cy="agents-card-dashboard"] > .align-self-center > .fs-1').contains(1);
        cy.get('[data-cy="opportunities-card-dashboard"] > .align-self-center > .fs-1').contains(0);
        cy.get('[data-cy="organizations-card-dashboard"] > .align-self-center > .fs-1').contains(0);
        cy.get('[data-cy="events-card-dashboard"] > .align-self-center > .fs-1').contains(0);
        cy.get('[data-cy="spaces-card-dashboard"] > .align-self-center > .fs-1').contains(0);
        cy.get('[data-cy="initiatives-card-dashboard"] > .align-self-center > .fs-1').contains(0);

        cy.get('a.nav-link[href="/painel/agentes/"]').click();
        cy.get('h2').contains('Meus Agentes');

        cy.get('a.nav-link[href="/painel/eventos/"]').click();
        cy.get('h2').contains('Meus Eventos');

        cy.get('a.nav-link[href="/painel/espacos/"]').click();
        cy.get('h2').contains('Meus Espaços');

        cy.get('a.nav-link[href="/painel/iniciativas/"]').click();
        cy.get('h2').contains('Minhas Iniciativas');

        cy.get('a.nav-link[href="/painel/organizacoes/"]').click();
        cy.get('h2').contains('Minhas Organizações');
    });

    // TODO: Ajustar esses testes por conta do REGMEL
    // it('Garante que não é possível fazer o cadastro se já estiver logado', () => {
    //     cy.login('talysonsoares@example.com', 'Aurora@2024');
    //     cy.contains('Cadastro').should('exist').click();
    //     cy.get("div[aria-atomic='true']").contains('Você já está logado.').should('be.visible');
    // });
    
    // it('Verifica o título e subtítulo do perfil de agente cultural existe', () => {
    //     clickOnContinueButton();
    //     cy.get('.form-step-active > .btn-form-group > .btn-next').click();
    //
    //     cy.get('h4').should('contain.text', 'Criação do Perfil');
    //     cy.get('p').should('contain.text', 'Para finalizar o seu cadastro, é necessário criar seu Perfil de Agente Cultural.');
    // });
    // it('Verifica os campos, preenche inputs, verifica o contador de caracteres e interage com as áreas de atuação', () => {
    //     clickOnContinueButton();
    //     cy.get('.form-step-active > .btn-form-group > .btn-next').click();
    //
    //     const campos = ['#inputProfileName', '#inputProfileDescription', '#areas-container'];
    //     campos.forEach((campo) => {
    //         cy.get(campo).should('exist');
    //     });
    //
    //     cy.get('#inputProfileName').type('João da Silva');
    //
    //     const description = 'Sou um agente cultural com experiência em várias áreas da cultura.';
    //     cy.get('#inputProfileDescription').type(description);
    //     cy.get('#counter').should('contain.text', `${description.length}/400`);
    //
    //     cy.get('#add-area-btn').click();
    //
    //     cy.get('.dropdown-menu').contains('Área de interesse').click();
    //
    //     cy.get('#areas-container').should('contain.text', 'Área de interesse');
    //
    //     cy.get('.area-tag').contains('Área de interesse').within(() => {
    //         cy.get('.remove-tag').click();
    //     });
    //     cy.get('#areas-container').should('not.contain.text', 'Área de interesse');
    //
    //     cy.contains('a', 'Criar conta').click();
    // });
});

