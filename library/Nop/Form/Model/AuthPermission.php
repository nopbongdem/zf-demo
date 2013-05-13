<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_AuthPermission extends Zend_Form {

    public function __construct($options = null) {
        $module = new Zend_Form_Element_Text("module");
        $module->setLabel("Mô đun:")
                ->setAttribs(array(
        ));
        $controller = new Zend_Form_Element_Text("controller");
        $controller->setLabel("Controller:")
                ->setAttribs(array(
        ));
        $action = new Zend_Form_Element_Text("action");
        $action->setLabel("Action:")
                ->setAttribs(array(
        ));

        $note = new Zend_Form_Element_Textarea("description");
        $note->setLabel("Description:")
                ->setAttribs(array(
                    'rows' => 5,
                    'cols' => 8
        ));

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setLabel("Save")
                ->setAttribs(array(
                    'class' => "btn"
        ));
        $this->addElements(array($module, $controller, $action, $note, $submit));
        parent::__construct($options);
    }

}
