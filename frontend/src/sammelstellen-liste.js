import {html} from "htm/preact";
import {useEffect} from "preact/hooks";

import Sammelstelle from "./sammelstelle";

export default function SammelstellenListe({sammelstellen, selected, onSammelstelleClick = () => {}}) {

    useEffect(() => {
        const sammelstelle = document.getElementById(`sammelstellen-sammelstelle-${selected}`);
        if (sammelstelle !== null) {
            sammelstelle.scrollIntoView({
                behavior: "smooth",
                block: "center"
            })
        }
    }, [sammelstellen, selected]);
    return html`
        <style>
            .sammelstellen-listeneintrag {
                cursor: pointer;
            }
        </style>
        <ul>
        ${sammelstellen.features.map(sammelstelle => {return html`
            <li id="sammelstellen-sammelstelle-${sammelstelle.properties.id}" 
                class="sammelstellen-listeneintrag" 
                onclick="${() => onSammelstelleClick(sammelstelle.properties.id)}">
                <${Sammelstelle} sammelstelle="${sammelstelle.properties}"/>
            </li>`})}
        </ul>`;
}
