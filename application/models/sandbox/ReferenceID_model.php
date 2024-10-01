<?php

class ReferenceID_model extends MY_Model
{

    function getPaymentReferenceID($referenceID = "")
    {

        $this->db->select('pGReferenceID')
            ->from('paymentReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->db->get();
    }

    function createPaymentReferenceID($referenceID = "")
    {

        $this->_transStart('db');

        $data = ['pGReferenceID' => $referenceID];

        $this->db->insert('paymentReferenceIDs', $data);

        return $this->_transEnd('db');
    }

    function getTransactionCheckReferenceID($referenceID = "")
    {

        $this->db->select('pGReferenceID')
            ->from('transactionCheckReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->db->get();
    }

    function createTransactionCheckReferenceID($referenceID = "")
    {

        $this->_transStart('db');

        $data = ['pGReferenceID' => $referenceID];

        $this->db->insert('transactionCheckReferenceIDs', $data);

        return $this->_transEnd('db');
    }

    function getRefundReferenceID($referenceID = "")
    {

        $this->db->select('pGReferenceID')
            ->from('refundReferenceIDs')
            ->where('pGReferenceID', $referenceID);

        return $this->db->get();
    }

    function createRefundReferenceID($referenceID = "")
    {

        $this->_transStart('db');

        $data = ['pGReferenceID' => $referenceID];

        $this->db->insert('refundReferenceIDs', $data);

        return $this->_transEnd('db');
    }
}
