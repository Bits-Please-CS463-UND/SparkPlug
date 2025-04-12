import './typescript/egg'
import './typescript/forms'
import './typescript/modal'
import './typescript/flashes'
import './typescript/popover'
import './typescript/nav'
import './typescript/map'


declare global {
    interface Window {
        flashes: Flash[];
        editButtonProxy: Function;
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