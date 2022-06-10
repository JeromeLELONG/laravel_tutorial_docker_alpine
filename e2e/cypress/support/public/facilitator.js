export const navigate = () => cy.visit('/diplomes');

export const getCorrectorsTableRows = () => cy.get('[data-testid="corrector-list"]').children();

export const getTeachingUnitRow = code => {
    return cy
        .findAllByText(new RegExp(code))
        .eq(0)
        .parent()
        .parent();
};

export const getCorrectorRow = userName => cy.findByText(new RegExp(userName)).parent();

export const getCorrectorRowByIndex = index =>
    cy.get('[data-testid="corrector-list"]').within(() => cy.get(`tr:nth(${index})`));

export const selectTeachingUnit = (index, code) => {
    cy.get(`[data-testid="ues[${index}].code"][placeholder="Sélectionnez une UE"]`).should(
        'be.visible',
    );
    return cy
        .findByTestId(`ues[${index}].code`)
        .clear()
        .type(`${code}{downarrow}{enter}`, { delay: 100 });
};

export const setTeachingUnitFilter = code =>
    cy
        .get('[placeholder="Filtrer par UE"]')
        .clear()
        .type(`${code}{downarrow}{enter}`, { delay: 100 });

export const createCorrector = (firstname, lastname, username, units = []) => {
    // This label is repeated on every UE row on the diplomes page
    // We must specifycally target the first one to avoid flaky tests
    cy.findAllByText('Ajouter un correcteur')
        .eq(0)
        .click();
    cy.contains('Calendrier');
    cy.findByText('Rechercher un utilisateur ENF existant ?').click();
    cy.findByLabelText('username à rechercher :')
        .type(username)
        .parents('form')
        .within(() => {
            cy.findByText('Rechercher').click();
        });
    cy.findByText('Valider').click();

    cy.contains(firstname).should('exist');
    cy.contains(lastname).should('exist');

    if (units.length > 0) {
        cy.findByText('Ajouter une UE / un groupe SE').click();

        units.forEach((unit, index) => {
            selectTeachingUnit(index, unit);
        });
    }

    cy.findByText('Enregistrer').click();
    cy.location('pathname').should('eq', '/diplomes');
    cy.findByText('Correcteurs').click();

    getCorrectorsTableRows().should(el => expect(el).to.have.length(2));

    cy.contains(`${lastname} ${firstname}`).should('exist');
};
