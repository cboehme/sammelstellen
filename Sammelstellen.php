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

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'No direct invocation allowed';
    exit;
}

define( 'SAMMELSTELLEN__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen.php' );
add_action( 'init', array( 'Sammelstellen', 'init' ) );

if ( is_admin() ) {
    require_once( SAMMELSTELLEN__PLUGIN_DIR . 'class.sammelstellen-admin.php' );
    add_action( 'init', array('Sammelstellen_Admin', 'init' ) );
}
