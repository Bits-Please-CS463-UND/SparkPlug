import { LeafletMouseEvent } from "leaflet";
import {resetModal, showModal} from "./typescript/common/modal";
import {initializeMap, addPointToMap} from "./typescript/common/map";

declare global {
    interface Window {
        locations: GPSLocation[];
        endpointURL: string;
    }
}

const parsedLocations: GPSLocation[] = [];
const defaultZoom = 20;

window.addEventListener('load', (e) => {
    // Map needs to be initialized
    const map = initializeMap(document.getElementById('map'))

    // Set center if needed
    if (window.locations.length){
        map.setView(window.locations[0], defaultZoom)
    }

    // Add initial points
    window.locations.reverse().forEach((p: GPSLocation) => {
        addPointToMap(map, p, parsedLocations);
    });

    // Set onclick to add a new location to the list
    map.on('click', (e: LeafletMouseEvent) => {
        addPointToMap(map, e.latlng, parsedLocations);

        // POST to the current location endpoint
        fetch(
            window.endpointURL,
            {
                method: 'POST',
                body: JSON.stringify(e.latlng)
            }
        ).catch(() => {
            resetModal();
            showModal('Comms Error', "Couldn't save location, uh ohhhhhh!");
        })
    })
})