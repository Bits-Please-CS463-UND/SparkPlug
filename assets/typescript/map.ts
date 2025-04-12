import { map, latLng, tileLayer, MapOptions, marker, circle, icon, popup } from "leaflet";

window.addEventListener('load', () => {
    const activityPane = document.getElementById('activity');
    if (activityPane) {
        let initialized = false;
        activityPane.addEventListener('transitionend', () => {
            if (!initialized){
                initialized = true;
                const options: MapOptions = {
                    center: latLng(47.918958, -97.075181),
                    zoom: 20,
                };

                const mapIcon = icon({
                    iconUrl: "/img/ralsei.png",
                    iconSize: [16 * 6, 9 * 6],
                    iconAnchor: [16 * 3, 9 * 6],
                })

                const renderedMap = map('map', options);
                tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(renderedMap);

                const carLocation = marker([47.918958, -97.075181], {
                    icon: mapIcon
                }).addTo(renderedMap);
                const carRadius = circle([47.918958, -97.075181], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.2,
                    radius: 5000
                }).addTo(renderedMap);

                const popper = popup()

                renderedMap.on('click', (e) => {
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
                        .openOn(renderedMap);

                    const popperForm = document.getElementById('geofence_form');
                    if (popperForm && popperForm instanceof HTMLFormElement){
                        popperForm.addEventListener('submit', (e) => {
                            e.preventDefault();

                            // Pull form values in a very demure and mindful manner 🙏
                            const formData = new FormData(popperForm)
                            const radius = parseInt(formData.get('radius')?.toString() ?? "0") ?? 0;
                            const lat = parseFloat(formData.get('lat')?.toString() ?? "0.0") ?? 0.0;
                            const lng = parseFloat(formData.get('lng')?.toString() ?? "0.0") ?? 0.0;

                            // Pass to the map for update
                            carRadius.setRadius(radius)
                            carRadius.setLatLng([lat, lng])

                            // TODO: Post to radius update endpoint
                            popper.close()
                        })
                    }
                })
            }
        })
    }
})