export const navigate = () => cy.visit('/lots');

export const uploadCorrectedPaper = ({ name, uploadName = name, type }) => {
    cy.upload_files([{ name, uploadName, type }], '[data-testid="corrected-paper-input"]');
    cy.get('svg[aria-label="Chargement..."]').should('be.visible');
    cy.get('svg[aria-label="Le corrigé a été déposé"]').should('be.visible');

    cy.contains(uploadName).should($el => {
        expect($el.attr('href')).contains('/api/corrected-paper/corrector-upload/download/');
    });
};
