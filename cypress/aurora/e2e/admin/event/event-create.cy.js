describe('Pagina de Cadastrar Eventos', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.login('henriquelopeslima@example.com', 'Aurora@2024');
        cy.visit('/painel/eventos/adicionar');

        Cypress.on('uncaught:exception', (err, runnable) => {
            if (err.message.includes('createPopper is not a function') || err.message.includes('Cannot read properties of null')) {
                return false;
            }
        });
    })

    it('Garante que a página de listar de eventos possui um botão de criar evento', () => {
        cy.visit('/eventos');
        cy.get('a').contains('Criar um evento').click();
        cy.url().should('include', '/painel/eventos/adicionar');
        cy.get('form').should('exist').and('be.visible');


        cy.visit('/painel/eventos/adicionar');

        cy.get('#name').type('E');
        cy.get("p.text-danger.mt-2").should('be.visible', 'O nome deve ter entre 2 e 50 caracteres.');
        cy.get('#name').clear().type('Evento Teste');

        cy.wait(100);

        cy.visit('/painel/eventos/adicionar');

        cy.get('#name').type('Evento Teste');
        cy.contains('button', 'Adicionar').click();
        cy.get("button[data-label^='Costumes']").click();
        cy.contains('button', 'Adicionar').click();
        cy.get("button[data-label='Gastronomia']").click();
        cy.get('#description').type('Este é um evento teste.');
        cy.get('#event-type').select('Presencial');
        cy.get('#start_date').type('2025-03-10');

        cy.contains('button', 'Criar em Rascunho').click();

        cy.wait(100);

        cy.contains('Evento Teste');
    });

    it('Garante que a página de listar meus eventos possui um botão de criar evento', () => {
        cy.visit('/painel');
        cy.get('.nav > .nav-item').contains('Meus Eventos').click();
        cy.get('.card > .d-flex > div > .btn').should('contain.text', 'Criar').click();
        cy.url().should('include', '/painel/eventos/adicionar');
        cy.get('.fw-bold').should('contain.text', 'Criar um evento');
    });
});
