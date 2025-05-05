import {InitFinishedEvent} from "./events";
import {populateModalConfirm, showModal, resetModal, redirectOnModalClose} from "../common/modal";

function deleteVehicle(){
    resetModal();
    redirectOnModalClose('');
    showModal('Vehicle Deleted', 'The app will now reload.')
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
    }
})