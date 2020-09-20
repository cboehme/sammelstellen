<?php


class Sammelstellen_REST_API {

    private static $initialised = false;

    public static function init() {
        if (!self::$initialised) {
            self::register_routes();
            self::$initialised = true;
        }
    }

    private static function register_routes() {
        register_rest_route( 'sammelstellen/v1', '/sammelstellen', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( 'Sammelstellen_REST_API', 'get_sammelstellen' ),
            'permission_callback' => array( 'Sammelstellen_REST_API', 'permitted_to_get_sammelstellen' )
        ) );
    }

    public static function permitted_to_get_sammelstellen( $request ) {
        $aktiv = rest_sanitize_boolean( $request->get_param( "aktiv" ) );
        if ($aktiv === true || current_user_can('edit_posts' )) {
            return true;
        }
        return new WP_Error('not_allowed', 'Not allowed to request non-active Sammelstellen',
            array( 'status' => 403 ) );
    }

    public static function get_sammelstellen( $request ) {
        $sammelstellen = self::find_sammelstellen( $request->get_param( "aktiv" ) );
        $sammelstellen_geojson = array();
        foreach ( $sammelstellen as $sammelstelle ) {
            $sammelstellen_geojson[] = self::map_sammelstelle_to_geojson( $sammelstelle );
        };
        $geo_json = array(
            'type' => 'FeatureCollection',
            'features' => $sammelstellen_geojson
        );
        return rest_ensure_response( $geo_json );
    }

    private static function map_sammelstelle_to_geojson( $sammelstelle ) {
        $sammlung_beendet = get_option( 'sammelstellen_sammlung_beendet' ) === "true";
        return array(
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => array(floatval($sammelstelle->longitude), floatval($sammelstelle->latitude))
            ),
            'properties' => (
                $sammlung_beendet
                    ? self::map_sammelstellen_archiv_properties( $sammelstelle )
                    : self::map_sammelstellen_properties( $sammelstelle )
            )
        );
    }

    private static function find_sammelstellen( $aktiv_param ) {
        if ($aktiv_param === null) {
            return Sammelstellen::find_all_sammelstellen();
        }
        $sammlung_beendet = get_option( 'sammelstellen_sammlung_beendet' ) === "true";
        $aktiv = rest_sanitize_boolean( $aktiv_param );
        if ($sammlung_beendet) {
            return Sammelstellen::find_sammelstellen_by_aktiv_without_briefkaesten( $aktiv );
        }
        return Sammelstellen::find_sammelstellen_by_aktiv( $aktiv );
    }

    private static function map_sammelstellen_properties( $sammelstelle ) {
        return array(
            'id' => $sammelstelle->id,
            'name' => $sammelstelle->name,
            'adresse' => $sammelstelle->adresse,
            'postleitzahl' => $sammelstelle->postleitzahl,
            'oeffnungszeiten' => $sammelstelle->oeffnungszeiten,
            'website' => $sammelstelle->website,
            'briefkasten' => $sammelstelle->briefkasten == "1",
            'hinweise' => $sammelstelle->hinweise,
            'aktiv' => $sammelstelle->aktiv == "1"
        );
    }

    private static function map_sammelstellen_archiv_properties( $sammelstelle ) {
        return array(
            'id' => $sammelstelle->id,
            'name' => $sammelstelle->name,
            'postleitzahl' => $sammelstelle->postleitzahl,
            'website' => $sammelstelle->website,
            'briefkasten' => $sammelstelle->briefkasten == "1",
            'aktiv' => $sammelstelle->aktiv == "1"
        );
    }

}
