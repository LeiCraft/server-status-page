<?php

$hosts = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/hosts.php";

function runUpdate() {

    return checkHost("leicraftmc.de");

}

function checkHost($fqdn) {
    
    $initialResponse = makeCurlRequest("https://check-host.net/check-ping?host=$fqdn&node=de4.node.check-host.net");

    return $initialResponse;

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
