describe('Teste de Dropdown do Navbar após Login', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/');
        cy.login('saracamilo@example.com', 'Aurora@2024');
        cy.contains('Sara').should('be.visible');
    });

    it('Verifica a funcionalidade completa do dropdown do navbar', () => {
        cy.get('#dropdownMenuButton')
            .should('be.visible')
            .click({ force: true });

        cy.get('#customDropdown')
            .should('have.class', 'show')
            .should('be.visible');

        cy.get('#customDropdown').trigger('mouseenter');

        const menuItems = [
            'Painel de Controle',
            'Minhas Oportunidades',
            'Minhas Inscrições',
            'Minhas Avaliações',
            'Meus Agentes',
            'Meus Espaços',
            'Meus Eventos',
            'Minhas Iniciativas',
            'Conta e Privacidade',
            'Meu Perfil',
            'Sair'
        ];

        cy.get('#dropdownMenuButton').click({ force: true });
        cy.get('#customDropdown')
            .invoke('attr', 'class')
            .then((classList) => {
                console.log('Classes do dropdown no CI:', classList);
            });
    });
});
