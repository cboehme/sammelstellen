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
    add_menu_page('Sammelstellen', 'Sammelstellen', 'manage_options', 'sammelstellen');
}
