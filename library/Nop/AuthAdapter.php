<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_AuthAdapter {

    public function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName("auth_user")
                ->setIdentityColumn("username")
                ->setCredentialColumn("password")
        ;
        return $authAdapter;
    }

}
