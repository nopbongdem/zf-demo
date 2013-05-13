<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_AuthGroup extends Zend_Form {

    public function __construct($options = null, $data) {
        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Tên nhóm")
                ->setAttribs(array(
        ));
        $module = new Zend_Form_Element_Text("description");
        $module->setLabel("Thông tin")
                ->setAttribs(array(
        ));
        $controller = new Zend_Form_Element_Select("group_parent_id");
        $controller->addMultiOptions($data)
                ->setLabel("User Parent")
                ->setAttribs(array(
        ));
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttribs(array(
            'class' => "btn"
        ));
        $this->addElements(array($name, $module, $controller, $submit));
        parent::__construct($options);
    }

}
