<?php


class Sammelstellen_REST_API {

    public static function init() {

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
                    'coordinate' => array($sammelstelle->longitude, $sammelstelle->latitude)
                ),
                'properties' => array(
                    'name' => $sammelstelle->name,
                    'adresse' => $sammelstelle->adresse,
                    'oeffnungszeiten' => $sammelstelle->oeffnungszeiten,
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
