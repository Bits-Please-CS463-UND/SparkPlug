import { Tab } from "bootstrap";

export class PaneStack {
    topPane: Tab | null;
    subPaneStack: Tab[];
    backButton: HTMLButtonElement;
    constructor(backButton: HTMLButtonElement){
        this.topPane = null;
        this.subPaneStack = [];
        this.backButton = backButton;
    }

    setTopPane(pane: HTMLElement){
        this.topPane = new Tab(pane);
        this.subPaneStack = [];
        this.showPane();
    }

    showPane() {
        const topSubPane = this.subPaneStack.at(-1);
        if (topSubPane){
            topSubPane.show();
            this.backButton.style.display = 'block';
            this.backButton.onclick = (e) => {
                e.preventDefault()
                this.popSubPane();
            }
        } else if (this.topPane){
            this.topPane.show();
            this.backButton.style.display = 'none';
        }
    }

    addSubPane(pane: HTMLElement) {
        this.subPaneStack.push(new Tab(pane));
        this.showPane()
    }

    popSubPane() {
        this.subPaneStack.pop();
        this.showPane();
    }
}

window.addEventListener("load", () => {
    // Initialize tabbing
    const backButton = document.getElementById('back_btn');
    const homePane = document.getElementById('home_link')
    if (backButton instanceof HTMLButtonElement && homePane instanceof HTMLButtonElement) {
        window.panes = new PaneStack(backButton);
        window.panes.setTopPane(homePane);
    }

    // Initialize submenu buttons
    document.querySelectorAll('[data-pane-control="sub"]').forEach((e: Element) => {
        if (e instanceof HTMLButtonElement){
            e.addEventListener('click', (event: Event) => {
                event.preventDefault();
                const targetSelector = e.attributes.getNamedItem('data-bs-target');
                if (targetSelector){
                    const targetNavItem = document.querySelector(targetSelector.value);
                    if (targetNavItem instanceof HTMLButtonElement) {
                        window.panes.addSubPane(targetNavItem);
                    }
                }
            })
        }
    })

    // Initialize major tab buttons
    document.querySelectorAll('[data-pane-control="top"]').forEach((e: Element) => {
        e.addEventListener('click', (event: Event) => {
            event.preventDefault();
            if (e instanceof HTMLButtonElement)
                window.panes.setTopPane(e);
        })
    })
})