maps = {};
lists = {};

const resizeObserver = new ResizeObserver(entries => {
    for (const map of Object.values(maps)) {
        map.map.resize();
    }
});

function initMap(container) {

    const map = new mapboxgl.Map({
        container: container,
        style: Config.mapSource,
        maxBounds: [[6.95, 50.60], [7.35, 50.80]],
        hash: true,
        center: [7.1006600, 50.7358510],
        zoom: 12
    });

    map.addControl(new mapboxgl.NavigationControl({
        visualizePitch: true
    }));

    const geolocateControl = new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        showAccuracyCircle: false
    });
    geolocateControl.on('error', (error) => console.log(`Geolocation failed: ${error.message}`));

    map.addControl(geolocateControl);

    map.on('load', function() {
        geolocateControl.trigger();
    });

    maps[container] = {
        map: map
    };

    resizeObserver.observe(document.getElementById(container));
}

function initList(container) {

    lists[container] = {
        list: document.getElementById(container)
    };
}

document.addEventListener('DOMContentLoaded', loadSammelstellen);

function loadSammelstellen() {
    fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json())
        .then(geojson => {
            for (const map of Object.values(maps)) {
                geojson.features.forEach(sammelstelle => addSammelstelleToMap(map, sammelstelle));
            }
            for (const list of Object.values(lists)) {
                geojson.features.forEach(sammelstelle => addSammelstelleToList(list, sammelstelle));
            }
        });
}

const markers = [];

function addSammelstelleToMap(map, sammelstelle) {

    let markerColor;
    if (sammelstelle.properties.briefkasten) {
        markerColor = '#0c78b2';
    } else {
        markerColor = '#98D800';
    }
    const marker = new mapboxgl.Marker({
        color: markerColor
    });
    marker.getElement().classList.add('SammelstelleMarker');
    marker.getElement().addEventListener('click', ev => {
        map.map.flyTo({
            center: sammelstelle.geometry.coordinates,
            zoom: 15
        })
    });
    marker
        .setLngLat(sammelstelle.geometry.coordinates)
        .setPopup(createPopup(sammelstelle))
        .addTo(map.map);
    markers.push({
        id: sammelstelle.properties.id,
        marker: marker
    });
}

function createPopup(sammelstelle) {
    const popup = new mapboxgl.Popup({
        className: 'SammelstellePopup',
        maxWidth: 'none'
    });
    popup.setHTML(Mustache.render(Config.popupTemplate, sammelstelle.properties));
    return popup;
}

function addSammelstelleToList(list, sammelstelle) {

    item = document.createElement('li');
    item.setAttribute('data-id', sammelstelle.properties.id);
    item.addEventListener('click', showSammelstellenMarker.bind(item));
    item.innerHTML = Mustache.render(Config.listitemTemplate, sammelstelle.properties);
    list.list.appendChild(item);
}

function showSammelstellenMarker() {
    const selectedId = this.getAttribute('data-id');
    let selectedMarker;
    for (const marker of markers) {
        if (marker.id === selectedId) {
            if (!marker.marker.getPopup().isOpen()) {
                marker.marker.togglePopup();
            }
            selectedMarker = marker.marker;
        } else {
            if (marker.marker.getPopup().isOpen()) {
                marker.marker.togglePopup();
            }
        }
    }
    for (const map of Object.values(maps)) {
        map.map.flyTo({
            center: selectedMarker.getLngLat(),
            zoom: 15
        });
    }
}
