maps = {};
lists = {};

function initMap(container, markerTemplate) {

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
        }
    });
    geolocateControl.on('error', (error) => console.log(`Geolocation failed: ${error.message}`));

    map.addControl(geolocateControl);

    map.on('load', function() {
        geolocateControl.trigger();
    });

    maps[container] = {
        map: map,
        markerTemplate: markerTemplate
    };

}

function initList(container, itemTemplate) {

    lists[container] = {
        list: document.getElementById(container),
        itemTemplate: itemTemplate
    };
}

document.addEventListener('DOMContentLoaded', loadSammelstellen);

function loadSammelstellen() {
    fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json())
        .then(geojson => {
            for (map of Object.values(maps)) {
                geojson.features.forEach(sammelstelle => addSammelstelle(map, sammelstelle));
            }
            for (list of Object.values(lists)) {
                geojson.features.forEach(sammelstelle => addSammelstelleToList(list, sammelstelle));
            }
        });
}

function addSammelstelle(map, sammelstelle) {

    const marker = new mapboxgl.Marker({
        color: '#98D800'
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
        .setPopup(createPopup(sammelstelle, map.markerTemplate))
        .addTo(map.map);
}

function createPopup(sammelstelle, markerTemplate) {
    const popup = new mapboxgl.Popup({
        className: 'SammelstellePopup',
        maxWidth: 'none'
    });
    popup.setHTML(Mustache.render(markerTemplate, sammelstelle.properties));
    return popup;
}

function addSammelstelleToList(list, sammelstelle) {

    item = document.createElement('li');
    item.innerHTML = Mustache.render(list.itemTemplate, sammelstelle.properties);
    list.list.appendChild(item);
}