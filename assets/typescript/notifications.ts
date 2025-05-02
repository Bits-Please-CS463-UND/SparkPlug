import {InitFinishedEvent} from "./events";

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent){
        const notifications = window.notifications;
        const template = document.getElementById('template-notification');
        const target = document.querySelector('div[data-template-target="notifications"]');

        if (notifications.length && template instanceof HTMLTemplateElement && target instanceof HTMLDivElement){
            notifications.forEach((n: ApplicationNotification) => {
                const templateClone = template.content.cloneNode(true);
                if (templateClone instanceof DocumentFragment){
                    // Set title
                    const title = templateClone.querySelector('[data-template-slot="title"]');
                    if (title instanceof HTMLElement)
                        title.innerText = n.title;

                    // Set message
                    const message = templateClone.querySelector('[data-template-slot="message"]');
                    if (message instanceof HTMLElement)
                        message.innerText = n.message;

                    const deleteButton = templateClone.querySelector('[data-template-slot="delete"]');
                    if (deleteButton instanceof HTMLSpanElement)
                        deleteButton.addEventListener('click', (e) => {
                            if (e.target instanceof HTMLElement)
                                target.removeChild(e.target.parentElement ?? e.target);

                            // TODO: Issue DELETE request to backend
                        });

                    target.appendChild(templateClone);
                }
            })
        }
    }
})