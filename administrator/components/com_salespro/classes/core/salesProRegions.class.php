<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProRegions extends salesPro {
    public $_table = '#__spr_regions';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'level' => array('int', 3),
        'parent' => array('int', 6), 
        'name' => array('string', 30), 
        'code_2' => array('string', 2),
        'code_3' => array('string', 3),
        'status' => array('int', 4),
        'default' => array('int', 1)
    );
    public $actions = array('status', 'setDefault', 'delete', 'save');
    public $order = array('sort' => array('status'=>'ASC','name'=>'ASC'));
    function __construct() {
        parent::__construct();
    }
    function getRegions($parent = '0',$status = '0') {
        $where = array('parent' => $parent);
        if((int)$status > 0) $where[] = array('status'=>$status);
        $object = $this->db->getObjList($this->_table, $this->getVars(), $where, $this->order);
        if (sizeof($object) > 0) return $object;
        else return array();
    }
    
    function getDefaultRegion() {
        static $id = NULL;
        if(NULL === $id) {
            $id = (int) $this->db->getResult($this->_table, 'id', array('default' => 1));
            if ($id < 1) $id = (int) $this->db->getResult($this->_table, 'id', array('id' => '>=1'));
        }
        if ($id < 1) return FALSE;
        return $id;
    }
    
    function getRegion($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
        }
        //GET PARENT NAME
        $object->parent_name = $this->getRegionName($object->parent);
        //GET TAXES
        //$taxes = new salesProTaxes;
        //$object->tax = $taxes->getRegionalTax($object->id, $object->parent);
        return $object;
    }
    
    function getRegionName($id = 0) {
        if ($id === 0) return '';
        return $this->getVar('name',$id);
    }
    
    function getRegionCode($id = 0) {
        if ($id === 0) return '';
        if ($res = $this->db->getResult($this->_table, 'code_3', array('id' => $id))) {
            return $res;
        }
        else {
            return '';
        }
    }

    function getRegionCode2($id = 0) {
        if ($id === 0) return '';
        if ($res = $this->db->getResult($this->_table, 'code_2', array('id' => $id))) {
            return $res;
        }
        else {
            return '';
        }
    }

    function showRegionOption($r, $selected = 0, $showstates = 0, $selectall = 0, $disableid = 0, $level = 0, $disableregions = 1) {
        if ($disableid == $r->id) return '';
        if ($r->status === '2' && $selectall === 0) {
            $ret = '';
            return $ret;
        }
        if ($level > 1 && $showstates === 0) return '';
        $name = '';
        if ($level === 0) {
            $name = $r->name;
        }
        else
            if ($level >= 1) {
                for ($i = 0; $i < $level; $i++) $name .= '&mdash;';
                $name .= ' ' . $r->name;
            }
        if (is_array($selected)) {
            $sel = (in_array($r->id, $selected, false)) ? 'selected="selected"' : '';
        }
        else {
            $sel = ($selected == $r->id) ? 'selected="selected"' : '';
        }
        if ($level === 0) {
            $dis = ($disableregions === 1) ? "disabled='disabled'" : "";
        }
        else {
            $dis = "";
        }
        $class = "region_{$level}";
        $ret = "<option value='{$r->id}' {$sel} {$dis}>{$name}</option>";
        $level++;
        if (isset($r->children) && count($r->children) > 0) {
            foreach ($r->children as $c) $ret .= $this->showRegionOption($c, $selected, $showstates, $selectall, $disableid, $level, $disableregions);
        }
        return $ret;
    }
}

class sprRegions implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        $class = new salesProRegions;
        if($id > 0) return $class->getRegion($id);
        else $regions = $class->getRegions();
        return $regions;
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0,$disabled = array()) {
        $class = new salesProRegions;
        return $class->selectOptions($selected,$options,$text,$disabled);
    }
    
    /* FACTORY METHOD TO GET ACTIVE REGIONS */
    public static function _getActive($id=0) {
        $actives = NULL;
        if(NULL === $actives) {
            $class = new salesProRegions;
            $actives = $class->getRegions(0,1);
        }
        return $actives;
    }
    
    /* FACTORY METHOD TO GET THE DEFAULT REGION */
    public static function _getDefault() {
        $class = new salesProRegions;
        $id = $class->getDefaultRegion();
        return $class->getRegion($id);
    }

    /* FACTORY METHOD TO GET THE STATES FOR A REGION */
    public static function _getStates($regionid = 0,$status = 0) {
        if($status === 0) $status = 1;
        $class = new salesProRegions;
        $array = $class->getRegions($regionid,$status);
        return $array;
    }
    
    /* FACTORY METHOD TO GET AN EMPTY REGION OBJECT - USE IN CREATE VIEW */
    public static function _getBlank() {
        $class = new salesProRegions;
        return $class->getDefaultObject();
    }
    
    /* FACTORY METHOD TO GET A REGION NAME */
    public static function _getName($id=0) {
        if($id === 0) return '';
        $class = new salesProRegions;
        return $class->getVar('name',$id);
    }
    
    /* FACTORY METHOD TO GET A REGION CODE */
    public static function _getCode($id=0) {
        $class = new salesProRegions;
        return $class->getRegionCode($id);
    }
    
    /* FACTORY METHOD TO GET A 2 CHAR REGION CODE */
    public static function _getCode2($id=0) {
        $class = new salesProRegions;
        return $class->getRegionCode2($id);
    }
}