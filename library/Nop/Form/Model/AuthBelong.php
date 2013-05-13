<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_AuthBelong extends Zend_Form {

    public function __construct($options = null, $data) {
        $group = new Zend_Form_Element_Select("group_id");
        $group->addMultiOptions($data)
                ->setLabel("Groups:")
                ->setAttribs(array(
        ));
        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttribs(array(
            'class' => "btn"
        ));
        $this->addElements(array($group, $submit));
        parent::__construct($options);
    }

}
