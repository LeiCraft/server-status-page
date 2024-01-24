<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Spatie\Async\Pool;
use Spatie\Async\Task;

$hosts = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/hosts.php";

function runUpdate() {
    global $results;
    $results = [];

    // Create a new Pool
    $pool = Pool::create();

    $hosts = ["leicraftmc.de", "host03.leicraftmc.de", "host02.leicraftmc.de", "host04.leicraftmc.de"];

    // Use a for loop to add tasks to the pool
    for ($i = 0; $i < count($hosts); $i++) {
        $pool->add(function () use ($hosts, $i) {
            return checkHost($hosts[$i]);
        })->then(function ($output) use ($results, $hosts, $i) {
            // Handle success
            global $results;
            $results[] = $output;
        });
    }

    // Wait for all tasks to complete
    $pool->wait();

    return $results;
}

function checkHost($fqdn) {
    $initialResponse = makeCurlRequest("https://check-host.net/check-ping?host=$fqdn&node=de4.node.check-host.net");

    if (isset($initialResponse['request_id'])) {
        usleep(5);

        // Make a second cURL request using the obtained request_id
        $checkResponse = makeCurlRequest('https://check-host.net/check-result/' . $initialResponse['request_id']);

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