<?php

class Nop_Model_Acl extends Zend_Acl {

    public function __construct() {

// define Roles
        $this->addRole(new Zend_Acl_Role('guest')); // not authenicated
        $this->addRole(new Zend_Acl_Role('member'), 'guest'); // authenticated as member inherit guest privilages
        $this->addRole(new Zend_Acl_Role('admin'), 'member'); // authenticated as admin inherit member privilages
// define Resources
        $this->add(new Zend_Acl_Resource('error'));
        $this->add(new Zend_Acl_Resource('default'));
        $this->add(new Zend_Acl_Resource('index'));
        $this->add(new Zend_Acl_Resource('authentication'));
        $this->add(new Zend_Acl_Resource('signout'), 'authentication');
        $this->add(new Zend_Acl_Resource('signin'), 'authentication');
        $this->add(new Zend_Acl_Resource('activity'));

// assign privileges
        //$this->allow('guest', 'default', 'index');
        $this->allow('guest', "authentication", 'signout');
        $this->allow('guest', "index");
        //$this->deny('guest', "authentication", 'signout');
    }

}
