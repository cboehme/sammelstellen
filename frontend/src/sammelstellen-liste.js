import {html} from "htm/preact";

import Sammelstelle from "./sammelstelle";

export default function SammelstellenListe({sammelstellen, onSammelstelleClick = () => {}}) {

     return html`
        <style>
            .sammelstellen-listeneintrag {
                cursor: pointer;
            }
        </style>
        <ul>
        ${sammelstellen.features.map(sammelstelle => {return html`
            <li class="sammelstellen-listeneintrag"><${Sammelstelle} 
                sammelstelle="${sammelstelle.properties}" 
                onClick="${() => onSammelstelleClick(sammelstelle.properties.id)}"/></li>`})}
        </ul>`;
}
