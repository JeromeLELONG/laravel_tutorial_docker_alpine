export const navigate = () => cy.visit('/');

export const uploadFile = (name = 'Textes et messages.docx', uploadName = name) => {
    cy.upload_files(
        [
            {
                name,
                uploadName,
                type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            },
        ],
        '[data-testid="assignment-dropzone"]',
    );

    cy.wait(1000); // Let the UI refresh with all the data
};

export const uploadPdfFile = (name = 'test.pdf', uploadName = name) => {
    cy.upload_files(
        [
            {
                name,
                uploadName,
                type: 'application/pdf',
            },
        ],
        '[data-testid="assignment-dropzone"]',
    );

    cy.wait(1000); // Let the UI refresh with all the data
};
