<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Form_Model_Navigation extends Zend_Form {

    public function __construct($options = null) {
        $name = new Zend_Form_Element_Text("name");
        $name->setRequired(true)
                ->setLabel("Tên:")
                ->setAttribs(array(
        ));
        $module = new Zend_Form_Element_Text("module");
        $module->setRequired(true)
                ->setLabel("Mô đun:")
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

        $note = new Zend_Form_Element_Textarea("note");
        $note->setLabel("Description:")
                ->setAttribs(array(
                    'rows' => 5,
                    'cols' => 8,
        ));

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttribs(array(
            'class' => "btn"
        ));
        $this->addElements(array($name, $module, $controller, $action, $note, $submit));
        parent::__construct($options);
    }

}
