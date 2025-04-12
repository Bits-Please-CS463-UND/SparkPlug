import {populateModalList, showModal, resetModal} from "./modal";
addEventListener("keyup", (event) => {
    if ( event.key == "~" && !(["input", "textarea", "select"].includes(document.activeElement?.tagName?.toLowerCase() ?? ""))){
        resetModal();
        populateModalList(
            [
                "Bootstrap 5: Stylesheets & Icons",
                "Symfony 7: Web Backend & Templating Engine",
                "Roboto: Primary Typeface",
                "Leaflet: OSM Map Rendering Engine",
            ]
        );
        showModal(
            "🌸 shit code, V0.0.0_alpha 🌸",
            "computer science, yaaaay..... Created with hatred and malice by 4096kb. Queer rights! 🏳️‍🌈 🏳️‍⚧️"
        );
    }
});