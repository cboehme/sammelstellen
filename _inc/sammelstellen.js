String.prototype.interpolate = function (vars) {

    return this.replace(/\${(.+)}/g, (match, p1) => vars[p1] );
};

const map = new mapboxgl.Map({
    container: 'map',
    style: SammelstellenSettings.mapSource,
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

map.addControl(geolocateControl);

map.on('load', function() {
    geolocateControl.trigger();

    fetch('/wp-json/sammelstellen/v1/sammelstellen')
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
    marker.getElement().addEventListener('click', ev => {
        map.flyTo({
            center: sammelstelle.geometry.coordinates,
            zoom: 15
        })
    });
    marker
        .setLngLat(sammelstelle.geometry.coordinates)
        .setPopup(createPopup(sammelstelle))
        .addTo(map);
}

function createPopup(sammelstelle) {
    const popup = new mapboxgl.Popup({
        className: 'SammelstellePopup',
        maxWidth: 'none'
    });
    popup.setHTML(SammelstellenSettings.markerTemplate.interpolate(sammelstelle.properties));
    return popup;
}
