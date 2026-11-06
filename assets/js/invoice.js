'use strict';

window.addEventListener('load', () => window.print());

const printButton = document.querySelector('[data-print-invoice]');
if (printButton) {
    printButton.addEventListener('click', () => {
        window.print();
    });
}

const backButton = document.querySelector('[data-history-back]');
if (backButton) {
    backButton.addEventListener('click', () => {
        window.history.back();
    });
}
