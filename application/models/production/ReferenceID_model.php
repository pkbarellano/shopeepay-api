<?php

class ReferenceID_model extends MY_Model
{

    function getPaymentReferenceID($referenceID = "")
    {

        $this->prodDB->select('pGReferenceID')
            ->from('paymentReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->prodDB->get();
    }

    function createPaymentReferenceID($referenceID = "")
    {

        $this->_transStart('prodDB');

        $data = ['pGReferenceID' => $referenceID];

        $this->prodDB->insert('paymentReferenceIDs', $data);

        return $this->_transEnd('prodDB');
    }

    function getTransactionCheckReferenceID($referenceID = "")
    {

        $this->prodDB->select('pGReferenceID')
            ->from('transactionCheckReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->prodDB->get();
    }

    function createTransactionCheckReferenceID($referenceID = "")
    {

        $this->_transStart('prodDB');

        $data = ['pGReferenceID' => $referenceID];

        $this->prodDB->insert('transactionCheckReferenceIDs', $data);

        return $this->_transEnd('prodDB');
    }

    function getRefundReferenceID($referenceID = "")
    {

        $this->prodDB->select('pGReferenceID')
            ->from('refundReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->prodDB->get();
    }

    function createRefundReferenceID($referenceID = "")
    {

        $this->_transStart('prodDB');

        $data = ['pGReferenceID' => $referenceID];

        $this->prodDB->insert('refundReferenceIDs', $data);

        return $this->_transEnd('prodDB');
    }
}
