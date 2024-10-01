<?php

class POSTransaction_model extends MY_Model
{

    function countPOSTransaction($paymentReferenceID = "", $paymentCode = "")
    {

        $this->db->select("COUNT(*) AS cnt", false)
            ->from("pOSTransactions")
            ->where("(paymentReferenceID = '" . $paymentReferenceID . "' OR paymentCode = '" . $paymentCode . "')")
            ->where("(deletedAt IS NULL OR deletedAt = '')");

        return $this->db->get();
    }

    function createPOSTransaction($referenceID = "", $postField = [])
    {

        $this->_transStart('db');

        $this->db->set([
            'paymentRequestID' => $referenceID,
            'amount' => $postField['amount'],
            'paymentCode' => $postField['paymentCode'],
            'paymentReferenceID' => $postField['paymentReferenceID']
        ]);

        $this->db->insert('pOSTransactions');

        return $this->_transEnd('db');
    }
}
