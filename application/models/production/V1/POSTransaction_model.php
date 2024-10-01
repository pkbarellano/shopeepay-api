<?php

class POSTransaction_model extends MY_Model
{

    function countPOSTransaction($paymentReferenceID = "", $paymentCode = "")
    {

        $this->prodDB->select("COUNT(*) AS cnt", false)
            ->from("pOSTransactions")
            ->where("(paymentReferenceID = '" . $paymentReferenceID . "' OR paymentCode = '" . $paymentCode . "')")
            ->where("(deletedAt IS NULL OR deletedAt = '')");

        return $this->prodDB->get();
    }

    function createPOSTransaction($referenceID = "", $postField = [])
    {

        $this->_transStart('prodDB');

        $this->prodDB->set([
            'paymentRequestID' => $referenceID,
            'amount' => $postField['amount'],
            'paymentCode' => $postField['paymentCode'],
            'paymentReferenceID' => $postField['paymentReferenceID']
        ]);

        $this->prodDB->insert('pOSTransactions');

        return $this->_transEnd('prodDB');
    }
}
