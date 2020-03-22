<?php


class Sammelstellen_Shortcodes
{

    private static $initialised = false;

    public static function init() {

        if ( !self::$initialised ) {
            add_shortcode( 'sammelstellen-map', array( 'Sammelstellen_Shortcodes', 'map_shortcode' ) );
            self::$initialised = true;
        }
    }

    public static function map_shortcode( $atts = [], $content = null ) {

        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'map.css' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'map.js' );

        return '<div id="map" class="map"></div>';
    }

}