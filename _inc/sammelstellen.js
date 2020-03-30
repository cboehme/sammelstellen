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

    fetch('/wp-json/sammelstellen/v1/sammelstellen')
        .then(response => response.json())
        .then(geojson => {
            geojson.features.forEach(addSammelstelle);
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
        popup.setHTML(Mustache.render(markerTemplate, sammelstelle.properties));
        return popup;
    }

}

