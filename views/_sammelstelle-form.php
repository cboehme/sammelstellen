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

    const view = new ol.View({
        center: ol.proj.fromLonLat([7.1006600, 50.7358510]),
        extent: ol.proj.transformExtent([6.95, 50.60, 7.35, 50.80], 'EPSG:4326', 'EPSG:3857'),
        zoom: 10
    });

    const marker = new ol.Feature();
    marker.setStyle(new ol.style.Style({
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
    }));

    const map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: [marker]
                })
            })
        ],
        view: view
    });

    map.on('click', function(ev) {
        const position = ol.proj.toLonLat(ev.coordinate);
        document.getElementById('lon').value = position[0];
        document.getElementById('lat').value = position[1];
        marker.setGeometry(new ol.geom.Point(ev.coordinate));
    });

    const longitude = document.getElementById('lon').value;
    const latitude = document.getElementById('lat').value;
    if (longitude !== '' && latitude !== '') {
        const coordinate = ol.proj.fromLonLat([longitude, latitude]);
        marker.setGeometry(new ol.geom.Point(coordinate));

        view.animate({
            center: coordinate,
            zoom: 16,
            duration: 1000
        });
    }

</script>
