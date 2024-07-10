<?php

namespace App\Services\PostalCode;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PostalCodeService
{
    public function geoCodeAction($pc)
    {
        $key = config('app.google_api_key');
        $result = null;
        $httpClient = new Client();
        $cp = $pc;
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?key=' . $key . '&components=country:ES|postal_code:' . $cp;
        $headers = ['Content-Type' => 'application/json'];
        $env = [
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            ],
            'headers' => $headers,
            'debug' => false,
        ];

        try {
            $response = $httpClient->request('POST', $url, $env);
            $body = json_decode($response->getBody(), true);

            if (isset($body['status'])) {
                if ($body['status'] = 'OK' && !empty($body['results'])) {
                    $results = $body['results'][0];
                    $address_components = $results['address_components'];
                    $formatted_address = $results['formatted_address'];
                    $location = $results['geometry']['location'];
                    $lat = $location['lat'];
                    $lng = $location['lng'];
                    foreach ($address_components as $address_component) {
                        if (in_array('locality', $address_component['types'])) {
                            $localidad = $address_component['long_name'];
                        }
                        if (in_array('administrative_area_level_2', $address_component['types'])) {
                            $provincia = $address_component['long_name'];
                        }
                    }
                    if ($provincia !== '' && $lat !== '' && $lng !== '') {
                        $result['isValid'] = true;
                        $result['formatted_address'] = $formatted_address;
                        $result['location'] = $location;
                        $result['lat'] = $lat;
                        $result['lng'] = $lng;
                        $result['localidad'] = $localidad;
                        $result['provincia'] = $provincia;
                    } else {
                        if ($provincia !== '') {
                            $result['error'] = 'No se ha encontrado provincia';
                        } else {
                            $result['error'] = 'No se ha encontrado lat o lng';
                        }
                        $result['results'] = $results;
                    }
                }
            }
        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $result['error'] = \GuzzleHttp\json_decode($responseBodyAsString);
            $result['det'] = $responseBodyAsString;
            $result['env'] = $env;
            $result['url'] = $url;
        }
        return $result;
    }
}
