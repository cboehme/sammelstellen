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

    public function sammelstellen_liste_shortcode( $atts = [], $content = null ) {
        $sammlung_beendet = get_option( 'sammelstellen_sammlung_beendet' ) === "true";
        $last_plz = "";
        $output = "";
        if ( $sammlung_beendet ) {
            $sammelstellen = Sammelstellen::find_sammelstellen_by_aktiv_without_briefkaesten( true );
        } else {
            $sammelstellen = Sammelstellen::find_sammelstellen_by_aktiv( true );
        }
        foreach ($sammelstellen as $sammelstelle ) {
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
                        <li>" . esc_html( $sammelstelle->name ) . "</li>";
            } else {
                $output .= "<h2>" . esc_html( $sammelstelle->name ) . "</h2>
                <ul>";
            }
            if ( !$sammlung_beendet ) {
                $output .= "<li>" . esc_html($sammelstelle->adresse) . "</li>";
                if ($sammelstelle->oeffnungszeiten) {
                    $output .= "<li>Öffnungszeiten: " . esc_html($sammelstelle->oeffnungszeiten) . "</li>";
                }
                if ($sammelstelle->hinweise) {
                    $output .= "<li>" . esc_html($sammelstelle->hinweise) . "</li>";
                }
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
            'aktiv' => "true",
            'start-push-right' => "10000000",
            'compact-map' => "(max-width: 0)",
            'fallback-page' => ""
        ), $atts );

        if ($defaultedAtts["aktiv"] === "") {
            $apiUrl = "/wp-json/sammelstellen/v1/sammelstellen";
        } else {
            $apiUrl = "/wp-json/sammelstellen/v1/sammelstellen?aktiv=" . $defaultedAtts["aktiv"];
        }
        wp_enqueue_style( 'mapbox-gl.css' );
        wp_enqueue_script( 'frontend.js' );
        wp_localize_script( 'frontend.js', 'Config', array(
            'src' => $apiUrl,
            'mapSource' => get_option( 'sammelstellen_map_source' ),
            'startPushRight' => $defaultedAtts["start-push-right"],
            'compactMap' => $defaultedAtts["compact-map"],
            'wp_nonce' => wp_create_nonce('wp_rest' ) ) );

        $mapId = self::get_map_id();

        return "
            <div id='$mapId' class='sammelstellen'>
                Dein Browser kann die Sammelstellenkarte leider nicht anzeigen, da er zu alt ist.<br/>
                Du kannst aber die <a href='" . get_permalink($defaultedAtts["fallback-page"]) . "'>Sammelstellenliste</a> benutzen.
            </div>
            <script defer>
                document.addEventListener('DOMContentLoaded', function() {
                    embedSammelstellen(
                        document.getElementById('$mapId'),
                        Config.src,
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