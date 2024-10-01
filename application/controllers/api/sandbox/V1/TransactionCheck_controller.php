<?php

class TransactionCheck_controller extends MY_Controller
{

    private $client;

    function __construct()
    {

        parent::__construct();

        $this->client = new SPSandboxClient();

        $this->load->model('sandbox/V1/TransactionCheck_model');
    }

    private function _SPClientTransactionCheck($requestID = "", $transactionType = 13, $payload = [])
    {

        $body = [
            'request_id' => (string)$requestID,
            'amount' => (float)$payload['amount'] * 100,
            'transaction_type' => $transactionType,
            'merchant_ext_id' => (string)$this->defaultParameterSandbox->merchantExtId,
            'store_ext_id' => (string)$this->defaultParameterSandbox->storeExtId,
            'reference_id' => (string)$payload['paymentReferenceID'],
        ];

        return $this->client->sendRequest(
            $this->appConfigSandbox->transactionCheckURL,
            $this->appConfigSandbox->clientId,
            $this->appConfigSandbox->secret,
            $body,
            0,
            'POST'
        );
    }

    private function _createRequestTransactionCheck($referenceID = "", $header = [], $postFields = [])
    {

        if ($this->TransactionCheck_model->createRequestTransactionCheck($referenceID, $header, $postFields, json_encode($header), json_encode($postFields)) === false) {

            $this->_response(0, "", "Warning: Query failed saving transaction check request.");

            $this->responseExit();
        }
    }

    private function _createResponseTransactionCheck($referenceID = "", $payload = [], $status = "")
    {

        $body = [
            'pGReferenceID' => $referenceID,
            'stat' => $status,
            'json' => json_encode($payload),
            'requestID' => (is_object($payload) && property_exists($payload, 'request_id')) ? $payload->request_id : '',
            'errCode' => (is_object($payload) && property_exists($payload, 'errcode')) ? $payload->errcode : '',
            'debugMsg' => (is_object($payload) && property_exists($payload, 'debug_msg')) ? $payload->debug_msg : '',
            'paymentMethod' => (is_object($payload) && property_exists($payload, 'payment_method')) ? $payload->payment_method : '',
        ];

        if (is_object($payload) && property_exists($payload, 'transaction')) {

            if (is_object($payload->transaction)) {

                $transaction = $payload->transaction;

                $body = array_merge($body, [
                    'transaction_referenceID' => (property_exists($transaction, 'reference_id')) ? $transaction->reference_id : '',
                    'transaction_amount' => (property_exists($transaction, 'amount')) ? $transaction->amount : '',
                    'transaction_transactionSN' => (property_exists($transaction, 'transaction_sn')) ? $transaction->transaction_sn : '',
                    'transaction_status' => (property_exists($transaction, 'status')) ? $transaction->status : '',
                    'transaction_transactionType' => (property_exists($transaction, 'transaction_type')) ? $transaction->transaction_type : '',
                    'transaction_createTime' => (property_exists($transaction, 'create_time')) ? $transaction->create_time : '',
                    'transaction_updateTime' => (property_exists($transaction, 'update_time')) ? $transaction->update_time : '',
                    'transaction_userIDHash' => (property_exists($transaction, 'user_id_hash')) ? $transaction->user_id_hash : '',
                    'transaction_merchantExtID' => (property_exists($transaction, 'merchant_ext_id')) ? $transaction->merchant_ext_id : '',
                    'transaction_storeExtID' => (property_exists($transaction, 'store_ext_id')) ? $transaction->store_ext_id : '',
                    'transaction_terminalID' => (property_exists($transaction, 'terminal_id')) ? $transaction->terminal_id : '',
                    'transaction_promo_id_applied' => (property_exists($transaction, 'promoIDApplied')) ? $transaction->promoIDApplied : ''
                ]);
            }
        }

        if ($this->TransactionCheck_model->createResponseTransactionCheck($body) === false) {

            $this->_response(0, "", "Warning: Query failed saving transaction check response.");

            $this->responseExit();
        }
    }

    public function create_post()
    {

        $payload = $this->request->body;

        $referenceID = $this->_generateReferenceID("sandbox", "TRANSACTION_CHECK");

        if ($referenceID !== null) {

            $resp = $this->_SPClientTransactionCheck($referenceID, 13, $payload);

            $this->createSysLog('sandbox', $referenceID, $_SERVER['REQUEST_METHOD'], __CLASS__, __METHOD__, 'TRANSACTION_CHECK', $payload['terminalID'], $payload['terminalIPAddress'], '', $resp['responseInfo']);

            $this->_createRequestTransactionCheck($referenceID, $resp['request']['header'], $resp['request']['postFields']);

            if ($resp['status'] === true) {

                if (is_object($resp['response']) && property_exists($resp['response'], 'transaction')) {

                    $transaction = $resp['response']->transaction;

                    if ($transaction->status == 2) {

                        $status = "PROCESSING";

                        $this->_response(0, "Transaction is processing.");
                    } else if ($transaction->status == 3) {

                        $status = "SUCCESSFUL";

                        $this->_response(1, "Transaction was successful.");
                    } else {

                        $status = "FAILED";

                        $this->_response(0, "Transaction failed.");
                    }
                } else {

                    $status = "UNAVAILABLE";

                    $this->_response(0, "ShopeePay transaction status is currently unavailable.");
                }
            } else {

                $status = "FAILED";

                if ((isset($resp['response']) && is_object($resp['response'])) && property_exists($resp['response'], 'debug_msg') && $resp['response']->debug_msg !== "") {

                    $this->_response(0, $resp['response']->debug_msg);
                } else {

                    $this->_response(0, "Transaction failed.");
                }
            }

            $this->_createResponseTransactionCheck($referenceID, $resp['response'], $status);
        }

        $this->response($this->responseArray, 200);
    }
}
