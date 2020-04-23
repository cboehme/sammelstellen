<?php

/*
Plugin Name: Sammelstellen
Plugin URI: https://www.radentscheid-bonn.de/
Description: Eine Sammelstellenliste für die Radentscheid Bonn Website
Version: 1.0
Author: Christoph Böhme
Author URI: https://b3e.net/
License: GPL2
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'No direct invocation allowed';
    exit;
}

define( 'SAMMELSTELLEN__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'Sammelstellen', 'activate_plugin' ) );
register_uninstall_hook( __FILE__, array( 'Sammelstellen', 'uninstall_plugin' ) );

require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen.php' );
add_action( 'init', array( 'Sammelstellen', 'init' ) );

require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen_shortcodes.php' );
add_action( 'init', array( 'Sammelstellen_Shortcodes', 'init' ) );

require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen_rest_api.php' );
add_action( 'rest_api_init', array( 'Sammelstellen_REST_API', 'init' ) );

if ( is_admin() ) {
    require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen_admin.php' );
    add_action( 'init', array('Sammelstellen_Admin', 'init' ) );
}
