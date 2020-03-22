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
    map.addSource('sammelstellen', {
        type: 'geojson',
        data: 'http://localhost:8000/wp-json/sammelstellen/v1/sammelstellen'
    });
    map.addLayer({
        id: 'sammelstellen',
        type: 'circle',
        source: 'sammelstellen'
    });
    geolocateControl.trigger();
});

map.on('mouseenter', 'sammelstellen', function() {
    map.getCanvas().style.cursor = 'pointer';
});

map.on('mouseleave', 'sammelstellen', function() {
    map.getCanvas().style.cursor = '';
});

map.on('click', 'sammelstellen', function(e) {
    const coordinates = e.features[0].geometry.coordinates.slice();
    const name = e.features[0].properties.name;
    const adresse = e.features[0].properties.adresse;

    new mapboxgl.Popup({
        className: 'SammelstellePopup',
        maxWidth: 'none'
    })
        .setLngLat(coordinates)
        .setHTML('<h1>' + name + '</h1><p>' + adresse + '</p>')
        .addTo(map);
});
