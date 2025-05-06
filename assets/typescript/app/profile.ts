import {InitFinishedEvent} from "./events";

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent) {
        const form = document.getElementById('profile')?.querySelector('form');
        const fieldFirstName = form?.querySelector('input[name="firstName"]');
        const fieldLastName = form?.querySelector('input[name="lastName"]');
        const fieldEmail = form?.querySelector('input[name="email"]');
        const fieldPhoneNum = form?.querySelector('input[name="phoneNum"]');
        const submitButton = form?.querySelector('button');

        if (
            fieldFirstName instanceof HTMLInputElement &&
            fieldLastName instanceof HTMLInputElement &&
            fieldEmail instanceof HTMLInputElement &&
            fieldPhoneNum instanceof HTMLInputElement &&
            submitButton instanceof HTMLButtonElement
        ){
            const user = window.profile;
            fieldFirstName.value = user.firstName;
            fieldLastName.value = user.lastName;
            fieldEmail.value = user.email;
            fieldPhoneNum.value = user.phoneNum;
            submitButton.setAttribute('data-request-path', `/api/v1/profile/${user.id}`);
        }
    }
});