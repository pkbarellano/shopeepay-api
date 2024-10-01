<?php

class Payment_model extends MY_Model
{

    function createRequestPayment($referenceID = "", $storeCode = "", $header = [], $postFields = [], $headerJson = "", $bodyJson = "")
    {

        $this->_transStart('db');

        $this->db->set([
            'pGReferenceID' => $referenceID,
            'pGStoreCode' => $storeCode,
            'headerXAirpayClientId' => (isset($header[0])) ? $header[0] : '',
            'headerXAirpayReqH' => (isset($header[1])) ? $header[1] : '',
            'requestID' => (isset($postFields['request_id'])) ? $postFields['request_id'] : '',
            'amount' => (isset($postFields['amount'])) ? $postFields['amount'] : '',
            'merchantExtID' => (isset($postFields['merchant_ext_id'])) ? $postFields['merchant_ext_id'] : '',
            'storeExtID' => (isset($postFields['store_ext_id'])) ? $postFields['store_ext_id'] : '',
            'paymentCode' => (isset($postFields['payment_code'])) ? $postFields['payment_code'] : '',
            'paymentReferenceID' => (isset($postFields['payment_reference_id'])) ? $postFields['payment_reference_id'] : '',
            'terminalID' => (isset($postFields['terminal_id'])) ? $postFields['terminal_id'] : '',
            'terminalIP' => (isset($postFields['terminal_ip'])) ? $postFields['terminal_ip'] : '',
            'currency' => (isset($postFields['currency'])) ? $postFields['currency'] : '',
            'additionalInfo' => (isset($postFields['additional_info'])) ? $postFields['additional_info'] : '',
            'headerJson' => $headerJson,
            'bodyJson' => $bodyJson
        ]);

        $this->db->insert('paymentRequests');

        return $this->_transEnd('db');
    }

    function createResponsePayment($param = [])
    {

        $this->_transStart('db');

        $this->db->set($param);

        $this->db->insert('paymentResponses');

        return $this->_transEnd('db');
    }
}
