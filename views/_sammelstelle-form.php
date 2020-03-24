<form action="<?= esc_url( $form_submission_url ); ?>" method="post" class="validate" novalidate="novalidate" id="sammelstellenForm">
    <?= wp_nonce_field( $nonce_name, Sammelstellen_Admin::NONCE_NAME ); ?>
    <input type="hidden" name="action" value="<?= esc_attr( $action ); ?>"/>
    <input type="hidden" name="id" value="<?= esc_attr( $sammelstelle->id ); ?>"/>
    <table class="form-table" role="presentation">
        <tr class="form-field form-required">
            <th scope="row"><label for="name">Name der Sammelstelle <span class="description">(erforderlich)</span></label></th>
            <td><input type="text" id="name" name="name" aria-required="true" required="required" value="<?= esc_attr( $sammelstelle->name ); ?>" maxlength="255"/></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="adresse">Adresse <span class="description">(erforderlich)</span></label></th>
            <td><textarea id="adresse" name="adresse" aria-required="true" required="required"><?= esc_html( $sammelstelle->adresse ); ?></textarea></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="map">Position <span class="description">(erforderlich)</span></label></th>
            <td><div class="map" id="map"></div>
                <input type="hidden" id="lat" name="lat" value="<?= esc_attr( $sammelstelle->latitude ); ?>" />
                <input type="hidden" id="lon" name="lon" value="<?= esc_attr( $sammelstelle->longitude ); ?>" />
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="oeffnungszeiten">Öffnungszeiten</label></th>
            <td><textarea id="oeffnungszeiten" name="oeffnungszeiten"><?= esc_html( $sammelstelle->oeffnungszeiten ); ?></textarea></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row">Aktive Sammelstelle</th>
            <td><input type="checkbox" id="aktiv" name="aktiv" <?= $sammelstelle->aktiv ? 'checked' : ''; ?>/> <label for="aktiv">Die Sammelstelle wird auf der Website angezeigt.</label></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="hinweise">Hinweise</label></th>
            <td><textarea id="hinweise" name="hinweise"><?= esc_html( $sammelstelle->hinweise ); ?></textarea></td>
        </tr>
    </table>
    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= esc_attr( $form_submit_text ); ?>"/>
</form>
<script type="application/javascript">
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

    elementById('sammelstellenForm').addEventListener('submit', function(ev) {
        var valid =
                checkElementValidity('name') &
                checkElementValidity('adresse') &
                checkPositionValidity() &
                checkElementValidity('oeffnungszeiten') &
                checkElementValidity('aktiv') &
                checkElementValidity('hinweise');

        if (!valid) {
            ev.preventDefault()
        }
    });

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'http://localhost:8080/styles/osm-bright/style.json',
        maxBounds: [[6.95, 50.60], [7.35, 50.80]],
        hash: true,
        center: [7.1006600, 50.7358510],
        zoom: 10
    });

    map.addControl(new mapboxgl.FullscreenControl());

    map.addControl(new mapboxgl.NavigationControl({
        visualizePitch: true
    }));

    const marker = new mapboxgl.Marker({
        draggable: true,
        color: '#0073AA'
    });

    marker.on('dragend', function(e) {
        const position = marker.getLngLat();
        document.getElementById('lon').value = position.lng;
        document.getElementById('lat').value = position.lat;
        map.flyTo({
            center: position,
            zoom: 16
        })
    });

    map.on('click', function(e) {
        document.getElementById('lon').value = e.lngLat.lng;
        document.getElementById('lat').value = e.lngLat.lat;
        marker.setLngLat(e.lngLat);
        marker.addTo(map);
        map.flyTo({
            center: e.lngLat,
            zoom: 16
        })
    });

    function setInitialMarkerPosition() {
        const longitude = document.getElementById('lon').value;
        const latitude = document.getElementById('lat').value;
        if (longitude !== '' && latitude !== '') {
            marker.setLngLat([longitude, latitude]);
            marker.addTo(map);

            map.jumpTo({
                center: [longitude, latitude],
                zoom: 16
            });
        }
    }
    setInitialMarkerPosition();

</script>
