import {InitFinishedEvent} from "./events";

function updateEngineDependents(engineToggle: HTMLInputElement){
    document.querySelectorAll('[data-engine-change]').forEach((element) => {
        const attributeText = element.getAttribute('data-engine-change') ?? '';
        const splitText = attributeText.split(':')
        if (element instanceof HTMLElement && splitText.length == 2){
            element.innerText = splitText[engineToggle.checked ? 1 : 0];
        }
    })
}

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent){
        const engineToggle = document.getElementById('engine-toggle');
        if (engineToggle instanceof HTMLInputElement){
            updateEngineDependents(engineToggle);

            engineToggle.addEventListener('change', (changeEvent) => {
                updateEngineDependents(engineToggle);
            })
        }
    }
})