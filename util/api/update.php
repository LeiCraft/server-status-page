<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$hosts = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/hosts.php";

function runUpdate() {
    global $results;
    $results = [];

    $client = HttpClient::create();

    // Make asynchronous requests
    $promises = [
        'leicraftmc.de' => $client->request('GET', 'https://check-host.net/check-ping?host=leicraftmc.de&node=de4.node.check-host.net', ['headers' => ['Accept' => 'application/json']])->toArray(),
        'host03.leicraftmc.de' => $client->request('GET', 'https://check-host.net/check-ping?host=host03.leicraftmc.de&node=de4.node.check-host.net', ['headers' => ['Accept' => 'application/json']])->toArray(),
        'host02.leicraftmc.de' => $client->request('GET', 'https://check-host.net/check-ping?host=host02.leicraftmc.de&node=de4.node.check-host.net', ['headers' => ['Accept' => 'application/json']])->toArray(),
        'host04.leicraftmc.de' => $client->request('GET', 'https://check-host.net/check-ping?host=host04.leicraftmc.de&node=de4.node.check-host.net', ['headers' => ['Accept' => 'application/json']])->toArray(),
    ];

    // Wait for all requests to complete
    $responses = Symfony\Component\HttpClient\Promise\all($promises)->wait();

    // Access the results
    foreach ($responses as $fqdn => $response) {
        $results[$fqdn] = checkHost($fqdn, $response);
    }

    print_r($results); // Output the results for testing
}

function checkHost($fqdn, $initialResponse) {
    $initialResponseData = $initialResponse['response']->toArray();
    
    if (isset($initialResponseData['request_id'])) {
        sleep(5);

        // Make a second cURL request using the obtained request_id
        $checkResponse = makeCurlRequest('https://check-host.net/check-result/' . $initialResponseData['request_id']);

        // Extract the response times and calculate the average
        $responseTimes = $checkResponse['de4.node.check-host.net'][0];
        $responseTimesInMs = array_map(function ($item) {
            return round($item[1] * 1000, 2); // Convert seconds to milliseconds
        }, $responseTimes);

        $averageResponseTime = array_sum($responseTimesInMs) / count($responseTimesInMs);

        // Determine the status based on the conditions
        if (count(array_filter($responseTimes, function ($item) {
            return $item[0] !== 'OK';
        })) === 0) {
            $status = 'green';
        } elseif (count(array_filter($responseTimes, function ($item) {
            return $item[0] !== 'OK';
        })) <= 2) {
            $status = 'yellow';
        } else {
            $status = 'red';
        }

        return [
            "response_time" => $averageResponseTime,
            "status_code" => $status
        ];
    } else {
        return [
            "response_time" => 0,
            "status_code" => "no_data"
        ];
    }
}

function makeCurlRequest($url) {
    $headers = array(
        'Accept: application/json',
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        // Handle curl errors if needed
        echo 'Curl error: ' . curl_error($ch);
    }

    curl_close($ch);

    return json_decode($response, true);
}

runUpdate();
