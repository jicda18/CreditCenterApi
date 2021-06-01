<?php

namespace App\Trait;

use GuzzleHttp\Client;

trait IpGeo
{
    protected function getGeoFromIp(string $ip)
    {
        $client = new Client(['base_uri' => "http://ip-api.com/json/"]);
        $response = $client->request('GET', $ip, ['query' => [
            'lang' => 'es',
            'fields' => 'status,message,continent,country,countryCode,region,regionName,city,zip,timezone,isp'
        ]]);

        $code = $response->getStatusCode();
        $body = json_decode((string) $response->getBody());

        if ($code !== 200 || $body->status !== 'success') {
            return false;
        }

        return $body;
    }
}
