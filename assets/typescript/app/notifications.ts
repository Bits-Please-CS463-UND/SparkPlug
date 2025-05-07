import {InitFinishedEvent} from "./events";
import {isHandledResponse, isNotificationBundle} from "../common/types";
import {resetModal, showModal} from "../common/modal";

let syncInitialized = false;

function addNotification(n: ApplicationNotification){
    const template = document.getElementById('template-notification');
    const target = document.querySelector('div[data-template-target="notifications"]');

    if (template instanceof HTMLTemplateElement && target instanceof HTMLDivElement){
        const templateClone = template.content.cloneNode(true);
        if (templateClone instanceof DocumentFragment) {
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
    }
}

function syncNotifications(){
    console.log('Pulling notifs');
    const url = `/api/v1/notifications/all/${window.profile.id}`
    fetch(
        url
    ).then((r) => {
        r.json().then((nb) => {
            if (isHandledResponse(nb) && isNotificationBundle(nb)){
                const heldIds = window.notifications.map(x => x.id);
                const newNotifs = nb.notifications.filter(x => !heldIds.includes(x.id));

                newNotifs.forEach((n) => {
                    window.notifications.unshift(n);
                    addNotification(n);
                    if (n.priority === 'high'){
                        resetModal();
                        showModal(n.title, n.message);
                    }
                })
            }
        })
    })
    setTimeout(syncNotifications, 5000);
}

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent){
        const notifications = window.notifications;

        if (!syncInitialized){
            setTimeout(syncNotifications, 5000);
            syncInitialized = true;
        }

        if (notifications.length){
            notifications.forEach((n: ApplicationNotification) => {
                addNotification(n);
            })
        }
    }
})