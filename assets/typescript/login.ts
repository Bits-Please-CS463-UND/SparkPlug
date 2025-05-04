import {InitFinishedEvent, VehicleChangedEvent} from "./events";

function setVehicleName(name: string){
    document.querySelectorAll('.vehicle-selector .vehicle-text').forEach((value) => {
        if (value instanceof HTMLHeadingElement)
            value.innerText = name;
    })
}
export function initializeApplication(vehicleData: VehicleData[]){
    window.vehicles = vehicleData;

    const header = document.getElementById('header');
    const navbar = document.getElementById('navbar');

    if (header instanceof HTMLElement && navbar instanceof HTMLElement){
        header.style.display = 'flex';
        navbar.style.display = 'flex';
    }
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

    const homePane = document.querySelector('[data-bs-target="#home"]');
    if (homePane instanceof HTMLButtonElement)
        window.panes.setTopPane(homePane);
}