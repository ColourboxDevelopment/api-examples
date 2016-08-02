<?php

require_once 'vendor/autoload.php';

$client = new GuzzleHttp\Client([
    'base_url' => 'https://api.colourbox.com',
    'defaults' => [
        'timeout' => 10
    ],
]);


$username = '****';
$password = '****';
$apiKey = '****';
$apiSecret = '****';

$timestamp = time();
$hmac = hash_hmac('sha1', "{$apiKey}:{$timestamp}", $apiSecret);

$response = $client->post('/authenticate/userpasshmac', ['body' => json_encode(['username' => $username, 'password' => $password, 'key' => $apiKey, 'ts' => $timestamp, 'hmac' => $hmac])]);
$jsonBody = json_decode($response->getBody(), true);

$token = $jsonBody['token'];

echo "This is the token obtained: {$token}\n";

$response = $client->get('/search', ['headers' => ['Authorization' => "CBX-SIMPLE-TOKEN Token={$token}"], 'query' => ['return_values' => 'thumbnail_url keywords', 'media_count' => 3]]);

echo "This is a search result:\n{$response->getBody()}\n";
