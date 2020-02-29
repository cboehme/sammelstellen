<?php


class Sammelstellen {

    private static $initialised = false;

    public static function init() {
        if ( !self::$initialised ) {
            // TODO: perform initalisation
            self::$initialised = true;
        }
    }

    public static function view( $name, array $args = array() ) {

        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }

        $file = SAMMELSTELLEN__PLUGIN_DIR . 'views/'. $name . '.php';

        include( $file );
    }

}