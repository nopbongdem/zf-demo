<?php
class Default_Model_Product extends Zend_Db_Table{

    public function getProduct(){
        echo '<br>' .  __CLASS__ . ' - ' .__FUNCTION__;
    }
}