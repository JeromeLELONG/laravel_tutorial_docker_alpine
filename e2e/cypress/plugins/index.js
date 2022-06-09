//const wp = require('@cypress/webpack-preprocessor');
const { downloadFile } = require('cypress-downloadfile/lib/addPlugin');
const fs = require('fs');
const path = require('path');
const pdf = require('pdf-parse');

/*
module.exports = on => {
    const options = {
        webpackOptions: require('../../webpack.config'),
    };
    on('file:preprocessor', wp(options));
};
*/

/*
module.exports = (on, config) => {
    on('task', { downloadFile });
};
*/

const repoRoot = path.join(__dirname, '..', '..'); // assumes pdf at project root

const parseLetterOfThanksPdf = async downloadsFolder => {
    const filenames = fs.readdirSync(downloadsFolder);

    /*
    fs.readdir(downloadsFolder, (err, files) => {
        files.forEach(file => {
            console.log(file);
        });
    });
    */
    const pdfPathname = path.join(downloadsFolder + `/${filenames[0]}`);
    let dataBuffer = fs.readFileSync(pdfPathname);
    return await pdf(dataBuffer); // use async/await since pdf returns a promise
};

const parseDischargeReceiptPdf = async downloadsFolder => {
    const filenames = fs.readdirSync(downloadsFolder);

    /*
    fs.readdir(downloadsFolder, (err, files) => {
        files.forEach(file => {
            console.log(file);
        });
    });
    */
    //const pdfPathname = path.join(downloadsFolder + `/${filenames[1]}`);
    const pdfPathname = path.join(downloadsFolder + `/${filenames[0]}`);
    let dataBuffer = fs.readFileSync(pdfPathname);
    return await pdf(dataBuffer); // use async/await since pdf returns a promise
};

const deleteDownloadFiles = async downloadsFolder => {
    if (fs.existsSync(downloadsFolder)) {
        const filenames = fs.readdirSync(downloadsFolder);

        /*
        fs.readdir(downloadsFolder, (err, files) => {
            files.forEach(file => {
                console.log(file);
            });
        });
        */

        for (let filename of filenames) {
            const pdfPathname = path.join(downloadsFolder + `/${filename}`);
            fs.unlinkSync(pdfPathname);
        }
    }

    return true;
};

module.exports = (on, config) => {
    on('task', {
        getLetterOfThanksPdfContent(pdfName) {
            return parseLetterOfThanksPdf(pdfName);
        },
    });
    on('task', {
        getDischargeReceiptPdfContent(pdfName) {
            return parseDischargeReceiptPdf(pdfName);
        },
    });
    on('task', {
        deleteDownloadFiles(downloadFolder) {
            return deleteDownloadFiles(downloadFolder);
        },
    });
    on('task', { downloadFile });
};
