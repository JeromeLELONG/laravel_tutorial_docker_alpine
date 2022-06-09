export const loginAs = ({ login, password }) => {
    cy.get('#login').type(login);
    cy.get('#password').type(password);
    return cy.contains('Connexion').click();
};

export const logout = () => cy.get('a:contains("Déconnexion")').click();
