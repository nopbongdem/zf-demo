<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_User extends Zend_Form {

    public function __construct($options = null) {
        $this->setAttribs(array(
            'autocomplete' => "off"
        ));
        $name = new Zend_Form_Element_Text("username");
        $name->setRequired(true)
                ->setLabel("Username")
                ->setAttribs(array(
        ));
        $pass = new Zend_Form_Element_Password("password");
        $pass->setLabel("Password")
                ->setAttribs(array(
        ));

        $first_name = new Zend_Form_Element_Text("first_name");
        $first_name->setLabel("First name:")
                ->setAttribs(array(
        ));
        $last_name = new Zend_Form_Element_Text("last_name");
        $last_name->setLabel("Last name:")
                ->setAttribs(array(
        ));


        $email = new Zend_Form_Element_Text("email");
        $email->setLabel("Email")
                ->setAttribs(array(
        ));
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttribs(array(
            'class' => "btn"
        ));
        $this->addElements(array($name, $pass, $first_name, $last_name, $email, $submit));
        parent::__construct($options);
    }

}
