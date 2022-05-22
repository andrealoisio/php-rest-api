<?php

require __DIR__ . '/vendor/autoload.php';

$client = new GuzzleHttp\Client;

$response = $client->request('GET', 'https://randomuser.me/api/');

echo $response->getStatuscode(), "\n";

echo $response->getHeader("content-type"), "\n";

echo substr($response->getBody(), 0, 200), "...\n";

