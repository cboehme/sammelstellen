<?php


class Sammelstellen_Admin {

    const NONCE_NAME = '_sammelstellen_nonce';
    const CREATE_NONCE = 'sammelstellen-create-sammestelle';

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
        add_action( 'admin_enqueue_scripts', array( 'Sammelstellen_Admin', 'load_resources' ) );
    }

    public static function admin_menu() {
        add_menu_page( 'Sammelstellen', 'Sammelstellen', 'edit_posts',
            'sammelstellen', array( 'Sammelstellen_Admin', 'display_list_page' ) );
        add_submenu_page('sammelstellen', 'Neue Sammelstelle hinzufügen', 'Neu hinzufügen',
            'edit_posts', 'sammelstellen-create', array( 'Sammelstellen_Admin', 'display_create_page') );
    }

    public static function load_resources() {
        wp_enqueue_style( 'sammelstellen.css', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen.css' );
    }

    public static function display_list_page() {
        Sammelstellen::view( 'list-sammelstellen' );
    }

    public static function display_create_page() {
        Sammelstellen::view( 'create-sammelstelle' );
    }

    private static function create_sammelstelle() {
        global $wpdb;

        // FIXME: Check capabilities!

        if ( !wp_verify_nonce( $_POST[self::NONCE_NAME], self::CREATE_NONCE ) ) {
            return false;
        }

        // FIXME: Validate input!
        $name = $_POST["name"];
        $adresse = $_POST["adresse"];
        $oeffnungszeiten = $_POST["oeffnungszeiten"];
        $hinweise = $_POST["hinweise"];
        $aktiv = isset($_POST["aktiv"]);
        $lon = $_POST["lon"];
        $lat = $_POST["lat"];

        $table_name = Sammelstellen::get_table_name();
        $wpdb->query(
            $wpdb->prepare( "
                INSERT INTO $table_name
                ( name, adresse, oeffnungszeiten, hinweise, aktiv, location )
                VALUES ( %s, %s, %s, %s, %d, PointFromText( %s ) )",
            $name,
            $adresse,
            $oeffnungszeiten,
            $hinweise,
            $aktiv,
            "POINT($lon $lat)" ) );

        return true;
    }

    public static function get_page_url() {

        $args = array( 'page' => 'sammelstellen-create' );

        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

}
