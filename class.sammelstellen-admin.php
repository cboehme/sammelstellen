<?php


class Sammelstellen_Admin {

    private static $initialised = false;

    public static function init() {
        if ( !self::$initialised ) {
            self::init_hooks();
            self::$initialised = true;
        }
    }

    private static function init_hooks() {
        add_action( 'admin_menu', array( 'Sammelstellen_Admin', 'admin_menu' ) );
    }

    public static function admin_menu() {
        add_menu_page( 'Sammelstellen', 'Sammelstellen', 'edit_posts',
            'sammelstellen', array( 'Sammelstellen_Admin', 'display_list_page' ) );
        add_submenu_page('sammelstellen', 'Neue Sammelstelle hinzufügen', 'Neu hinzufügen',
            'edit_posts', 'neu', array( 'Sammelstellen_Admin', 'display_create_page') );
    }

    public static function display_list_page() {
        Sammelstellen::view( 'list-sammelstellen' );
    }

    public static function display_create_page() {
        Sammelstellen::view( 'create-sammelstelle' );
    }

}
