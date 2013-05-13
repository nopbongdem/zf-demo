<?php

class Nop_System_Recursive {

    protected $_sourceArr;

    public function __construct($sourceArr = null) {
        $this->_sourceArr = $sourceArr;
    }

    public function buildRecursive($parents = 0) {
        //$resultArr = array();
        //$level = array();
        //$this->recursive1($this->_sourceArr,$parents,$level,$resultArr,0);
        $resultArr = $this->getCategory($this->_sourceArr, $parents);
        return $resultArr;
    }

    public function getCategory($data, $prentsID = 0) {
        $newArray = array();
        foreach ($data as $value) {
            if ($value['parent_id'] == $prentsID) {
                $arr['label'] = $value['name'];

                if ($value['type'] == 'root') {
                    $arr['uri'] = "#";
                } else if ($value['type'] == 'link') {
                    $arr['uri'] = $value['url'];
                } else {
                    $arr['module'] = $value['module'];
                    if ($value['controller'] == null) {
                        $arr['controller'] = "index";
                    } else {
                        $arr['controller'] = $value['controller'];
                    }

                    if ($value['action'] == null) {
                        $arr['action'] = "index";
                        $arr['privilege'] = "index";
                        $arr['resource'] = $arr['module'] . ":" . $arr['controller'];
                        //$arr['resource'] = $value['controller'];
                    } else {
                        $arr['action'] = $value['action'];
                        $arr['privilege'] = $value['action'];
                        //$arr['resource'] = $value['controller'];

                        $arr['resource'] = $arr['module'] . ":" . $arr['controller'];
                    }

                    //$arr['privilege'] = $value['action'];
                }

                if ($this->getCategory($data, $value['id']) != false) {
                    $arr['pages'] = $this->getCategory($data, $value['id']);
                }
                $newArray[] = $arr;
            }
        }
        if ($newArray != null)
            return $newArray;
        return false;
    }

}