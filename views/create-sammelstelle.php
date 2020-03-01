<div class="wrap">
    <h2><?= esc_html( get_admin_page_title() ) ?></h2>
    <p>Legt eine neue Sammelstelle an.</p>
    <form action="<?= esc_url( Sammelstellen_Admin::get_page_url() ); ?>" method="post" class="validate">
        <input type="hidden" name="action" value="create-sammelstelle">
        <table class="form-table" role="presentation">
            <tr class="form-field form-required">
                <th scope="row"><label for="name">Name der Sammelstelle <span class="description">(Pflichtfeld)</span></label></th>
                <td><input type="text" id="name" aria-required="true" required="required" maxlength="120"/></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="adresse">Adresse <span class="description">(Pflichtfeld)</span></label></th>
                <td><textarea id="adresse" aria-required="true" required="required"></textarea></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="anmerkungen">Anmerkungen</label></th>
                <td><textarea id="anmerkungen"></textarea></td>
            </tr>
        </table>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Neue Sammelstelle hinzufügen"/>
    </form>
</div>
