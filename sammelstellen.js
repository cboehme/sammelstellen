import {html} from "htm/preact";
import {useEffect, useState} from "preact/hooks";

import SammelstellenListe from "./sammelstellen-liste";
import SammelstellenKarte from "./sammelstellen-karte";

export default function Sammelstellen({mapStyle}) {

    const [sammelstellen, setSammelstellen] = useState({"type": "FeatureCollection", "features": []});
    useEffect(() => loadSammelstellen().then(setSammelstellen), []);

    const [selected, setSelected] = useState("");

    return html`
        <div style="width: 50%">
            <${SammelstellenListe} sammelstellen="${sammelstellen}" 
                                   onSammelstelleClick="${(id) => setSelected(id)}"/>
        </div>
        <div style="position: fixed; left: 50%; top: 0; right: 0; bottom: 0;">
            <${SammelstellenKarte} mapStyle=${mapStyle} 
                                   sammelstellen="${sammelstellen}" 
                                   selected="${selected}"/>
        </div>`;
}

function loadSammelstellen() {

    return fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json());
}