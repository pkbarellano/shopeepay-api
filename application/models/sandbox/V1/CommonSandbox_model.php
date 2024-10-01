<?php

class CommonSandbox_model extends MY_Model
{

    function getAppConfig()
    {

        $this->db->select([
            "clientId",
            "secret",
            "paymentURL",
            "transactionCheckURL",
            "refundURL"
        ])
            ->from("appConfig");

        return $this->db->get();
    }

    function getDefaultParameters()
    {

        $this->db->select([
            "merchantExtId",
            "mcc",
            "currency",
            "storeExtId"
        ])
            ->from("defaultParameters");

        return $this->db->get();
    }
}
