<?php


class Sammelstellen_Admin {

    const NONCE_NAME = '_sammelstellen_nonce';
    const CREATE_NONCE = 'sammelstellen-create-sammestelle';
    const EDIT_NONCE = 'sammelstellen-edit-sammestelle';
    const DELETE_NONCE = 'sammelstellen-delete-sammestelle';

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

        if ( isset( $_POST['action'] ) ) {
            if ( $_POST['action'] == 'create-sammelstelle' ) {
                self::create_sammelstelle();
            } else if ( $_POST['action'] == 'edit-sammelstelle' ) {
                self::edit_sammelstelle();
            } else if ( $_POST['action'] == 'delete-sammelstelle' ) {
                self::delete_sammelstelle();
            }

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
        add_submenu_page('sammelstellen', 'Sammelstelle löschen', 'Löschen',
            'edit_posts', 'sammelstellen-delete', array( 'Sammelstellen_Admin', 'display_confirm_delete_page') );

        add_filter( 'submenu_file', array( 'Sammelstellen_Admin', 'remove_submenus') );
    }

    public static function remove_submenus() {
        global $plugin_page;

        $hidden_submenus = array(
            'sammelstellen-edit' => true,
            'sammelstellen-delete' => true
        );

        // Select another submenu item to highlight (optional).
        $submenu_file = null;
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
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'sammelstellen-admin.css' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'sammelstellen-admin.js' );
    }

    public static function display_list_page() {
        $model['sammelstellen'] = Sammelstellen::find_all_sammelstellen();
        Sammelstellen::view( 'list-sammelstellen', $model);
    }

    public static function display_create_page() {
        $args = array(
            'sammelstelle' => array(
                'id' => '',
                'name' => '',
                'adresse' => '',
                'oeffnungszeiten' => '',
                'aktiv' => false,
                'hinweise' => '',
                'longitude' => '',
                'latitude' => ''
            )
        );
        Sammelstellen::view( 'create-sammelstelle', $args );
    }

    private static function create_sammelstelle() {
        global $wpdb;

        if ( ! current_user_can( 'edit_posts' ) ) {
            die( 'Access not allowed' );
        }

        if ( ! wp_verify_nonce( $_POST[self::NONCE_NAME], self::CREATE_NONCE ) ) {
            return false;
        }

        $input_data = self::read_input();
        if ( ! $input_data ) {
            return false;
        }

        $table_name = Sammelstellen::get_table_name();
        return $wpdb->query(
            $wpdb->prepare( "
                INSERT INTO $table_name
                ( name, adresse, oeffnungszeiten, hinweise, aktiv, location )
                VALUES ( %s, %s, %s, %s, %d, PointFromText( %s ) )",
            $input_data['name'],
            $input_data['adresse'],
            $input_data['oeffnungszeiten'],
            $input_data['hinweise'],
            $input_data['aktiv'],
            "POINT(" . $input_data['lon'] . " " . $input_data['lat'] . ")" ) );
    }

    private static function edit_sammelstelle() {
        global $wpdb;

        if ( ! current_user_can( 'edit_posts' ) ) {
            die( 'Access not allowed' );
        }

        if ( ! wp_verify_nonce( $_POST[self::NONCE_NAME], self::EDIT_NONCE ) ) {
            return false;
        }

        if ( ! isset( $_POST[ "id" ] ) ) {
            return false;
        }

        $input_data = self::read_input();
        if ( !$input_data ) {
            return false;
        }

        $table_name = Sammelstellen::get_table_name();
        return $wpdb->query(
            $wpdb->prepare( "
                UPDATE $table_name
                SET name = %s, 
                    adresse = %s, 
                    oeffnungszeiten = %s, 
                    hinweise = %s, 
                    aktiv = %d, 
                    location = PointFromText( %s )
                WHERE id = %d",
                $input_data['name'],
                $input_data['adresse'],
                $input_data['oeffnungszeiten'],
                $input_data['hinweise'],
                $input_data['aktiv'],
                "POINT(" . $input_data['lon'] . " " . $input_data['lat'] . ")",
                $input_data['id'] ) );
    }

    private static function read_input() {

        if ( ! self::has_required_text_field( self::FIELD_NAME ) ) {
            return false;
        }
        if ( ! self::has_required_text_field( self::FIELD_ADRESSE ) ) {
            return false;
        }
        if ( ! self::has_required_longitude_field( self::FIELD_LONGITUDE ) ) {
            return false;
        }
        if ( ! self::has_required_latitude_field( self::FIELD_LATITUDE ) ) {
            return false;
        }

        return array(
            'id' => intval( $_POST[ "id" ] ),
            'name' => sanitize_text_field( $_POST[self::FIELD_NAME] ),
            'adresse' => sanitize_textarea_field( $_POST[self::FIELD_ADRESSE] ),
            'lon' => floatval( $_POST[self::FIELD_LONGITUDE] ),
            'lat' => floatval( $_POST[self::FIELD_LATITUDE] ),
            'oeffnungszeiten' => sanitize_textarea_field( $_POST[self::FIELD_OEFFNUNGSZEITEN] ),
            'aktiv' => isset( $_POST[self::FIELD_AKTIV] ),
            'hinweise' => sanitize_textarea_field( $_POST[self::FIELD_HINWEISE] )
        );
    }

    private static function delete_sammelstelle() {
        global $wpdb;

        if ( ! current_user_can( 'edit_posts' ) ) {
            die( 'Access not allowed' );
        }

        if ( ! wp_verify_nonce( $_POST[ self::NONCE_NAME ], self::DELETE_NONCE ) ) {
            return false;
        }

        if ( ! isset($_POST["id"] ) ) {
            return false;
        }

        $id = intval( $_POST[ "id" ] );

        $table_name = Sammelstellen::get_table_name();
        return $wpdb->query(
            $wpdb->prepare( "
                DELETE FROM $table_name
                WHERE id = %d", $id ) );
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
        if ( ! isset( $_GET[ "id" ] ) ) {
            die("Invalid access");
        }

        $id = intval( $_GET[ "id" ] );
        $args = array(
            'sammelstelle' => Sammelstellen::find_sammelstellen_by_id($id)
        );
        Sammelstellen::view( 'edit-sammelstelle', $args );
    }

    public static function display_confirm_delete_page() {
        if ( ! isset( $_GET[ "id" ] ) ) {
            die("Invalid access");
        }

        $id = intval( $_GET[ "id" ] );
        $args = array(
            'sammelstelle' => Sammelstellen::find_sammelstellen_by_id($id)
        );
        Sammelstellen::view( 'confirm-delete-sammelstelle', $args );
    }

    public static function get_sammelstellen_url() {
        $args = array( 'page' => 'sammelstellen' );
        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

    public static function get_create_sammelstelle_url() {
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

    public static function get_delete_sammelstelle_url($id) {
        $args = array(
            'page' => 'sammelstellen-delete',
            'id' => $id
        );
        return add_query_arg( $args, admin_url( 'admin.php' ) );
    }

}
