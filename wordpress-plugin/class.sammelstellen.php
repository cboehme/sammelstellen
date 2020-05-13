<?php


class Sammelstellen {

    private static $initialised = false;

    public static function init() {
        if ( !self::$initialised ) {
            self::register_styles_and_scripts();
            self::$initialised = true;
        }
    }

    public static function register_styles_and_scripts() {
        wp_register_script( 'mustache.js', plugin_dir_url( __FILE__ ). '_inc/mustache.min.js' );
        wp_register_script( 'mapbox-gl.js', plugin_dir_url( __FILE__ ) . '_inc/mapbox-gl.js' );
        wp_register_script( 'ResizeObserver.js', plugin_dir_url( __FILE__ ) . '_inc/ResizeObserver.js' );
        wp_register_script( 'sammelstellen.js', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen.js', array(), false, true );
        wp_register_script( 'sammelstellen-admin.js', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen-admin.js', array(), false, true );
        wp_register_style( 'mapbox-gl.css', plugin_dir_url( __FILE__ ) . '_inc/mapbox-gl.css' );
        wp_register_style( 'sammelstellen.css', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen.css' );
        wp_register_style( 'sammelstellen-admin.css', plugin_dir_url( __FILE__ ) . '_inc/sammelstellen-admin.css' );
    }

    public static function activate_plugin() {
        global $wpdb;

        $table_name = Sammelstellen::get_table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name tinytext NOT NULL,
                adresse mediumtext NOT NULL,
                postleitzahl varchar(5) NOT NULL,
                oeffnungszeiten tinytext DEFAULT '' NOT NULL,   
                website tinytext DEFAULT '' NOT NULL,
                briefkasten boolean DEFAULT false NOT NULL,
                aktiv boolean DEFAULT false NOT NULL,
                hinweise mediumtext DEFAULT '' NOT NULL,
                location point NOT NULL,
                PRIMARY KEY  (id)                
                ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );
    }

    public static function uninstall_plugin() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'sammelstellen';

        $wpdb->query( "DROP TABLE IF EXISTS $table_name;" );
    }

    public static function view( $name, array $args = array() ) {

        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }

        $file = SAMMELSTELLEN__PLUGIN_DIR . 'views/'. $name . '.php';

        include( $file );
    }

    public static function get_table_name() {
        global $wpdb;

        return $wpdb->prefix . 'sammelstellen';
    }

    public static function find_all_sammelstellen() {
        global $wpdb;

        $table_name = self::get_table_name();
        return $wpdb->get_results("
                SELECT id, name, adresse, postleitzahl, oeffnungszeiten, website, briefkasten, aktiv, hinweise,
                    X(location) as longitude, Y(location) as latitude
                    FROM $table_name 
                    ORDER BY name" );
    }

    public static function find_sammelstellen_by_aktiv( $aktiv ) {
        global $wpdb;

        $table_name = self::get_table_name();
        return $wpdb->get_results( $wpdb->prepare( "
                SELECT id, name, adresse, postleitzahl, oeffnungszeiten, website, briefkasten, aktiv, hinweise,
                    X(location) as longitude, Y(location) as latitude
                    FROM $table_name 
                    WHERE aktiv = %d
                    ORDER BY postleitzahl, name", $aktiv ) );
    }

    public static function find_sammelstelle_by_id($id ) {
        global $wpdb;

        $table_name = self::get_table_name();
        return $wpdb->get_row( $wpdb->prepare("
                SELECT id, name, adresse, postleitzahl, oeffnungszeiten, website, briefkasten, aktiv, hinweise,
                    X(location) as longitude, Y(location) as latitude
                    FROM $table_name 
                    WHERE id = %d", $id ) );
    }

}