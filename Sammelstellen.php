<?php

/*
Plugin Name: Sammelstellen
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: christoph
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

defined( 'ABSPATH' ) or die( 'No direct invokation allowed' );

add_action('admin_menu', 'sammelstellen_init');

function sammelstellen_init() {

    add_menu_page('Sammelstellen', 'Sammelstellen', 'edit_posts', 'sammelstellen',
            'sammelstellen_list_page');
    add_submenu_page('sammelstellen', 'Neue Sammelstelle hinzufügen', 'Neu hinzufügen',
           'edit_posts', 'neu', 'sammelstellen_neu_page');
}

function sammelstellen_list_page() {
?>
    <div class="wrap">
        <h1>Sammelstellen</h1>
    </div>
<?php
}

function sammelstellen_neu_page() {
?>
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
<?php
}