const map = new mapboxgl.Map({
    container: 'map',
    style: 'http://localhost:8080/styles/positron/style.json',
    maxBounds: [[6.95, 50.60], [7.35, 50.80]],
    hash: true,
    center: [7.1006600, 50.7358510],
    zoom: 12
});

const geolocateControl = new mapboxgl.GeolocateControl({
    positionOptions: {
        enableHighAccuracy: true
    }
});
map.addControl(geolocateControl);

map.on('load', function() {
    geolocateControl.trigger();

    fetch('http://localhost:8000/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json())
        .then(geojson => {
            geojson.features.forEach(addSammelstelle);
        });
});

function addSammelstelle(sammelstelle) {
    const marker = new mapboxgl.Marker({
        color: '#98D800'
    });
    marker.getElement().classList.add('SammelstelleMarker');
    marker
        .setLngLat(sammelstelle.geometry.coordinates)
        .setPopup(createPopup(sammelstelle))
        .addTo(map);
}

function createPopup(sammelstelle) {
    const name = sammelstelle.properties.name;
    const adresse = sammelstelle.properties.adresse;

    const popup = new mapboxgl.Popup({
        className: 'SammelstellePopup',
        maxWidth: 'none'
    });
    popup.setHTML('<h1>' + name + '</h1><p>' + adresse + '</p>');
    return popup;
}
