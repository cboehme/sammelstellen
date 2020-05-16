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
            <li class="sammelstellen-listeneintrag" onclick="${() => onSammelstelleClick(sammelstelle.properties.id)}">
                <${Sammelstelle} sammelstelle="${sammelstelle.properties}"/>
            </li>`})}
        </ul>`;
}
