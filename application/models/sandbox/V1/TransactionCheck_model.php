<?php

class TransactionCheck_model extends MY_Model
{

    function createRequestTransactionCheck($referenceID = "", $header = [], $postFields = [], $headerJson = "", $bodyJson = "")
    {

        $this->_transStart('db');

        $this->db->set([
            'pGReferenceID' => $referenceID,
            'headerXAirpayClientId' => (isset($header[0])) ? $header[0] : '',
            'headerXAirpayReqH' => (isset($header[1])) ? $header[1] : '',
            'requestID' => (isset($postFields['request_id'])) ? $postFields['request_id'] : '',
            'referenceID' => (isset($postFields['reference_id'])) ? $postFields['reference_id'] : '',
            'transactionType' => (isset($postFields['transaction_type'])) ? $postFields['transaction_type'] : '',
            'merchantExtID' => (isset($postFields['merchant_ext_id'])) ? $postFields['merchant_ext_id'] : '',
            'storeExtID' => (isset($postFields['store_ext_id'])) ? $postFields['store_ext_id'] : '',
            'amount' => (isset($postfields['amount'])) ? $postfields['amount'] : '',
            'headerJson' => $headerJson,
            'bodyJson' => $bodyJson
        ]);

        $this->db->insert('transactionCheckRequests');

        return $this->_transEnd('db');
    }

    function createResponseTransactionCheck($param = [])
    {

        $this->_transStart('db');

        $this->db->set($param);

        $this->db->insert('transactionCheckResponses');

        return $this->_transEnd('db');
    }
}
