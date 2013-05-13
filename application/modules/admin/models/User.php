<?php
class Admin_Model_User extends Zend_Db_Table{

    public function getUser(){
        echo '<br>' .  __CLASS__ . ' - ' .__FUNCTION__;
    }
}