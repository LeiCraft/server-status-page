<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$config = require_once $_SERVER['DOCUMENT_ROOT'] . "/util/config/config.php";

$headers = getallheaders();

header('Content-Type: application/json');

if (isset($headers['Authorization'])) {

    $authorizationHeader = $headers['Authorization'];

    if (strpos($authorizationHeader, 'Bearer') === 0) {

        $token = trim(substr($authorizationHeader, 7));

        if ($token === $config['token']) {
            
            require_once $_SERVER['DOCUMENT_ROOT'] . "/util/api/update.php";

            //runUpdate();

            http_response_code(200);
            //echo json_encode(['status' => 'success', 'message' => 'Bearer Token is valid']);

            echo json_encode(runUpdate());

        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid Bearer Token']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid Authorization header format']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Authorization header not present']);
    exit;
}

?>