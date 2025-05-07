import {icon, Icon, map, Map, marker, polyline, tileLayer} from "leaflet";

export const defaultIcon: Icon = icon({
    iconUrl: "/img/pin.svg",
    iconSize: [16 * 6, 9 * 6],
    iconAnchor: [16 * 3, 9 * 6],
});
export const defaultZoom = 0;
export const defaultLocation = {lat: 0.0, lng: 0.0};
export const defaultTileLayer = tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})

/**
 * Add a point in a vehicle's history to a map.
 *
 * @param map           Leaflet map
 * @param point         The point to add
 * @param locations     A working list of locations
 */
export function addPointToMap(map: Map|null, point: GPSLocation, locations: GPSLocation[]){
    if (map instanceof Map){
        // Add point to parsed list
        locations.unshift(point);

        // Add marker to map
        const newMarker = marker(point, {
            icon: defaultIcon
        });
        newMarker.addTo(map);

        // Add a line to the last point if one exists
        if (locations.length > 1){
            const newLine = polyline(
                [locations[0], locations[1]],
                {
                    color: 'var(--uwu-primary)'
                }
            )
            newLine.addTo(map);
        }
    }
}

/**
 * Initialize map for leaflet
 *
 * @param target
 * @param targetParent  A parent of the target that may hold relevant transitions. idk!
 */
export function initializeMap(target: HTMLElement|null, targetParent: HTMLElement|null = null): Map{
    if (target instanceof HTMLElement){
        const leaflet = map(target, {center: defaultLocation, zoom: defaultZoom});
        defaultTileLayer.addTo(leaflet);

        if (targetParent)
            targetParent.addEventListener('transitionend', (e) => {
                leaflet.invalidateSize();
            });

        return leaflet;
    }
    throw Error('uh oh!');
}