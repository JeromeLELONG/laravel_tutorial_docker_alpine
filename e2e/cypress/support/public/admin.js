export const navigate = () => cy.visit('/');

export const getUsersTableRows = () => cy.get('[data-testid="user-list"]').children();

export const getUserRow = user_uuid => cy.contains(user_uuid).parent();
