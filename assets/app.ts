import './typescript/forms'
import './typescript/modal'
import './typescript/flashes'
import './typescript/popover'
import './typescript/nav'
import './typescript/map'
import './typescript/notifications'
import './typescript/links'
import './typescript/toggles'
import './typescript/engine'
import {PaneStack} from "./typescript/nav";
import {InitFinishedEvent, VehicleChangedEvent} from './typescript/events'

declare global {
    interface Window {
        flashes: Flash[];
        panes: PaneStack;
        vehicles: VehicleData[];
        currentVehicleIndex: number;
        notifications: ApplicationNotification[];
    }
}

// Light/Dark mode management
const colorScheme = window.matchMedia('(prefers-color-scheme: dark)');

function setColorScheme(query: Event|MediaQueryList){
    if (query instanceof Event)
        query = <MediaQueryList>query.target;

    document.documentElement.dataset.bsTheme =
        query.matches ?
            "dark" : "light";
}

setColorScheme(colorScheme);
colorScheme.addEventListener('change', setColorScheme);

function setVehicleName(name: string){
    document.querySelectorAll('.vehicle-selector .vehicle-text').forEach((value) => {
        if (value instanceof HTMLHeadingElement)
            value.innerText = name;
    })
}
window.addEventListener('load', () => {
    window.vehicles = [
        {
            make: "Deltarune",
            model: "Ralsei",
            year: 2002,
            color: 'red',
            currentLocation: {
                lat: 47.918958,
                lng: -97.075181
            },
            locationHistory: [],
            geofence: {
                radius: 500,
                center: {
                    lat: 47.918958,
                    lng: -97.075181
                },
            }
        },
        {
            make: "Undertale",
            model: "Asriel",
            year: 1999,
            color: 'blue',
            currentLocation: {
                lat: 47.92326006567793,
                lng: -97.06003546714783
            },
            locationHistory: [],
            geofence: {
                radius: 500,
                center: {
                    lat: 47.92326006567793,
                    lng: -97.06003546714783
                },
            }
        }
    ]
    window.currentVehicleIndex = 0;

    window.notifications = [
        {
            title: "Vehicle Update Available (1)",
            message: "Update your vehicle to receive the latest security patches."
        },
        {
            title: "Vehicle Update Available (2)",
            message: "Update your vehicle to receive the latest security patches."
        },
        {
            title: "Vehicle Update Available (3)",
            message: "Update your vehicle to receive the latest security patches."
        },
        {
            title: "Vehicle Update Available (4)",
            message: "Update your vehicle to receive the latest security patches."
        },
        {
            title: "Vehicle Update Available (5)",
            message: "Update your vehicle to receive the latest security patches."
        }
    ]

    document.querySelectorAll('div.vehicle-selector span.bi-chevron-left, div.vehicle-selector span.bi-chevron-right')
        .forEach((element: Element) => {
            if (element instanceof HTMLSpanElement){
                element.addEventListener('click', (e) => {
                    if (element.classList.contains('bi-chevron-left')){
                        window.currentVehicleIndex = (window.currentVehicleIndex - 1 + window.vehicles.length) % window.vehicles.length
                    } else {
                        window.currentVehicleIndex = (window.currentVehicleIndex + 1) % window.vehicles.length
                    }

                    window.dispatchEvent(new VehicleChangedEvent(window.vehicles[window.currentVehicleIndex]))
                });
            }
        })

    window.addEventListener('vehicleChanged', (e: Event) => {
        if (e instanceof VehicleChangedEvent){
            // Update vehicle name everywhere
            setVehicleName(`${e.vehicle.year} ${e.vehicle.make} ${e.vehicle.model}`);

            // Set theme color
            document.documentElement.style.setProperty("--uwu-primary", e.vehicle.color);
        }
    });

    window.dispatchEvent(new VehicleChangedEvent(window.vehicles[0]));
    window.dispatchEvent(new InitFinishedEvent());
});