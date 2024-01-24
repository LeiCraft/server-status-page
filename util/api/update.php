<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Spatie\Async\Pool;

$hosts = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/hosts.php";

function runUpdate() {
    global $hosts;
    $results = [];
    
    // Create a new Pool
    $pool = Pool::create();

    $hosts = [
        "leicraftmc.de" => [],
        "host03.leicraftmc.de" => [],
        "host02.leicraftmc.de" => [],
        "host04.leicraftmc.de" => []
    ];

    // Use a for loop to add tasks to the pool for initial check
    foreach ($hosts as $fqdn => &$host_data) {
        $pool[] = async(function () use ($fqdn) {
            $initialResponse = makeCurlRequest("https://check-host.net/check-ping?host=$fqdn&node=de4.node.check-host.net");
            return $initialResponse;
        })->then(function ($output) use ($fqdn, $hosts) {
            global $hosts;
            $hosts[$fqdn]["initialResponse"] = $output;
        });
    }

    // Wait for all tasks in the initial check pool to complete
    await($pool);

    // Sleep for 5 seconds globally
    sleep(5);

    // Create a new Pool for the second check
    $secondPool = Pool::create();

    // Use a for loop to add tasks to the second pool for the second check
    foreach ($hosts as $fqdn => &$host_data) {
        $secondPool[] = async(function () use ($host_data) {
            return runSecondCheck($host_data['initialResponse']);
        })->then(function ($output) use ($fqdn, $hosts) {
            global $hosts;
            $hosts[$fqdn] = $output;
        });
    }

    // Wait for all tasks in the second check pool to complete
    await($secondPool);

    return $hosts;
}

function runSecondCheck($initialResponse) {
    if (isset($initialResponse['request_id'])) {
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
?>
