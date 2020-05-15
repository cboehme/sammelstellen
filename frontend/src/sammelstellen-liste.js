import {html} from "htm/preact";

import Sammelstelle from "./sammelstelle";

export default function SammelstellenListe({sammelstellen, onSammelstelleClick = () => {}}) {

     return html`${sammelstellen.features.map(sammelstelle => {return html`
        <${Sammelstelle} 
            sammelstelle="${sammelstelle.properties}" 
            onClick="${() => onSammelstelleClick(sammelstelle.properties.id)}"/>`})}`;
}
