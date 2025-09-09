describe('Api V2 Documentation Page Test', () => {
  beforeEach(() => {
    cy.visit('/docs/index.html');
  });

  it('should load the documentation page without errors', () => {
    cy.get('.errors-wrapper').should('not.exist');

    cy.contains('API Aurora').should('be.visible');
    cy.contains('API responsável por praticamente todas as funcionalidades de gerenciamento:');
  });
});
