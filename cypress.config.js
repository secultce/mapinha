const { defineConfig } = require("cypress");
const { downloadFile } = require("cypress-downloadfile/lib/addPlugin");

require('dotenv').config();

module.exports = defineConfig({
  viewportWidth: 1920,
  viewportHeight: 1080,
  e2e: {
    baseUrl: process.env.CYPRESS_BASE_URL,
    chromeWebSecurity: false,
    setupNodeEvents(on, config) {
        on('task', { downloadFile })
    },
    specPattern: [
      'cypress/aurora/e2e/api/**/*.cy.js',
      'cypress/aurora/e2e/web/agent/*.cy.js',
      'cypress/aurora/e2e/web/authentication/*.cy.js',
      'cypress/aurora/e2e/admin/space/*.cy.js',
      'cypress/aurora/e2e/web/**/*.cy.js',
      'cypress/aurora/e2e/admin/dashboard/*.cy.js',
      'cypress/aurora/e2e/admin/**/*.cy.js',
    ],
  },
});
