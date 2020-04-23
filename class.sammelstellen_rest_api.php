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
            'callback' => array( 'Sammelstellen_REST_API', 'get_sammelstellen' )
        ) );
    }

    public static function get_sammelstellen( $request ) {
        $sammelstellen = array();
        foreach ( Sammelstellen::find_sammelstellen_by_aktiv( true ) as $sammelstelle ) {
            $sammelstellen[] = array(
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => array(floatval($sammelstelle->longitude), floatval($sammelstelle->latitude))
                ),
                'properties' => array(
                    'id' => $sammelstelle->id,
                    'name' => $sammelstelle->name,
                    'adresse' => $sammelstelle->adresse,
                    'oeffnungszeiten' => $sammelstelle->oeffnungszeiten,
                    'website' => $sammelstelle->website,
                    'briefkasten' => $sammelstelle->briefkasten,
                    'hinweise' => $sammelstelle->hinweise
                )
            );
        };
        $geo_json = array(
            'type' => 'FeatureCollection',
            'features' => $sammelstellen
        );
        return rest_ensure_response( $geo_json );
    }

}
