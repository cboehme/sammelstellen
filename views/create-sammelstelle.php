<div class="wrap">
    <h2><?= esc_html(get_admin_page_title()) ?></h2>
    <p>Legt eine neue Sammelstelle an.</p>
    <form method="post" name="createsammelstelle" id="createsammelstelle" class="validate" novalidate="novalidate">
        <table class="form-table" role="presentation">
            <tr class="form-field form-required">
                <th scope="row"><label for="name">Name der Sammelstelle <span class="description">(Pflichtfeld)</span></label></th>
                <td><input type="text" id="name" aria-required="true" required maxlength="120"/></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="adresse">Adresse <span class="description">(Pflichtfeld)</span></label></th>
                <td><textarea id="adresse"></textarea></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><label for="anmerkungen">Anmerkungen</label></th>
                <td><textarea id="anmerkungen"></textarea></td>
            </tr>
        </table>
    </form>
    <?php submit_button( 'Neue Sammelstelle hinzufügen', 'primary', 'createsammelstelle', true, array( 'id' => 'createsammestellesub' ) ); ?>
</div>
