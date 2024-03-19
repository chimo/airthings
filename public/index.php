<?php

require '../private/config.php';
require '../private/lib/Airthings.php';

// Configs
if (
    !isset($config['secret']) ||
    !isset($config['airthings_client_id']) ||
    !isset($config['airthings_client_secret']) ||
    !isset($config['airthings_serial_number'])
) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    exit(0);
}

// Secret verification
if (!isset($_SERVER['HTTP_SECRET']) || $_SERVER['HTTP_SECRET'] !== $config['secret']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    exit(0);
}

// Dispatch request
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        get();
        break;
    default:
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        break;
}

function get() {
    global $config;

    $client_id = $config['airthings_client_id'];
    $client_secret = $config['airthings_client_secret'];
    $serial_number = $config['airthings_serial_number'];

    $Airthings_Client = new Airthings_Client($client_id, $client_secret);

    $token = $Airthings_Client->get_token();
    $samples = $Airthings_Client->get_samples($serial_number);

    header('Content-Type: application/json; charset=utf-8');
    echo $samples;
}

