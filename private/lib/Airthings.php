<?php

class Airthings_Client {
    protected $client_id = '';
    protected $client_secret = '';
    protected $token = '';

    public function __construct($client_id, $client_secret) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public function get_token() {
        $data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials',
            'scope' => ['read:device:current_values']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts-api.airthings.com/v1/token");
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);


        // TODO: Set token
        // $this->token = "";
        return $response;
    }

    public function get_samples($token, $serial_number) {
        $url = "https://ext-api.airthings.com/v1/devices/" . $serial_number . "/latest-samples";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}

