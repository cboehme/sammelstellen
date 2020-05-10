import {html} from "htm/preact";
import {useEffect, useState} from "preact/hooks";

import SammelstellenListe from "./sammelstellen-liste";
import SammelstellenKarte from "./sammelstellen-karte";

export default function Sammelstellen({mapStyle, shrinkAt = Number.MAX_VALUE}) {

    const [sammelstellen, setSammelstellen] = useState({"type": "FeatureCollection", "features": []});
    useEffect(() => loadSammelstellen().then(setSammelstellen), []);

    const [mapWidth, setMapWidth] = useState("100%");
    useEffect(() => {
        window.addEventListener("scroll", computeMapWidth);
        return () => window.removeEventListener("scroll", computeMapWidth);
    }, []);

    const [selected, setSelected] = useState("");

    return html`
        <div id="map" 
             style="position: sticky; margin-left: calc(100% - ${mapWidth}); width: ${mapWidth}; top: 5em; height: calc(95vh - 5em);">
            <${SammelstellenKarte} mapStyle=${mapStyle} 
                                   sammelstellen="${sammelstellen}" 
                                   selected="${selected}"/>
        </div>
        <div style="width: 50%">
            <${SammelstellenListe} sammelstellen="${sammelstellen}" 
                                   onSammelstelleClick="${(id) => setSelected(id)}"/>
        </div>`;

    function computeMapWidth() {

        const scrollTop = window.scrollY;
        if (scrollTop > shrinkAt) {
            let shrinkProgress = scrollTop - shrinkAt;
            if (shrinkProgress > 400) {
                shrinkProgress = 400;
            }
            const newWidth = 100 - (50 / 400 * shrinkProgress);
            setMapWidth(newWidth + "%");
        } else {
            setMapWidth("100%");
        }
    }

}

function loadSammelstellen() {

    return fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json());
}
