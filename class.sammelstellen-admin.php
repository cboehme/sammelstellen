<?php


class Sammelstellen_Admin {

    const NONCE_NAME = '_sammelstellen_nonce';
    const CREATE_NONCE = 'sammelstellen-create-sammestelle';

    const FIELD_NAME = "name";
    const FIELD_ADRESSE = "adresse";
    const FIELD_LATITUDE = "lat";
    const FIELD_LONGITUDE = "lon";
    const FIELD_OEFFNUNGSZEITEN = "oeffnungszeiten";
    const FIELD_AKTIV = "aktiv";
    const FIELD_HINWEISE = "hinweise";

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

        add_submenu_page('sammelstellen', 'Sammelstelle bearbeiten', 'Bearbeiten',
            'edit_posts', 'sammelstellen-edit', array( 'Sammelstellen_Admin', 'display_edit_page') );
        add_filter( 'submenu_file', array( 'Sammelstellen_Admin', 'remove_edit_sammelstelle' ) );
    }

    public static function remove_edit_sammelstelle() {
        global $plugin_page;

        $hidden_submenus = array(
            'sammelstellen-edit' => true,
        );

        // Select another submenu item to highlight (optional).
        if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) {
            $submenu_file = 'sammelstellen';
        }

        // Hide the submenu.
        foreach ( $hidden_submenus as $submenu => $unused ) {
            remove_submenu_page( 'sammelstellen', $submenu );
        }

        return $submenu_file;
    }

    public static function load_resources() {
        wp_enqueue_style( 'sammelstellen.css', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen.css' );
        wp_enqueue_style( 'ol.css', plugin_dir_url( __FILE__ ) . '_inc/ol.css' );
        wp_enqueue_script( 'ol.js', plugin_dir_url( __FILE__ ) . '_inc/ol.js' );
    }

    public static function display_list_page() {

        $model['sammelstellen'] = self::find_all_sammelstellen();

        Sammelstellen::view( 'list-sammelstellen', $model);
    }

    public static function display_create_page() {
        $args = array(
            'sammelstelle' => array(
                'name' => '',
                'adresse' => '',
                'oeffnungszeiten' => '',
                'aktiv' => false,
                'hinweise' => '',
                'longitude' => '',
                'latitude' => ''
            )
        );
        Sammelstellen::view( 'edit-sammelstelle', $args );
    }

    private static function create_sammelstelle() {
        global $wpdb;

        if ( ! current_user_can( 'edit_posts' ) ) {
            die( 'Access not allowed' );
        }

        if ( !wp_verify_nonce( $_POST[self::NONCE_NAME], self::CREATE_NONCE ) ) {
            return false;
        }

        if ( !self::has_required_text_field( self::FIELD_NAME ) ) {
            return false;
        }
        if ( !self::has_required_text_field( self::FIELD_ADRESSE ) ) {
            return false;
        }
        if ( !self::has_required_longitude_field( self::FIELD_LONGITUDE ) ) {
            return false;
        }
        if ( !self::has_required_latitude_field( self::FIELD_LATITUDE ) ) {
            return false;
        }

        $name = sanitize_text_field( $_POST[self::FIELD_NAME] );
        $adresse = sanitize_textarea_field( $_POST[self::FIELD_ADRESSE] );
        $lon = floatval( $_POST[self::FIELD_LONGITUDE] );
        $lat = floatval( $_POST[self::FIELD_LATITUDE] );
        $oeffnungszeiten = sanitize_textarea_field( $_POST[self::FIELD_OEFFNUNGSZEITEN] );
        $aktiv = isset( $_POST[self::FIELD_AKTIV] );
        $hinweise = sanitize_textarea_field( $_POST[self::FIELD_HINWEISE] );

        $table_name = Sammelstellen::get_table_name();
        $result = $wpdb->query(
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

        return $result;
    }

    private static function has_required_text_field( $name ) {

        return isset( $_POST[$name] ) && !empty( trim( $_POST[$name] ) );
    }

    private static function has_required_latitude_field( $name ) {

        if ( self::has_required_float_field( $name ) ) {
            $latitude = floatval( $_POST[$name] );
            if (-90.0 <= $latitude && $latitude <= 90.0) {
                return true;
            }
        }
        return false;
    }

    private static function has_required_longitude_field( $name ) {

        if ( self::has_required_float_field( $name ) ) {
            $longitude = floatval( $_POST[$name] );
            if (-180.0 <= $longitude && $longitude <= 180.0) {
                return true;
            }
        }
        return false;
    }

    private static function has_required_float_field( $name ) {

        return isset( $_POST[$name] ) && preg_match( '/-?\\d+(\.\\d*)?/', $_POST[$name] ) == 1;
    }

    public static function display_edit_page() {

        if ( !isset( $_GET[ "id" ] ) ) {
            die("Invalid access");
        }

        $id = $_GET[ "id" ];
        $args = array(
            'sammelstelle' => self::find_sammelstellen_by_id( $id )
        );
        Sammelstellen::view( 'edit-sammelstelle', $args );
    }

    public static function get_sammelstellen_url() {

        $args = array( 'page' => 'sammelstellen' );

        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

    public static function get_page_url() {

        $args = array( 'page' => 'sammelstellen-create' );

        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

    public static function get_edit_sammelstelle_url($id) {

        $args = array(
            'page' => 'sammelstellen-edit',
            'id' => $id
        );

        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

    private static function find_all_sammelstellen() {
        global $wpdb;

        $table_name = Sammelstellen::get_table_name();
        return $wpdb->get_results( "
                SELECT id, name, adresse, oeffnungszeiten, aktiv, hinweise,
                    X(location) as longitude, Y(location) as latitude
                    FROM $table_name ORDER BY name");
    }

    private static function find_sammelstellen_by_id( $id ) {
        global $wpdb;

        $table_name = Sammelstellen::get_table_name();
        return $wpdb->get_row( $wpdb->prepare( "
                SELECT id, name, adresse, oeffnungszeiten, aktiv, hinweise,
                    X(location) as longitude, Y(location) as latitude
                    FROM $table_name WHERE id = %s", $id ) );
    }

}
