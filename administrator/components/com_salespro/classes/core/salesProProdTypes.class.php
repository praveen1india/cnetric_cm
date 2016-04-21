<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProProdTypes extends salesPro {
    public $_table = '#__spr_prodtypes';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'name' => array('string', 255),
        'params' => array('json')
    );
    public $params = array(
        'var' => '2',
        'del' => '1',
        'sm' => '2',
        'dl' => '2',
        'tc' => '0',
        'quantity' => '1',
    );
    public $actions = array('save','delete');
    function __construct() {
        parent::__construct();
    }
    function getTypes() {
        $object = $this->db->getObjList($this->_table,'id');
        if(count($object)>0) foreach($object as $n=>$o) {
            $object[$n] = $this->getType($o->id);
        }
        return $object;
    }
    function getType($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if(sizeof($object)<1) {
            $object = $this->getDefaultObject();
        }
        $object->params = $this->getParams($object->params);
        foreach($this->params as $x=>$y) {
            if(!isset($object->params->$x) || $object->params->$x === '0' || $object->params->$x === '') {
                $object->params->$x = $y;
            }
            foreach(array('var','dl','del','sm','tc','quantity') as $x) {
                switch ($object->params->$x) {
                    case '1': $icon = 'yes';
                        break;
                    case '2': $icon = 'no';
                        break;
                    case '0':
                    default: $icon = 'blank';
                        break;
                }
                $$x = "<span class='spr_icon spr_icon_{$icon}' style='margin:0 auto;'>&nbsp;</span>";
            }
            $object->string = "<tr id='spr_prodtypes_{$object->id}' t_id='{$object->id}' t_name='{$object->name}' t_del='{$object->params->del}' t_var='{$object->params->var}' t_dl='{$object->params->dl}' t_sm='{$object->params->sm}' t_tc='{$object->params->tc}' t_quantity='{$object->params->quantity}'><td><a href='#' onclick='editPT({$object->id});' class='t_name'>{$object->name}</a></td><td class='nowrap center' width='1%'>{$var}</td>
            <td class='nowrap center' width='1%'>{$del}</td>
            <td class='nowrap center' width='1%'>{$sm}</td>
            <td class='nowrap center' width='1%'>{$dl}</td>
            <td class='nowrap center' width='1%'>{$quantity}</td>
            <td class='nowrap center' width='1%'>{$tc}</td>
            <td class='nowrap center' width='1%'><a href='#' onclick='editPT({$object->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deletePT({$object->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
        }
        return $object;
    }
    function ajaxDelete() {
        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        if($id < 1) return $this->saveJson();
        $class = new salesProItems;
        $count = 0;
        $where = array(array('type' => $id));
        $count = (int)$class->db->getCount($class->_table, $where);
        $ajax = new salesProAjax;
        if($count > 0) {
            $ajax->json['error'] = "You have {$count} items assigned to this product type - can't delete";
            $ajax->saveJson();
        } else {
            $ajax->delete($this->_table,$this->getVars());
        }
    }
    
    function delete($table = '', $goodVars = array(), $saveJson = 1) {
        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        $this->db->deleteData($table, array('id' => $id));
        $this->json['id'] = $id;
        if($saveJson === 1) $this->saveJson();
    }
    
    function ajaxSave() {
        $ajax = new salesProAjax;
        $ajax->save($this->_table, $this->getVars(), 0);
        if($ajax->json['id'] > 0) {
            $id = (int) $ajax->json['id'];
            $pt = $this->getType($id);
            $ajax->json['string'] = $pt->string;
        }
        $ajax->saveJson();
    }
}

class sprProdTypes implements salesProFactory {
    
    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $types = NULL;
        $class = new salesProProdTypes;
        if($id > 0) return $class->getType($id);
        else {
            if(NULL === $types) {
                $types = $class->getTypes();
            }
            return $types;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProProdTypes;
        return $class->selectOptions($selected,$options,$text);
    }

    /* FACTORY METHOD TO GET THE DEFAULT PRODUCT TYPE */
    public static function _getDefault() {
        static $default = NULL;
        if(NULL === $default) {
            $types = sprProdTypes::_load();
            if(count($types)>0) $default = $types[0];
        }
        return $default;
    }
    
    /* FACTORY METHOD TO GET BASIC PRODUCT TYPE ID AND NAMES */
    public static function _getTypes() {
        static $types = NULL;
        if(NULL === $types) {
            $types = array();
            $class = new salesProProdTypes;
            $object = $class->db->getObjList($class->_table,array('id','name'));
            if(count($object)>0) foreach($object as $o) {
                $types[$o->id] = $o->name;
            }
        }
        return $types;
    }
    
    /* FACTORY METHOD TO GET JUST THE PARAMETERS */
    public static function _loadParams($id) {
        $type = sprProdTypes::_load($id);
        if(isset($type->params)) return $type->params;
        return FALSE;
    }
}