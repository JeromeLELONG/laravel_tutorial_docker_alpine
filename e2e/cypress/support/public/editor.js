import { withinParentByText, withinParentByLabelText } from '../utils';

export const navigateToYear = year => cy.visit(`/${year}`);

export const navigateToDegree = (yearStr, degree_code) => {
    const year = parseInt(yearStr, 10);
    cy.visit(`/${year}/${degree_code}`);

    cy.contains(`Année universitaire ${year}-${year + 1} - ${degree_code}`).should('exist');
};

export const selectDegree = degree =>
    cy
        .contains(degree)
        .parent()
        .within(() => cy.queryByText('Préparer').click());

export const prepareEvaluation = code => {
    withinParentByText(code, () => {
        cy.queryByText('Préparer').click();
    });
};

export const checkEvaluationPrepareButtonExist = code => {
    withinParentByText(code, () => {
        cy.queryByText('Préparer').should(el => expect(el).to.exist);
    });
};

const nativeInputValueSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;

// Bypass React by injecting value directly in the DOM
// Then trigger the event and bubble up to React
// @see https://github.com/cypress-io/cypress/issues/1570#issuecomment-450966053
export const changeEvaluationDate = (label, date) => {
    withinParentByLabelText(label, () => {
        cy.get('input[type="date"]').then($input => {
            const input = $input[0];
            nativeInputValueSetter.call(input, date);

            input.dispatchEvent(
                new Event('change', {
                    value: date,
                    bubbles: true
                })
            );
        });
    });
};
