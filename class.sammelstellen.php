<?php


class Sammelstellen {

    private static $initialised = false;

    public static function init() {
        if ( !self::$initialised ) {
            // TODO: perform initalisation
            self::$initialised = true;
        }
    }

    public static function activate_plugin() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'sammelstellen';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                name tinytext NOT NULL,
                adresse mediumtext NOT NULL,
                oeffnungszeiten tinytext DEFAULT '' NOT NULL,
                hinweise mediumtext DEFAULT '' NOT NULL,
                aktiv boolean DEFAULT false NOT NULL,
                location point NOT NULL,
                PRIMARY KEY  (id)                
                ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
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

}