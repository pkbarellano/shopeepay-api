<?php

class SysLog_model extends MY_Model
{

    function createLog($params = [])
    {

        $this->_transStart();

        $this->prodDB->set($params);

        $this->prodDB->insert('sysLogs');

        return $this->_transEnd();
    }
}
