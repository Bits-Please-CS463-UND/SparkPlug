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
import './typescript/login'
import './typescript/time'
import {PaneStack} from "./typescript/nav";

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

window.addEventListener('load', () => {
    const loginButton = document.getElementById('login_link');
    if (loginButton instanceof HTMLButtonElement)
        window.panes.setTopPane(loginButton);
})