export const loadFixtureScenarios = scenarios => {
    return cy.request({
        url: 'api/test/fixtures/scenarios',
        method: 'POST',
        body: {
            scenarios,
        },
        failOnStatusCode: true,
    });
};

export const resetFixtures = () => {
    cy.request({ method: 'POST', url: 'api/test/fixtures/reset-fixtures', failOnStatusCode: true });
    cy.wait(1000); // Wait a bit so that our reset is accounted for
    return loadFixtureScenarios(['createDefaultUserAccounts']);
};
