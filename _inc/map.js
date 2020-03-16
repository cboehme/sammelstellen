const view = new ol.View({
    center: ol.proj.fromLonLat([7.1006600, 50.7358510]),
    extent: ol.proj.transformExtent([6.95, 50.60, 7.35, 50.80], 'EPSG:4326', 'EPSG:3857'),
    minZoom: 12,
    maxZoom: 20,
    zoom: 12
});

const map = new ol.Map({
    layers: [
        new ol.layer.Tile({
            source: new ol.source.XYZ({
                url: 'http://localhost:8080/styles/klokantech-basic/{z}/{x}/{y}.png',
                minZoom: 12,
                maxZoom: 20,
                tilePixelRatio: 2
            })
        })
    ],
    target: 'map',
    view: view
});

const geolocation = new ol.Geolocation({
    trackingOptions: {
        enableHighAccuracy: true
    },
    projection: view.getProjection(),
    tracking: true
});

geolocation.on('error', function(error) {
    console.log('Geolokalisierung ist fehlgeschlagen: ' + error.message);
});

const pointStyle = new ol.style.Style({
    image: new ol.style.Circle({
        radius: 6,
        fill: new ol.style.Fill({
            color: '#3399CC'
        }),
        stroke: new ol.style.Stroke({
            color: '#fff',
            width: 2
        })
    })
});

const positionFeature = new ol.Feature();
positionFeature.setStyle(pointStyle);

geolocation.on('change:position', function() {
    var coordinates = geolocation.getPosition();
    positionFeature.setGeometry(coordinates ?
        new ol.geom.Point(coordinates) : null);
    map.getView().animate({
        center: coordinates,
        zoom: 16,
        duration: 1000
    });
    geolocation.setTracking(false);
});

new ol.layer.Vector({
    map: map,
    source: new ol.source.Vector({
        features: [positionFeature]
    })
});

const iconStyle = new ol.style.Style({
    image: new ol.style.Icon({
        anchor: [0, 1],
        anchorXUnits: 'fraction',
        anchorYUnits: 'fraction',
        src: 'sammelstelle.svg'
    })
});

var sammelstellen = new ol.layer.Vector({
    map: map,
    source: new ol.source.Vector({
        url: 'http://localhost:8000/wp-json/sammelstellen/v1/sammelstellen',
        format: new ol.format.GeoJSON({
            dataProjection: 'EPSG:4326',
            featureProjection: 'EPSG:3857'
        })
    }),
    style: pointStyle
});

var element = document.getElementById('popup');

var popup = new ol.Overlay({
    element: element,
    positioning: 'bottom-left',
    autoPan: true,
    autoPanAnimation: {
        duration: 200
    }
});
map.addOverlay(popup);

map.on('click', function(event) {
    var feature = map.forEachFeatureAtPixel(event.pixel, (feature) => feature, {
        layerFilter: (layer) => layer === sammelstellen
    });
    if (feature) {
        var coordinates = feature.getGeometry().getCoordinates();
        popup.setPosition(coordinates);
        element.innerHTML =
            '<h5>' + feature.get('name') + '</h5>' +
            '<p>' + feature.get('adresse') + '</p>' +
            '<p><a href="' + feature.get('website') + '">Website</a></p>';
    } else {
        popup.setPosition(undefined);
    }
});
