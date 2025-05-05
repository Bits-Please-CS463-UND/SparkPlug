import {Map, Marker, circle, Circle, popup} from "leaflet";
import {VehicleChangedEvent} from "./events";
import {addPointToMap, initializeMap} from "../common/map";
import {resetModal, showModal} from "../common/modal";

const defaultZoom = 20;
let vehicleMap: Map | null = null;
let geofence: Circle | null = null;

function resetMap(): Map{
    if (vehicleMap instanceof Map){
        vehicleMap.off();
        vehicleMap.remove();
    }
    geofence = null;

    const mapDiv = document.getElementById('map');
    return initializeMap(mapDiv, mapDiv?.parentElement?.parentElement);
}

function loadVehicleData(map: Map, vehicle: VehicleData){
    // Set map options
    if (vehicle.currentLocation){
        map.setView(vehicle.currentLocation, defaultZoom)
    }

    // Add points to map
    const working: GPSLocation[] = [];
    vehicle.locationHistory.reverse().forEach((l: GPSLocation) => {
        addPointToMap(map, l, working);
    })

    // Set geofence if applicable
    if (vehicle.geofence){
        geofence = circle(vehicle.geofence.center, {
            fillOpacity: 0.15,
            radius: vehicle.geofence.radius,
            color: 'var(--uwu-primary)',
            fillColor: 'var(--uwu-primary)'
        });
        geofence.addTo(map);
    }

    // Add geofence popper
    const popper = popup()
    map.on('click', (e) => {
        popper.setLatLng(e.latlng);
        popper.setContent(
            "<form id='geofence_form'>" +
            "   <input class='visually-hidden' name='lat' value='" + e.latlng.lat + "'>" +
            "   <input class='visually-hidden' name='lng' value='" + e.latlng.lng + "'>" +
            "   <b>Set Geofence to This Place?</b>" +
            "   </br>" +
            "   <br/>" +
            "   <div class='form-floating'>" +
            "       <input class='form-control' id='geofence_radius' type='number' name='radius'>" +
            "       <label class='form-label' for='geofence_radius'>Radius (Meters)</label>" +
            "   </div>" +
            "   </br>" +
            "   <button class='btn btn-primary' type='submit'>Set</button>" +
            "</form>"
        );
        popper.openOn(map);

        const popperForm = document.getElementById('geofence_form');
        if (popperForm && popperForm instanceof HTMLFormElement){
            popperForm.addEventListener('submit', (e) => {
                e.preventDefault();

                // Pull form values in a very demure and mindful manner 🙏
                const formData = new FormData(popperForm)
                const radius = parseInt(formData.get('radius')?.toString() ?? "0") ?? 0;
                const lat = parseFloat(formData.get('lat')?.toString() ?? "0.0") ?? 0.0;
                const lng = parseFloat(formData.get('lng')?.toString() ?? "0.0") ?? 0.0;

                fetch(
                    `/api/v1/vehicle/${vehicle.id}/location/geofence`,
                    {
                        method: 'POST',
                        body: JSON.stringify({
                            lat: lat,
                            lng: lng,
                            radius: radius
                        })
                    }
                ).then(() => {
                    vehicle.geofence = {
                        radius: radius,
                        center: {
                            lat: lat,
                            lng: lng
                        }
                    }

                    if (!geofence){
                        geofence = circle([lat, lng], {
                            color: 'var(--uwu-primary)',
                            fillColor: 'var(--uwu-primary)',
                            fillOpacity: 0.15,
                            radius: radius
                        });
                        geofence.addTo(map);
                    } else {
                        geofence.setRadius(radius);
                        geofence.setLatLng([lat, lng]);
                    }
                }).catch(() => {
                    resetModal();
                    showModal('Fatal Error', "Couldn't write geofence to server.....");
                })
                popper.close();
            });
        }
    });
}

window.addEventListener('vehicleChanged', (e) => {
    if (e instanceof VehicleChangedEvent){
        vehicleMap = resetMap();
        loadVehicleData(vehicleMap, e.vehicle);
    }
})