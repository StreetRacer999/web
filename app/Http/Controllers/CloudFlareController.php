<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;

class CloudFlareController extends Controller
{
    protected const ENDPOINT = 'https://api.cloudflare.com/client/v4/';
    private const TOKEN = 'D4bad2oAawEWEOoMECdxdHnkvwz206Q71n7VE6in';

    public static function generateCSR($domain, $unit, $email)
    {
        $subject = [
            'commonName' => $domain,
            'organizationName' => 'RustSCP',
            'organizationalUnitName' => $unit,
            'localityName' => 'Moscow',
            'stateOrProvinceName' => 'Moscow',
            'countryName' => 'RU',
            'emailAddress' => $email
        ];
       
        $private_key = openssl_pkey_new(['private_key_type' => OPENSSL_KEYTYPE_RSA, 'private_key_bits' => 2048]);
        $csr_resource = openssl_csr_new($subject, $private_key, ['digest_alg' => 'sha256']);

        openssl_csr_export($csr_resource, $csr_string);
        openssl_pkey_export($private_key, $private_key_string);

        return (object) ['csr' => $csr_string, 'key' => $private_key_string];
    }

    public static function sendRequest($method = 'POST', $endpoint, $data, $token = null)
    {
        if ($token == null) {
            $authheader = ['Authorization', 'Bearer ' . self::TOKEN];
        } else {
            $authheader = ['X-Auth-User-Service-Key', $token];
        }

        try {
            $client = new Client;

            $result = $client->request($method, $endpoint, [
                'headers' => [
                    $authheader[0] => $authheader[1],
                ],
                'body' => json_encode($data)
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return json_decode($e->getResponse()->getBody());
        }

        return json_decode($result->getBody());
    }

    public static function addDNSRecord($type = 'A', $name, $content, $proxied = true)
    {
        $endpoint = self::ENDPOINT . 'zones/076761477f7aab83da8426f6d639c9aa/dns_records';

        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied
        ];

        $result = self::sendRequest('POST', $endpoint, $data);

        return ['success' => $result->success, 'errors' => $result->errors];
    }

    public static function createOriginCA($hostname, $validity = 5475)
    {
        $endpoint = self::ENDPOINT . 'certificates';

        $ssl = self::generateCSR($hostname, 'Servers', 'server@rustscp.net');

        $data = [
            'hostnames' => [$hostname],
            'requested_validity' => $validity,
            'request_type' => 'origin-rsa',
            'csr' => $ssl->csr,
        ];

        $result = self::sendRequest('POST', $endpoint, $data, 'v1.0-b9ce68cc1260be8a8bb79cee-48ec3937ff7f85bacfa2e3e9d9204cb91e543026eb9a49e41a1035cb2ba63b80e1a3c4bd3e0e875a496bad88c90dde289d163115a5b8ed0682256a50d7ae90f601e95f98a6ad537c');

        return (object) ['cert' => $result->result->certificate, 'key' => $ssl->key];
    }
}
