import { map, Map, latLng, tileLayer, MapOptions, marker, Marker, circle, Circle, icon, popup } from "leaflet";
import {VehicleChangedEvent} from "./events";
import {showModal} from "./modal";

const defaultZoom = 20;
const defaultIcon = icon({
    iconUrl: "/img/ralsei.png",
    iconSize: [16 * 6, 9 * 6],
    iconAnchor: [16 * 3, 9 * 6],
});
const defaultTileLayer = tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})
let vehicleMap: Map | null = null;
let carLocation: Marker | null = null;
let geofence: Circle | null = null;

function loadVehicleData(vehicle: VehicleData){
    if (vehicleMap && carLocation && geofence){
        carLocation.setLatLng(vehicle.currentLocation);

        if (vehicle.geofence){
            if (geofence){
                geofence.setLatLng(vehicle.geofence.center);
                geofence.setRadius(vehicle.geofence.radius);
            } else {
                geofence = circle([vehicle.geofence.center.lat, vehicle.geofence.center.lng], {
                    fillOpacity: 0.15,
                    radius: vehicle.geofence.radius,
                    color: 'var(--uwu-primary)',
                    fillColor: 'var(--uwu-primary)'
                });
                geofence.addTo(vehicleMap)
            }
        } else {
            geofence = null;
        }

        vehicleMap.setView(vehicle.currentLocation, defaultZoom);
    } else {
        // Map needs to be initialized
        const mapDiv = document.getElementById('map');
        if (mapDiv){
            vehicleMap = map(mapDiv, {
                center: vehicle.currentLocation,
                zoom: defaultZoom,
            })

            mapDiv.parentElement?.addEventListener('transitionstart', () => {
                vehicleMap?.invalidateSize();
            });

            // Add OSM Tile Layer
            defaultTileLayer.addTo(vehicleMap);

            // Add vehicle marker
            carLocation = marker(vehicle.currentLocation, {
                icon: defaultIcon
            });
            carLocation.addTo(vehicleMap);

            // Add geofence marker (if relevant)
            if (vehicle.geofence){
                geofence = circle([vehicle.geofence.center.lat, vehicle.geofence.center.lng], {
                    color: 'var(--uwu-primary)',
                    fillColor: 'var(--uwu-primary)',
                    fillOpacity: 0.15,
                    radius: vehicle.geofence.radius
                });

                geofence.addTo(vehicleMap)
            }

            // Add popper
            const popper = popup()
            vehicleMap.on('click', (e) => {
                if (vehicleMap && geofence){
                    popper.setLatLng(e.latlng)
                        .setContent("<form id='geofence_form'>" +
                            "<input class='visually-hidden' name='lat' value='" + e.latlng.lat + "'>" +
                            "<input class='visually-hidden' name='lng' value='" + e.latlng.lng + "'>" +
                            "<b>Set Geofence to This Place?</b></br><br/>" +
                            "<div class='form-floating'>" +
                            "<input class='form-control' id='geofence_radius' type='number' name='radius'>" +
                            "<label class='form-label' for='geofence_radius'>Radius (Meters)</label>" +
                            "</div></br>" +
                            "<button class='btn btn-primary' type='submit'>Set</button>" +
                            "</form>")
                        .openOn(vehicleMap);

                    const popperForm = document.getElementById('geofence_form');
                    if (popperForm && popperForm instanceof HTMLFormElement){
                        popperForm.addEventListener('submit', (e) => {
                            e.preventDefault();

                            // Pull form values in a very demure and mindful manner 🙏
                            const formData = new FormData(popperForm)
                            const radius = parseInt(formData.get('radius')?.toString() ?? "0") ?? 0;
                            const lat = parseFloat(formData.get('lat')?.toString() ?? "0.0") ?? 0.0;
                            const lng = parseFloat(formData.get('lng')?.toString() ?? "0.0") ?? 0.0;

                            // Get current vehicle
                            const currentVehicle = window.vehicles[window.currentVehicleIndex];

                            if (!geofence){
                                geofence = circle([lat, lng], {
                                    color: 'var(--uwu-primary)',
                                    fillColor: 'var(--uwu-primary)',
                                    fillOpacity: 0.15,
                                    radius: radius
                                });
                            } else {
                                geofence.setRadius(radius)
                                geofence.setLatLng([lat, lng])
                            }

                            // TODO: Post to radius update endpoint
                            popper.close()
                        })
                    }
                }
            })
        } else {
            showModal('Fatal Error', 'Vehicle map could not be initialized.');
        }

    }
}

window.addEventListener('vehicleChanged', (e) => {
    if (e instanceof VehicleChangedEvent)
        loadVehicleData(e.vehicle);
})