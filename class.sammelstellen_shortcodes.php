<?php


class Sammelstellen_Shortcodes
{

    private static $initialised = false;
    private static $mapId = 0;
    private static $listId = 0;

    public static function init() {
        if ( !self::$initialised ) {
            self::add_shortcodes();
            self::$initialised = true;
        }
    }

    private static function add_shortcodes() {
        add_shortcode( 'sammelstellen-map', array( 'Sammelstellen_Shortcodes', 'map_shortcode' ) );
        add_shortcode( 'sammelstellen-list', array( 'Sammelstellen_Shortcodes', 'list_shortcode' ) );
    }

    public static function map_shortcode( $atts = [], $content = null ) {
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'sammelstellen.css' );
        wp_enqueue_script( 'mustache.js' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'sammelstellen.js' );

        wp_localize_script( 'sammelstellen.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ) ) );

        $mapId = self::getMapId();
        $markerTemplate = preg_replace("/(\n\r)|\n|\r/", '\\\n', addslashes( $content ));

        return "<div id='$mapId' class='map'></div>
        <script>
            document.addEventListener('DOMContentLoaded', () => initMap('$mapId', '$markerTemplate'));
        </script>";
    }

    private static function getMapId() {
        return "sammelstellenMap" . self::$mapId++;
    }

    public static function list_shortcode( $atts = [], $content = null ) {
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'sammelstellen.css' );
        wp_enqueue_script( 'mustache.js' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'sammelstellen.js' );

        wp_localize_script( 'sammelstellen.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ) ) );

        $itemTemplate = preg_replace("/(\n\r)|\n|\r/", '\\\n', addslashes( $content ));
        $listId = self::getListId();

        return "<ol id='$listId'></ol>
        <script>
            document.addEventListener('DOMContentLoaded', () => initList('$listId', '$itemTemplate'));
        </script>";
    }

    private static function getListId() {
        return "sammelstellenList" . self::$listId++;
    }

}