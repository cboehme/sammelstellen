function elementById(id) {
    return document.getElementById(id);
}

function checkElementValidity(id) {
    const element = elementById(id);
    if (element.checkValidity()) {
        element.parentElement.parentElement.classList.remove('form-invalid');
        return true;
    }
    element.parentElement.parentElement.classList.add('form-invalid');
    return false;
}

function checkPositionValidity() {
    const element = elementById('lat');
    if (element.value === '') {
        element.parentElement.parentElement.classList.add('form-invalid');
        return false;
    }
    element.parentElement.parentElement.classList.remove('form-invalid');
    return true;
}

function validateForm(ev) {
    const valid =
        checkElementValidity('name') &
        checkElementValidity('adresse') &
        checkElementValidity('postleitzahl') &
        checkPositionValidity() &
        checkElementValidity('oeffnungszeiten') &
        checkElementValidity('website') &
        checkElementValidity('briefkasten') &
        checkElementValidity('aktiv') &
        checkElementValidity('hinweise');

    if (!valid) {
        ev.preventDefault()
    }
}

let map;
let marker;

function initializeMapControl()
{
    map = new mapboxgl.Map({
        container: 'map',
        style: SammelstellenSettings.mapSource,
        maxBounds: [[6.95, 50.60], [7.35, 50.80]],
        hash: true,
        center: [7.1006600, 50.7358510],
        zoom: 10
    });

    map.addControl(new mapboxgl.FullscreenControl());
    map.addControl(new mapboxgl.NavigationControl({
        visualizePitch: true
    }));

    marker = new mapboxgl.Marker({
        draggable: true,
        color: '#0073AA'
    });

    marker.on('dragend', function () {
        const position = marker.getLngLat();
        document.getElementById('lon').value = position.lng;
        document.getElementById('lat').value = position.lat;
        map.flyTo({
            center: position,
            zoom: 17
        })
    });

    map.on('click', function (ev) {
        document.getElementById('lon').value = ev.lngLat.lng;
        document.getElementById('lat').value = ev.lngLat.lat;
        marker.setLngLat(ev.lngLat);
        marker.addTo(map);
        map.flyTo({
            center: ev.lngLat,
            zoom: 17
        })
    });
}

function setInitialMarkerPosition() {
    const longitude = document.getElementById('lon').value;
    const latitude = document.getElementById('lat').value;
    if (longitude !== '' && latitude !== '') {
        marker.setLngLat([longitude, latitude]);
        marker.addTo(map);

        map.jumpTo({
            center: [longitude, latitude],
            zoom: 17
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    elementById('sammelstellenForm').addEventListener('submit', validateForm);
});
document.addEventListener('DOMContentLoaded', initializeMapControl);
document.addEventListener('DOMContentLoaded', setInitialMarkerPosition);