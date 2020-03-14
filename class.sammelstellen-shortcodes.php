<?php


class Sammelstellen_Shortcodes
{

    public static function init() {

        add_shortcode( 'sammelstellen-map', array( 'Sammelstellen_Shortcodes', 'map_shortcode' ) );

        wp_register_script( 'ol.js', plugin_dir_url( __FILE__ ) . '_inc/ol.js' );
        wp_register_script( 'map.js', plugin_dir_url( __FILE__ ) . '_inc/map.js' );

        wp_register_style( 'ol.css', plugin_dir_url( __FILE__ ) . '_inc/ol.css' );
        wp_register_style( 'map.css', plugin_dir_url( __FILE__ ) . '_inc/map.css' );
    }

    public static function map_shortcode( $atts = [], $content = null ) {

        wp_enqueue_style( 'ol.css' );
        wp_enqueue_style( 'map.css' );
        wp_enqueue_script( 'ol.js' );
        wp_enqueue_script( 'map.js' );

        return '<div id="map" class="map"></div><div id="popup" class="Popup"></div>';
    }

}