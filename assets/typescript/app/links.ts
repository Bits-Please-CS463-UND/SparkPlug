import {InitFinishedEvent} from "./events";
import {populateModalConfirm, showModal, resetModal, redirectOnModalClose} from "../common/modal";

function deleteVehicle(){
    const currentVehicle = window.vehicles[window.currentVehicleIndex];

    fetch(
        `/api/v1/vehicle/${currentVehicle.id}`,
        {
            method: "DELETE"
        }
    ).then((response) => {
        if (response.ok){
            resetModal();
            redirectOnModalClose('');
            showModal('Vehicle Deleted', 'The app will now reload.');
        } else {
            resetModal();
            showModal('Fatal Error', 'The vehicle could not be deleted.');
        }
    });

}

window.addEventListener('initFinished', (e) => {
    if (e instanceof InitFinishedEvent){
        // Hook vehicle deletion button
        const deleteVehicleLink = document.getElementById('delete-vehicle-link');
        if (deleteVehicleLink instanceof HTMLAnchorElement){
            deleteVehicleLink.addEventListener('click', (e) => {
                e.preventDefault();
                resetModal();
                populateModalConfirm(
                    deleteVehicle
                );
                showModal("Delete Vehicle?", "This action cannot be undone.");
            })
        }

        // Add new vehicle button
        const addVehicleLink = document.getElementById('add_vehicle_button_please');
        if (addVehicleLink instanceof HTMLButtonElement){
            addVehicleLink.setAttribute('data-request-path', `/api/v1/onboarding/${window.profile.id}`)
        }
    }
})