export const withinParentByText = (text, fn) => {
    cy.contains(text).should('be.visible');

    return cy
        .queryByText(text)
        .parent()
        .within(fn);
};

export const withinParentByLabelText = (label, fn) => {
    return cy
        .queryByLabelText(label)
        .parent()
        .within(fn);
};
