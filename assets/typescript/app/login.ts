import {InitFinishedEvent, VehicleChangedEvent} from "./events";

function setVehicleName(name: string){
    document.querySelectorAll('.vehicle-selector .vehicle-text').forEach((value) => {
        if (value instanceof HTMLHeadingElement)
            value.innerText = name;
    })
}
export function initializeApplication(seed: SeedResponse){
    window.vehicles = seed.vehicles;
    window.profile = seed.profile;

    if (!window.vehicles.length){
        // The user has no vehicles. Make them add one!

        // We'll need to set the onboarding link's user ID. That's easy enough.
        const onboardingSubmissionButton = document.getElementById('onboarding')?.querySelector('button');
        if (onboardingSubmissionButton instanceof HTMLButtonElement)
            onboardingSubmissionButton.setAttribute('data-request-path', `/api/v1/onboarding/${window.profile.id}`);

        // Focus the panel.
        const onboardingLink = document.getElementById('onboarding_link');
        if (onboardingLink instanceof HTMLButtonElement)
            window.panes.setTopPane(onboardingLink);
    } else {
        const header = document.getElementById('header');
        const navbar = document.getElementById('navbar');

        if (header instanceof HTMLElement && navbar instanceof HTMLElement){
            header.style.display = 'flex';
            navbar.style.display = 'flex';
        }
        window.currentVehicleIndex = 0;

        window.notifications = seed.notifications;

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
}