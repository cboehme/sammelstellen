import {html} from "htm/preact";
import {useEffect, useRef, useState} from "preact/hooks";

import SammelstellenListe from "./sammelstellen-liste";
import SammelstellenKarte from "./sammelstellen-karte";

export default function Sammelstellen({
        src,
        mapStyle,
        startPushRight = Number.MAX_VALUE,
        compactMap = "(max-width: 0)"}) {

    const [sammelstellen, setSammelstellen] = useState({"type": "FeatureCollection", "features": []});
    useEffect(() => loadSammelstellen(src).then(setSammelstellen), [src]);

    const [mapWidth, setMapWidth] = useState(100);
    useEffect(() => {
        window.addEventListener("scroll", computeMapWidth);
        return () => window.removeEventListener("scroll", computeMapWidth);
    }, []);

    const [showingMap, setShowingMap] = useState(true);
    const [showingList, setShowingList] = useState(true);
    const mediaMatcher = useRef();
    useEffect(() => {
        mediaMatcher.current = window.matchMedia(compactMap);
        mediaMatcher.current.addListener(handleMaxWidthChange);
        handleMaxWidthChange();
        return () => mediaMatcher.current = null;
    }, []);

    const [selected, setSelected] = useState("");

    return html`
        <style>
            .sammelstellen-switcher {
                box-sizing: border-box;
                display: none;
            }
            .sammelstellen-karte {
                box-sizing: border-box;
                position: sticky;
                top: var(--sticky-map-top, 0);
                height: calc(100vh - var(--sticky-map-top, 0));
            }
            .sammelstellen-liste {
                box-sizing: border-box;
                width: 50%;
            }
            
            @media(${compactMap}) {
                .sammelstellen-switcher {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    position: sticky;
                    top: var(--sticky-map-top, 0);  
                    height: var(--switcher-height, 4em);
                }
                .sammelstellen-karte {
                    top: calc(var(--sticky-map-top, 0) + var(--switcher-height, 4em));
                    height: calc(100vh - var(--sticky-map-top, 0) - var(--switcher-height, 4em));
                }
                .sammelstellen-liste {
                    width: 100%;
                }
            }
        </style>
        <nav class="sammelstellen-switcher"><button onclick="${toggleMap}">${showingMap ? "Zur Listenansicht" : "Zur Kartenansicht"}</button></nav>
        <div class="sammelstellen-karte" style="margin-left: calc(100% - ${mapWidth}%); width: ${mapWidth}%; display: ${showingMap ? 'block' : 'none'};">
            <${SammelstellenKarte} mapStyle=${mapStyle} 
                                   sammelstellen="${sammelstellen}" 
                                   selected="${selected}"
                                   onSammelstelleClick="${(id) => console.log('Sammelstelle selected: ' + id)}"/>
        </div>
        <div class="sammelstellen-liste" style="visibility: ${showingList ? 'visible' : 'hidden'};">
            <${SammelstellenListe} sammelstellen="${sammelstellen}" 
                                   onSammelstelleClick="${(id) => selectSammelstelle(id)}"/>
        </div>`;

    function computeMapWidth() {

        const scrollTop = window.scrollY;
        if (scrollTop > startPushRight && !mediaMatcher.current.matches) {
            let relativeScrollTop = scrollTop - startPushRight;
            let viewportHeight = document.documentElement.clientHeight;
            if (relativeScrollTop > viewportHeight) {
                relativeScrollTop = viewportHeight;
            }
            const newWidth = 100 - (50 / viewportHeight * relativeScrollTop);
            setMapWidth(newWidth);
        } else {
            setMapWidth(100);
        }
    }

    function handleMaxWidthChange() {
        if (!mediaMatcher.current.matches) {
            setShowingMap(true);
            setShowingList(true);
            computeMapWidth();
        } else {
            if (window.scrollY > startPushRight) {
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
        if (mediaMatcher.current.matches) {
            setShowingMap(true);
            setShowingList(false);
        }
        setSelected(id);
    }
}

function loadSammelstellen(src) {

    return fetch(src)
        .then(response => response.json());
}
