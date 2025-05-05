import './typescript/app/nav'
import './typescript/app/map'
import './typescript/app/notifications'
import './typescript/app/links'
import './typescript/app/toggles'
import './typescript/app/engine'
import './typescript/app/login'
import './typescript/app/time'
import {PaneStack} from "./typescript/app/nav";

declare global {
    interface Window {
        flashes: Flash[];
        panes: PaneStack;
        vehicles: VehicleData[];
        currentVehicleIndex: number;
        notifications: ApplicationNotification[];
        profile: User;
    }
}

window.addEventListener('load', () => {
    const loginButton = document.getElementById('login_link');
    if (loginButton instanceof HTMLButtonElement)
        window.panes.setTopPane(loginButton);
})