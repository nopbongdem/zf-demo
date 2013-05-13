<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_Login extends Zend_Form {

    public function __construct($options = null) {
        $this->setAttribs(array(
            "class" => "login-form"
        ));
        $name = new Zend_Form_Element_Text("username");
        $name->setRequired(true)
                ->removeDecorator("Label")
                ->removeDecorator("HTMLTag")
                ->setLabel("Username:")
                ->setAttribs(array(
        ));
        $pass = new Zend_Form_Element_Password("password");
        $pass->setRequired(true)
                ->setLabel("Password:")
                ->setAttribs(array(
                ))
                ->removeDecorator("Label")
                ->removeDecorator("HTMLTag");

        $remember = new Zend_Form_Element_Checkbox("remember");
        $remember->setLabel("Nhớ cho lần sau")
                ->removeDecorator("Label")
                ->removeDecorator("HTMLTag");

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttribs(array(
            'class' => "btn"
        ));
        $this->addElements(array($name, $pass, $remember, $submit));

        parent::__construct($options);
    }

}
