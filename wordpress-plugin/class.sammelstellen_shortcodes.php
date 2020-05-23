<?php


class Sammelstellen_Shortcodes
{

    private static $initialised = false;
    private static $map_id = 0;

    public static function init() {
        if ( !self::$initialised ) {
            self::add_shortcodes();
            self::$initialised = true;
        }
    }

    private static function add_shortcodes() {
        add_shortcode( 'sammelstellen-liste', array( 'Sammelstellen_Shortcodes', 'sammelstellen_liste_shortcode' ) );
        add_shortcode( 'sammelstellen', array( 'Sammelstellen_Shortcodes', 'sammelstellen_shortcode' ) );
    }

    public function sammelstellen_liste( $atts = [], $content = null ) {

        $last_plz = "";
        $output = "";
        foreach (Sammelstellen::find_sammelstellen_by_aktiv( true ) as $sammelstelle ) {
            if ($last_plz !== $sammelstelle->postleitzahl) {
                $output .= "<h1 class='sammelstellen-plz-bereich'>Postleitzahl $sammelstelle->postleitzahl</h1>";
                $last_plz = $sammelstelle->postleitzahl;
            }
            $output .= "<article class='sammelstellen-sammelstelle'>";
            if ($sammelstelle->briefkasten) {
                $output .= "
                    <h2>Radentscheid-Briefkasten</h2>
                    <p class='sammelstellen-info-briefkasten'>Privater Briefkasten als Einwurfstelle für Unterschriftenlisten</p>
                    <ul>
                        <li>" . esc_html( $sammelstelle->name ) . "</li>
                        <li>" . esc_html( $sammelstelle->adresse ) . "</li>";
            } else {
                $output .= "<h2>" . esc_html( $sammelstelle->name ) . "</h2>
                <ul>
                    <li>" . esc_html( $sammelstelle->adresse ) . "</li>";
            }
            if ( $sammelstelle->oeffnungszeiten ) {
                $output .= "<li>Öffnungszeiten: " . esc_html( $sammelstelle->oeffnungszeiten ) . "</li>";
            }
            if ( $sammelstelle->hinweise ) {
                $output .= "<li>" . esc_html( $sammelstelle->hinweise ) . "</li>";
            }
            if ( $sammelstelle->website ) {
                $output .= "<li><a href='" . esc_attr( $sammelstelle->website ) . "' 
                           target='_blank'
                           rel='noopener noreferer'>Website der Sammelstelle</a></li>";
            }
            $output .= "
                </ul></article>";
        }
        return $output;
    }

    public function sammelstellen_shortcode( $atts = [], $content = null ) {
        $defaultedAtts = shortcode_atts( array(
            'start-push-right' => "10000000",
            'compact-map' => "(max-width: 0)",
            'fallback-page' => ""
        ), $atts );

        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_script( 'frontend.js' );
        wp_localize_script( 'frontend.js', 'Config', array(
            'mapSource' => get_option( 'sammelstellen_map_source' ),
            'startPushRight' => $defaultedAtts["start-push-right"],
            'compactMap' => $defaultedAtts["compact-map"],
            'wp_nonce' => wp_create_nonce('wp_rest' ) ) );

        $mapId = self::get_map_id();

        return "
            <div id='$mapId' class='sammelstellen'>
                Dein Browser ist leider zu alt, um die Sammelstellenkarte anzuzeigen.<br/>
                Du kannst aber die <a href='" . get_permalink($defaultedAtts["fallback-page"]) . "'>Sammelstellenliste</a> benutzen.
            </div>
            <script defer>
                document.addEventListener('DOMContentLoaded', function() {
                    embedSammelstellen(
                        document.getElementById('$mapId'),
                        '/wp-json/sammelstellen/v1/sammelstellen?aktiv=true',
                        Config.mapSource,
                        Config.startPushRight,
                        Config.compactMap
                        );
                    document.getElementById('$mapId').innerText = '';
                });
            </script>";
    }

    private static function get_map_id() {
        return "sammelstellen-" . self::$map_id++;
    }

}