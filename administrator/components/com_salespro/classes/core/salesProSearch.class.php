<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class sprSearch {

    public static function _get($type,$vars = array()) {
        $ret = $vars;
        foreach ($vars as $field=>$val) {
            if (isset($_GET['spr_' . $field])) {
                $val = $_GET['spr_' . $field];
                if (is_array($val)) continue;
                if (strlen($val) === 0) continue;
                $val = htmlspecialchars(urlencode($val));
                $ret[$field] = $val;
                sprSearch::_update($type,$field,$val);
            } else {
                if($val = sprCookies::_getVar($type.'.'.$field)) {
                    $ret[$field] = $val;
                }
            }
        }
        return $ret;
    }
    
    public static function _getVal($type,$field) {
        if($val = sprCookies::_getVar($type.'.'.$field)) return $val;
        return '';
    }
    
    public static function _update($type,$field,$var) {
        sprCookies::_setVar($type.'.'.$field,$var);
    }
}