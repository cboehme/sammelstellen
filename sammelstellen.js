import {html} from "htm/preact";
import {useEffect, useRef, useState} from "preact/hooks";

import SammelstellenListe from "./sammelstellen-liste";
import SammelstellenKarte from "./sammelstellen-karte";

export default function Sammelstellen({mapStyle, shrinkAt = Number.MAX_VALUE}) {

    const [sammelstellen, setSammelstellen] = useState({"type": "FeatureCollection", "features": []});
    useEffect(() => loadSammelstellen().then(setSammelstellen), []);

    const [mapWidth, setMapWidth] = useState(100);
    useEffect(() => {
        window.addEventListener("scroll", computeMapWidth);
        return () => window.removeEventListener("scroll", computeMapWidth);
    }, []);

    const [showingMap, setShowingMap] = useState(true);
    const [showingList, setShowingList] = useState(true);
    const mediaMaxWidth = useRef();
    useEffect(() => {
        mediaMaxWidth.current = window.matchMedia("(max-width: 41em)"); /*c*/
        mediaMaxWidth.current.addListener(handleMaxWidthChange);
        handleMaxWidthChange();
        return () => mediaMaxWidth.current = null;
    }, []);

    const [selected, setSelected] = useState("");

    return html`
        <style>
            .Switcher {
              display: none;
              position: sticky;
              top: 5em /*c*/;  
              height: 4em;
              text-align: center;
              background-color: white;
            }
            .Map {
                position: sticky;
                top: 5em /*c*/;
                height: calc(95vh - 5em /*c*/);
            }
            .List {
                width: 50%;
            }
            
            @media(max-width: 41em /*c*/) {
                .Switcher {
                    display: block;
                }
                .Map {
                    top: calc(5em /*c*/ + 4em);
                    height: calc(95vh - 5em /*c*/ - 4em);
                }
                .List {
                    width: 100%;
                }
            }
        </style>
        <nav class="Switcher"><button onclick="${toggleMap}">${showingMap ? "Zur Listenansicht" : "Zur Kartenansicht"}</button></nav>
        <div class="Map" style="margin-left: calc(100% - ${mapWidth}%); width: ${mapWidth}%; display: ${showingMap ? 'block' : 'none'};">
            <${SammelstellenKarte} mapStyle=${mapStyle} 
                                   sammelstellen="${sammelstellen}" 
                                   selected="${selected}"/>
        </div>
        <div class="List" style="visibility: ${showingList ? 'visible' : 'hidden'};">
            <${SammelstellenListe} sammelstellen="${sammelstellen}" 
                                   onSammelstelleClick="${(id) => selectSammelstelle(id)}"/>
        </div>`;

    function computeMapWidth() {

        const scrollTop = window.scrollY;
        if (scrollTop > shrinkAt && !mediaMaxWidth.current.matches) {
            let shrinkProgress = scrollTop - shrinkAt;
            if (shrinkProgress > 400) {
                shrinkProgress = 400;
            }
            const newWidth = 100 - (50 / 400 * shrinkProgress);
            setMapWidth(newWidth);
        } else {
            setMapWidth(100);
        }
    }

    function handleMaxWidthChange() {
        if (!mediaMaxWidth.current.matches) {
            setShowingMap(true);
            setShowingList(true);
            computeMapWidth();
        } else {
            if (window.scrollY > shrinkAt) {
                setShowingMap(false);
                setShowingList(true);
            } else {
                setShowingMap(true);
                setShowingList(false);
            }
        }
    }

    function toggleMap() {
        setShowingMap(!showingMap);
        setShowingList(!showingList);
    }

    function selectSammelstelle(id) {
        if (mediaMaxWidth.current.matches) {
            setShowingMap(true);
            setShowingList(false);
        }
        setSelected(id);
    }
}

function loadSammelstellen() {

    return fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json());
}
