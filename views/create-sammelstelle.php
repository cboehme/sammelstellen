<div class="wrap">
    <h2><?= esc_html( get_admin_page_title() ) ?></h2>
    <p>Legt eine neue Sammelstelle an.</p>
    <form action="<?= esc_url( Sammelstellen_Admin::get_page_url() ); ?>" method="post" class="validate">
        <?= wp_nonce_field( Sammelstellen_Admin::CREATE_NONCE, Sammelstellen_Admin::NONCE_NAME ) ?>
        <input type="hidden" name="action" value="create-sammelstelle">
        <table class="form-table" role="presentation">
            <tr class="form-field form-required">
                <th scope="row"><label for="name">Name der Sammelstelle <span class="description">(erforderlich)</span></label></th>
                <td><input type="text" id="name" name="name" aria-required="true" required="required" maxlength="255"/></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="adresse">Adresse <span class="description">(erforderlich)</span></label></th>
                <td><textarea id="adresse" name="adresse" aria-required="true" required="required"></textarea></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="position">Position</label></th>
                <td><input type="hidden" id="lat" name="lat"/> <input type="hidden" id="lon" name="lon"/>
                    <div class="map" id="map"></div>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="oeffnungszeiten">Öffnungszeiten</label></th>
                <td><textarea id="oeffnungszeiten" name="oeffnungszeiten"></textarea></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">Aktive Sammelstelle</th>
                <td><input type="checkbox" id="aktiv" name="aktiv"/> <label for="aktiv">Die Sammelstelle wird auf der Website angezeigt.</label></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="hinweise">Hinweise</label></th>
                <td><textarea id="hinweise" name="hinweise"></textarea></td>
            </tr>
        </table>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Neue Sammelstelle hinzufügen"/>
    </form>
    <script type="application/javascript">
        const view = new ol.View({
            center: [0, 0],
            zoom: 2
        });

        const map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
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

        geolocation.on('change:position', function() {
            var coordinates = geolocation.getPosition();
            map.getView().animate({
                center: coordinates,
                zoom: 16,
                duration: 1000
            });
            geolocation.setTracking(false);
        });
    </script>
</div>
