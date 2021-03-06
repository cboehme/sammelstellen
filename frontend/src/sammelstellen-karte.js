import {render} from 'preact';
import {html} from "htm/preact";
import {useEffect, useRef} from "preact/hooks";

import ResizeObserver from 'resize-observer-polyfill';

import mapboxgl from 'mapbox-gl';

import Sammelstelle from "./sammelstelle";

export default function SammelstellenKarte({mapStyle, sammelstellen, selected, onSammelstelleClick = () => {}}) {

    const map = useRef();
    const mapContainer = useRef();
    useEffect(() => {
        map.current = createMap(mapContainer.current);
        let resizeObserver = observeMapContainerSize();
        return () => {
            resizeObserver.unobserve(mapContainer.current);
            resizeObserver = null;
            map.current.remove();
            map.current = null;
        }
    }, []);
    useEffect(() => {
        map.current.setStyle(mapStyle);
    }, [mapStyle]);

    const markers = useRef(new Map());
    useEffect(
        () => markers.current = updateMarkers(markers.current, sammelstellen.features, selected,
            onSammelstelleClick, map.current),
        [sammelstellen.features, selected, onSammelstelleClick]);

    return html`
       <style>
           .sammelstellen-marker {
               cursor: pointer;
           }
       </style>
       <div style="width: 100%; height: 100%" ref=${mapContainer}></div>`;

    function observeMapContainerSize() {
        const resizeObserver = new ResizeObserver(() => {
            if (map.current !== null) {
                map.current.resize();
            }
        });
        resizeObserver.observe(mapContainer.current);
        return resizeObserver;
    }
}

function createMap(containerElement) {
    const map = new mapboxgl.Map({
        container: containerElement,
        center: [7.1006600, 50.7358510],
        zoom: 12
    }).on('load', function () {
        const mapSource = this.getSource("openmaptiles");
        this.setMaxBounds(mapSource.bounds);
    });
    map.resize();
    addNavigationControl(map);
    addGeolocateControl(map);
    return map;
}

function addNavigationControl(map) {

    map.addControl(new mapboxgl.NavigationControl({
        visualizePitch: true
    }));
}

function addGeolocateControl(map) {

    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        showAccuracyCircle: false
    });
    geolocateControl.on('error', (error) => {
        console.warn(`Geolocation failed: ${error.message}`)
    });

    map.addControl(geolocateControl);
    map.on('load', function () {
        geolocateControl.trigger();
    });
}

function updateMarkers(prevSammelstellen, currentSammelstellen, selected, clickHandler, map) {

    let nextSammelstellen = new Map();
    currentSammelstellen.forEach(sammelstelle => {
        const id = sammelstelle.properties.id;
        if (prevSammelstellen.has(id)) {
            nextSammelstellen.set(id, updateSammelstelle(prevSammelstellen.get(id), sammelstelle, selected,
                clickHandler, map));
        } else {
            nextSammelstellen.set(id, addSammelstelle(sammelstelle, selected, clickHandler, map));
        }
    });
    prevSammelstellen.forEach((_, id) => {
        if (!nextSammelstellen.has(id)) {
            removeSammelstelle(prevSammelstellen.get(id));
        }
    });
    return nextSammelstellen;
}

function addSammelstelle(sammelstelle, selected, clickHandler, map) {

    let markerColor;
    if (!sammelstelle.properties.aktiv) {
        markerColor = '#b0b0b0';
    } else if (sammelstelle.properties.briefkasten) {
        markerColor = '#0c78b2';
    } else {
        markerColor = '#98D800';
    }
    const marker = new mapboxgl.Marker({
        color: markerColor
    });
    marker.clickHandler = clickHandler;
    marker.getElement().classList.add('sammelstellen-marker');
    marker.getElement().addEventListener('click', ev => {
        map.flyTo({
            center: sammelstelle.geometry.coordinates,
            zoom: 15
        })
        marker.clickHandler(sammelstelle.properties.id);
    });
    marker
        .setLngLat(sammelstelle.geometry.coordinates)
        .setPopup(createPopup(sammelstelle))
        .addTo(map);
    if (sammelstelle.properties.id === selected) {
        openPopup(marker);
        flyTo(marker, map);
    }
    return marker;
}

function createPopup(sammelstelle) {
    const popup = new mapboxgl.Popup({
        className: 'sammelstellen-popup',
        maxWidth: 'none'
    });
    const container = document.createElement("div");
    popup.setDOMContent(container);
    const popupInfo = html`<${Sammelstelle} sammelstelle=${sammelstelle.properties}/>`;
    render(popupInfo, container);
    return popup;
}

function updateSammelstelle(marker, sammelstelle, selected, clickHandler, map) {

    marker.clickHandler = clickHandler;
    if (sammelstelle.properties.id === selected) {
        openPopup(marker);
        flyTo(marker, map);
    } else {
        closePopup(marker);
    }
    return marker;
}

function openPopup(marker) {
    if (!marker.getPopup().isOpen()) {
        marker.togglePopup();
    }
}

function closePopup(marker) {
    if (marker.getPopup().isOpen()) {
        marker.togglePopup();
    }
}

function flyTo(marker, map) {
    map.flyTo({
        center: marker.getLngLat(),
        zoom: 15
    });
}

function removeSammelstelle (marker) {
    marker.remove();
}
