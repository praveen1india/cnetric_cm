<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProConfig extends salesPro {
    public $_table = '#__spr_config';
    public $id = '1';
    public $_vars = array(
        'name' => array('string', 10),
        'params' => array('json')
    );
    public $params = array(
        'core' => array(
            'name' => 'SalesPro',
            'hp_title' => 'Welcome to SalesPro',
            'cart_action' => '1',
            'ssl' => '2',
            'tc' => '2',
            'tcpage' => '0',
            'taxes' => '1',
            'show_welcome' => '1',
            'stock_empty' => '1',
            'updates' => array(),
            'updates_checked' => 0,
            'timer' => 0,
        ),
        'thankyou' => array(
            'title' => 'Thank you for your purchase',
            'content' => 'Please check your email for your order confirmation'
        ),
        'files' => array(
            'valid' => 'pdf,html',
            'loc' => 'dls/'
        ),
        'images' => array(
            'crop' => '1',
            'bg' => '',
            'valid' => 'jpg,png,gif',
            'loc' => 'images/salesPro/'
        ),
        'units' => array(
            'size' => 'mm',
            'weight' => 'grams'
        )
    );
    public $imagecropOptions = array('SPR_SET_IMG_OFF','SPR_SET_IMG_CONSERVATIVE','SPR_SET_IMG_ZOOM');
    public $weightOptions = array('grams', 'kgs', 'lbs', 'oz');
    public $sizeOptions = array('mm','cm','meters','inches','feet');
    public function __construct() {
        parent::__construct();
        foreach($this->imagecropOptions as $n=>$o) {
            $this->imagecropOptions[$n] = JText::_($o);
        }
        foreach($this->sizeOptions as $n=>$o) {
            $this->sizeOptions[$o] = $o;
            unset($this->sizeOptions[$n]);
        }
        foreach($this->weightOptions as $n=>$o) {
            $this->weightOptions[$o] = $o;
            unset($this->weightOptions[$n]);
        }
    }
    
    function checkConfigExists($name='') {
        $object = $this->db->getObj($this->_table, 'params', (array('name' => $name)));
        if(isset($object->params)) return json_decode($object->params);
        return FALSE;
    }
    
    function getConfig($name='') {
        
        $params = array();

        $object = $this->db->getObj($this->_table, 'params', (array('name' => $name)));
        if(isset($object->params)) $params = json_decode($object->params);
        
        $temps = (isset($this->params[$name])) ? $this->params[$name] : array();
        
        $params = $this->getParams($params,$temps);

        return $params;
    }

    function saveData($id = '', $array = array()) {
        
        foreach($_POST as $field=>$val) {
            if(strpos($field,'spr_config_') === 0) {
                $field = substr($field,strlen('spr_config_'));
                if(!is_array($val)) continue;
                sprConfig::_save($field,$val);
            }
        }
    }
}


class sprConfig {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($name='',$myparams=array()) {
        $class = new salesProConfig();
        $params = $class->getConfig($name);
        foreach($params as $field=>$var) {
            $myparams[$field] = $var;
        }
        return (object) $myparams;
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProConfig;
        return $class->selectOptions($selected,$options,$text);
    }
    
    /* FACTORY METHOD TO OVERWRITE ALL EXISTING PARAMETERS */
    public static function _save($name = '', $array = array()) {
        $data = array();
        
        $class = new salesProConfig;

        $params = $class->checkConfigExists($name);
        if($params !== FALSE) {
            $data = (array) $params;
        } else {
            $data = (array) sprConfig::_load($name);
        }
        if(count($array)>0) foreach($array as $field=>$val) {
            $data[$field] = $val;
        }

        if($params !== FALSE) {
            $class->db->updateData($class->_table, array('params' => $data), array('name' => $name));
        } else {
            $x = $class->db->insertData($class->_table, array('name' => $name, 'params' => $data));
        }
        return TRUE;
    }
    
    /* FACTORY METHOD TO SAVE SINGLE PARAMETER */
    public static function _saveParam($name='', $field='',$val='') {
        $params = array($field=>$val);
        return sprConfig::_save($name, $params);
    }
}