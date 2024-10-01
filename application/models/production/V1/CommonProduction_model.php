<?php

class CommonProduction_model extends MY_Model
{

    function getAppConfig()
    {

        $this->prodDB->select([
            "clientId",
            "secret",
            "paymentURL",
            "transactionCheckURL",
            "refundURL"
        ])
            ->from("appConfig");

        return $this->prodDB->get();
    }

    function getDefaultParameters()
    {

        $this->prodDB->select([
            "merchantExtId",
            "mcc",
            "currency",
            "storeExtId"
        ])
            ->from("defaultParameters");

        return $this->prodDB->get();
    }
}
