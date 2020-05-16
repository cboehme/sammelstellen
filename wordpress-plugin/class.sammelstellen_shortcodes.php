<?php


class Sammelstellen_Shortcodes
{

    private static $initialised = false;
    private static $map_id = 0;
    private static $list_id = 0;

    public static function init() {
        if ( !self::$initialised ) {
            self::add_shortcodes();
            self::$initialised = true;
        }
    }

    private static function add_shortcodes() {
        add_shortcode( 'sammelstellen-map', array( 'Sammelstellen_Shortcodes', 'map_shortcode' ) );
        add_shortcode( 'sammelstellen-list', array( 'Sammelstellen_Shortcodes', 'list_shortcode' ) );
        add_shortcode( 'sammelstellen', array( 'Sammelstellen_Shortcodes', 'sammelstellen_shortcode' ) );
    }

    public static function map_shortcode( $atts = [], $content = null ) {
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'sammelstellen.css' );
        wp_enqueue_script( 'mustache.js' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'ResizeObserver.js' );
        wp_enqueue_script( 'sammelstellen.js' );

        wp_localize_script( 'sammelstellen.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ),
            'listitemTemplate' => get_option( 'sammelstellen_listitem_template' ),
            'popupTemplate' => get_option( 'sammelstellen_popup_template' ) ) );

        $mapId = self::get_map_id();

        return "<div id='$mapId' class='map'></div>
        <script>
            document.addEventListener('DOMContentLoaded', () => initMap('$mapId'));
        </script>";
    }

    private static function get_map_id() {
        return "sammelstellenMap" . self::$map_id++;
    }

    public static function list_shortcode( $atts = [], $content = null ) {
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_style( 'sammelstellen.css' );
        wp_enqueue_script( 'mustache.js' );
        wp_enqueue_script( 'mapbox-gl.js' );
        wp_enqueue_script( 'ResizeObserver.js' );
        wp_enqueue_script( 'sammelstellen.js' );

        wp_localize_script( 'sammelstellen.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ),
            'listitemTemplate' => get_option( 'sammelstellen_listitem_template' ),
            'popupTemplate' => get_option( 'sammelstellen_popup_template' ) ) );

        $listId = self::get_list_id();

        return "<ol id='$listId'></ol>
        <script>
            document.addEventListener('DOMContentLoaded', () => initList('$listId'));
        </script>";
    }

    private static function get_list_id() {
        return "sammelstellenList" . self::$list_id++;
    }

    public function sammelstellen_shortcode( $atts = [], $content = null ) {
        $defaultedAtts = shortcode_atts( array(
            'start-push-right' => "10000000",
            'compact-map' => "(max-width: 0)",
        ), $atts );

        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_script( 'frontend.js' );
        wp_localize_script( 'frontend.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ),
            'startPushRight' => $defaultedAtts["start-push-right"],
            'compactMap' => $defaultedAtts["compact-map"] ) );

        $mapId = self::get_map_id();

        return "
            <div id='$mapId'class='sammelstellen-container'>
                Dein Browser ist leider zu alt, um die Sammelstellenkarte anzuzeigen.<br/>
                Bitte verwende einen aktuelleren Browser.
            </div>
            <script defer>
                document.addEventListener('DOMContentLoaded', function() {
                    embedSammelstellen(
                        document.getElementById('$mapId'),
                        '/wp-json/sammelstellen/v1/sammelstellen',
                        Config.mapSource,
                        Config.startPushRight,
                        Config.compactMap
                        );
                    document.getElementById('$mapId').innerText = '';
                });
            </script>";
    }
}