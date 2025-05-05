import { map, Map, latLng, tileLayer, MapOptions, marker, Marker, polyline, Polyline, icon, LeafletMouseEvent } from "leaflet";
import {resetModal, showModal} from "./typescript/common/modal";

declare global {
    interface Window {
        locations: GPSLocation[];
        endpointURL: string;
    }
}

// Declare some constants silly style
const defaultZoom = 20;
const defaultLocation = {lat: 0.0, lng: 0.0};
const defaultIcon = icon({
    iconUrl: '/img/ralsei.png',
    iconSize: [16 * 6, 9 * 6],
    iconAnchor: [16 * 3, 9 * 6],
});
const defaultTileLayer = tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})
let vehicleMap: Map | null = null;
const parsedLocations: GPSLocation[] = [];

function addPointToMap(point: GPSLocation){
    if (vehicleMap instanceof Map){
        // Add point to parsed list
        parsedLocations.unshift(point);

        // Add marker to map
        const newMarker = marker(point, {
            icon: defaultIcon
        });
        newMarker.addTo(vehicleMap);

        // Add a line to the last point if one exists
        if (parsedLocations.length > 1){
            const newLine = polyline(
                [parsedLocations[0], parsedLocations[1]],
                {
                    color: 'var(--uwu-primary)'
                }
            )
            newLine.addTo(vehicleMap);
        }
    }
}

window.addEventListener('load', (e) => {
    // Map needs to be initialized
    const mapDiv = document.getElementById('map');
    if (mapDiv) {
        vehicleMap = map(mapDiv, {
            center: window.locations.length ? window.locations[0] : defaultLocation,
            zoom: window.locations.length ? defaultZoom : 0,
        })

        // Add OSM Tile Layer
        defaultTileLayer.addTo(vehicleMap);

        // Add initial points
        window.locations.reverse().forEach((p: GPSLocation) => {
            addPointToMap(p);
        })

        // Set onclick to add a new location to the list
        vehicleMap.on('click', (e: LeafletMouseEvent) => {
            addPointToMap(e.latlng);

            // POST to the current location endpoint
            fetch(
                window.endpointURL,
                {
                    method: 'POST',
                    body: JSON.stringify(e.latlng)
                }
            ).catch((reason) => {
                resetModal();
                showModal('Comms Error', "Couldn't save location, uh ohhhhhh!");
            })
        })
    }
})