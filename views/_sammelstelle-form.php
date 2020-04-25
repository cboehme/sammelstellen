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
            <th scope="row"><label for="postleitzahl">Postleitzahl <span class="description">(für Sortierung, erforderlich)</span></label></th>
            <td><input type="text" id="postleitzahl" name="postleitzahl" aria-required="true" required="required" value="<?= esc_attr( $sammelstelle->postleitzahl ); ?>" pattern="\d{5}"/></td>
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
            <th scope="row"><label for="website">Website</label></th>
            <td><input type="url" id="website" name="website" value="<?= esc_attr( $sammelstelle->website ); ?>" maxlength="255"/></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row">Privater Briefkasten</th>
            <td><input type="checkbox" id="briefkasten" name="briefkasten" <?= $sammelstelle->briefkasten ? 'checked' : ''; ?>/> <label for="briefkasten">Die Sammelstelle ist ein privater Briefkasten, in den ausgefüllte Unterschriftenlisten eingeworfen werden können.</label></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row">Aktive Sammelstelle</th>
            <td><input type="checkbox" id="aktiv" name="aktiv" <?= $sammelstelle->aktiv ? 'checked' : ''; ?>/> <label for="aktiv">Die Sammelstelle wird auf der Website angezeigt. Entferne den Haken, um eine Sammelstelle zeitweise von der Karte zu entfernen.</label></td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="hinweise">Hinweise</label></th>
            <td><textarea id="hinweise" name="hinweise"><?= esc_html( $sammelstelle->hinweise ); ?></textarea></td>
        </tr>
    </table>
    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= esc_attr( $form_submit_text ); ?>"/>
</form>
