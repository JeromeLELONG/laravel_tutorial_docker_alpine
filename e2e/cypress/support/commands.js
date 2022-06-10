import '@testing-library/cypress/add-commands';
import 'cypress-file-upload';
require('cypress-downloadfile/lib/downloadFileCommand');
import format from 'date-fns/format';

const getFixtureAsFile = (files, fileToUpload) =>
    cy.fixture(fileToUpload.name, 'base64').then(fileContent => {
        const file = {
            fileContent,
            fileName: fileToUpload.uploadName || fileToUpload.name,
            mimeType: fileToUpload.type || 'application/pdf',
            encoding: 'base64',
        };

        return [...files, file];
    });

const getFixturesAsFiles = (files, filesToUpload) => {
    const [fileToUpload, ...otherFilesToUpload] = filesToUpload;
    return getFixtureAsFile(files, fileToUpload).then(newFiles => {
        if (otherFilesToUpload.length > 0) {
            return getFixturesAsFiles(newFiles, otherFilesToUpload);
        }

        return newFiles;
    });
};

Cypress.Commands.add('upload_files', (filesToUpload, selector) => {
    return getFixturesAsFiles([], filesToUpload).then(files => {
        return cy.get(selector).upload(files, { subjectType: 'drag-n-drop' });
    });
});

Cypress.Commands.add('setDateInputValue', (label, date) =>
    cy
        .queryByLabelText(new RegExp(label))
        .invoke('attr', 'type', 'text') // HACK: Cypress has trouble setting the date input value
        .type(format(date, 'YYYY-MM-DD')),
);
