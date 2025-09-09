describe('Página de Detalhes da Organização', () => {
    beforeEach(() => {
        cy.viewport(1920, 1080);
        cy.visit('/');
    });

    it('Acessa diretamente uma organização na página de organizações e verifica cada parte da página de detalhes', () => {

        cy.get('nav').contains('Organizações').click();

        cy.url().should('include', '/organizacoes');

        cy.get(':nth-child(2) > .pt-3 > .d-flex > .btn').click();

        cy.contains('Apresentação').should('be.visible');
        cy.get('.agent-public').contains('DESIGN, ACESSIBILIDADE, DESIGNINCLUSIVO').should('be.visible');

        cy.get('.project-info').within(() => {
            cy.contains('Este projeto está à iniciativa').should('be.visible');
            cy.get('a').contains('Nome da iniciativa associada').should('be.visible');
        });

        cy.get('.agent-public').within(() => {
            cy.contains('Tipo de iniciativa').should('be.visible');
            cy.contains('Tags (3)').should('be.visible');
            cy.contains('Período da iniciativa').should('be.visible');
            cy.contains('Valor do projeto').should('be.visible');
        });

        cy.get('.profile-description').within(() => {
            cy.contains('Descrição').should('be.visible');
            cy.get('.description-agent-profile').should('contain.text', 'Lorem ipsum dolor sit amet');
            cy.get('a.text-decoration-none').contains('(ver mais)').should('be.visible');
        });

        cy.get('.agent-profile-contact').within(() => {
            cy.contains('Site').should('be.visible');
            cy.get('a').contains('Site da iniciativa').should('have.attr', 'href', 'https://www.example.com');
            cy.contains('Telefone').should('be.visible');
            cy.contains('(00) 12345-6789').should('be.visible');
        });

        cy.get('.agent-social-media').within(() => {
            cy.contains('Redes sociais').should('be.visible');
            const socialLinks = [
                { platform: 'Instagram', url: 'https://www.instagram.com/nomedainiciativa' },
                { platform: 'Vimeo', url: 'https://www.vimeo.com/nomedainiciativa' },
                { platform: 'Spotify', url: 'https://open.spotify.com/user/nomedainiciativa' },
                { platform: 'X (antigo Twitter)', url: 'https://x.com/nomedainiciativa' },
                { platform: 'YouTube', url: 'https://www.youtube.com/nomedainiciativa' },
                { platform: 'Pinterest', url: 'https://www.pinterest.com/nomedainiciativa' },
                { platform: 'Facebook', url: 'https://www.facebook.com/nomedainiciativa' },
                { platform: 'LinkedIn', url: 'https://www.linkedin.com/company/nomedainiciativa' },
                { platform: 'Tiktok', url: 'https://www.tiktok.com/@nomedainiciativa' }
            ];

            socialLinks.forEach(({ platform, url }) => {
                cy.contains(platform).should('be.visible');
                cy.get(`a[href="${url}"]`).should('have.attr', 'target', '_blank').and('be.visible');
            });
        });

        cy.get('.agent-connections').within(() => {
            cy.contains('Outras informações').should('be.visible');
            cy.contains('Perfil de público').should('be.visible');
            cy.contains('Acessibilidade arquitetônica').should('be.visible');
            cy.contains('Estratégias de divulgação').should('be.visible');
            cy.contains('Local').should('be.visible');
        });
    });
});
