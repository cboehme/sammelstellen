import {html} from "htm/preact";

import Sammelstelle from "./sammelstelle";

export default function SammelstellenListe({sammelstellen, onSammelstelleClick = () => {}}) {

     return html`<ul>
        ${sammelstellen.features.map(sammelstelle => {return html`
            <li><${Sammelstelle} 
                sammelstelle="${sammelstelle.properties}" 
                onClick="${() => onSammelstelleClick(sammelstelle.properties.id)}"/></li>`})}
        </ul>`;
}
