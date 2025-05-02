import {InitFinishedEvent} from "./events";

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent){
        document.querySelectorAll("[data-toggle-terminology]").forEach((element) => {
            const terminology = element.getAttribute("data-toggle-terminology");
            if (element instanceof HTMLDivElement && terminology){
                const statusElement = element.querySelector('[data-toggle-target]');
                const toggleElement = element.querySelector('input');
                const splitTerms = terminology.split(':')
                if (statusElement instanceof HTMLParagraphElement && toggleElement instanceof HTMLInputElement && splitTerms.length == 2){
                    // Register initial state
                    statusElement.innerText = splitTerms[toggleElement.checked ? 1 : 0];

                    // Also do so for future changes
                    toggleElement.addEventListener('change', (changeEvent) => {
                        statusElement.innerText = splitTerms[toggleElement.checked ? 1 : 0];
                    })
                }
            }
        })
    }
})