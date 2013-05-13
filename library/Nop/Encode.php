<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Nop_Encode {

    public function encode($string) {
        $encode = md5("chacbumbum" . md5($string));
        return $encode;
    }

}
