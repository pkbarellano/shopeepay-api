<?php

class SPProductionClient
{

    private function generateSignature($secret = "", $payload = [])
    {

        $json = json_encode($payload);

        $signature = hash_hmac('sha256', $json, $secret, true);

        return base64_encode($signature);
    }

    private function header($clientId = "", $secret = "", $payload = [])
    {

        $signature = $this->generateSignature($secret, $payload);

        $header = [
            'X-Airpay-ClientId: ' . $clientId,
            'X-Airpay-Req-H: ' . $signature
        ];

        return $header;
    }

    private function requestBody($url = "", $timeout = 0, $method = "POST", $payload = [], $curlHeader = [])
    {

        $body = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => $curlHeader
        );

        return $body;
    }

    private function curlExec($body = [], $jsonPayload = "")
    {

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt_array($curl, $body);

        $response = json_decode(curl_exec($curl));

        $responseInfo = curl_getinfo($curl);

        $error = curl_error($curl);

        curl_close($curl);

        $resp = [
            'status' => false,
            'debug_msg' => "Undefined",
            'request' => $jsonPayload,
            'response' => $response,
            'responseInfo' => $responseInfo,
            'responseError' => $error
        ];

        if ($responseInfo['http_code'] == 200) {

            if (is_object($response) && (property_exists($response, 'errcode') && property_exists($response, 'debug_msg')) && ($response->errcode == 0 && $response->debug_msg == "success")) {

                $resp = [
                    'status' => true,
                    'debug_msg' => "",
                    'request' => $jsonPayload,
                    'response' => $response,
                    'responseInfo' => $responseInfo,
                    'responseError' => $error
                ];
            }
        }

        return $resp;
    }

    public function sendRequest($url = "", $clientId = "", $secret = "", $payload = [], $timeout = 0, $method)
    {

        $curlHeader = $this->header($clientId, $secret, $payload);

        $curlBody = $this->requestBody($url, $timeout, $method, $payload, $curlHeader);

        $jsonPayload = [
            'header' => $curlHeader,
            'postFields' => $payload
        ];

        return $this->curlExec($curlBody, $jsonPayload);
    }
}
