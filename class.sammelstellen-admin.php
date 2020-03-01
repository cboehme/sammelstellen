<?php


class Sammelstellen_Admin {

    private static $initialised = false;

    public static function init() {
        if ( !self::$initialised ) {
            self::init_hooks();
            self::$initialised = true;
        }

        if ( isset( $_POST['action'] ) && $_POST['action'] == 'create-sammelstelle' ) {
            self::create_sammelstelle();
        }

    }

    private static function init_hooks() {
        add_action( 'admin_menu', array( 'Sammelstellen_Admin', 'admin_menu' ) );
    }

    public static function admin_menu() {
        add_menu_page( 'Sammelstellen', 'Sammelstellen', 'edit_posts',
            'sammelstellen', array( 'Sammelstellen_Admin', 'display_list_page' ) );
        add_submenu_page('sammelstellen', 'Neue Sammelstelle hinzufügen', 'Neu hinzufügen',
            'edit_posts', 'sammelstellen-create', array( 'Sammelstellen_Admin', 'display_create_page') );
    }

    public static function display_list_page() {
        Sammelstellen::view( 'list-sammelstellen' );
    }

    public static function display_create_page() {
        Sammelstellen::view( 'create-sammelstelle' );
    }

    private static function create_sammelstelle() {
        // FIXME: Check capabilities!
        // FIXME: Check nonce!

        echo "Okay!";
    }

    public static function get_page_url() {

        $args = array( 'page' => 'sammelstellen-create' );

        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

}
