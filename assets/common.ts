import './typescript/common/forms'
import './typescript/common/modal'
import './typescript/common/popover'

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