<?php

// Rough draft

class Airthings_Client {
    protected $client_id = '';
    protected $client_secret = '';
    protected $token = '';
    protected $token_expiry = '';

    public function __construct($client_id, $client_secret) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;

        $this->token = apcu_fetch('at-token') ?: null;
        $this->token_expiry = apcu_fetch('at-token_expiry') ?: null;
    }

    private function _request($url, $method='get', $headers=[], $payload=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
        }

        if (!is_null($payload)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function _refresh_token() {
        $data = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials',
            'scope' => ['read:device:current_values']
        ];

        $headers = [
            'Content-Type: application/json'
        ];

        $response = $this->_request(
            'https://accounts-api.airthings.com/v1/token',
            'post',
            $headers,
            $data
        );

        $response_obj = json_decode($response);

        // Store
        $this->token = $response_obj->access_token;
        $this->token_expiry = time() + $response_obj->expires_in;

        // Cache
        apcu_store('at-token', $this->token);
        apcu_store('at-token_expiry', $this->token_expiry);
    }

    public function get_token() {
        if (
            is_null($this->token) ||
            is_null($this->token_expiry) ||
            time() > $this->token_expiry
        ) {
            $this->_refresh_token();
        }

        return $this->token;
    }

    public function get_samples($serial_number) {
        $url = 'https://ext-api.airthings.com/v1/devices/' . $serial_number .
            '/latest-samples';

        // Get fresh token if expired
        $token = $this->get_token();

        $headers = [
            'Authorization: Bearer ' . $token
        ];

        $response = $this->_request(
            $url,
            'get',
            $headers
        );

        $response_obj = json_decode($response);
        error_log(print_r($response, true));
        $data = $response_obj->data;
        $response = json_encode($data);

        return $response;
    }
}

