exports.navigate = () => cy.visit('/');

exports.getTitle = () => cy.contains('Intec Deli Public').should('exist');
exports.getAuthentication = () =>
    cy.contains('Authentification Shibboleth').should('exist');
