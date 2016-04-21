<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProTaxes extends salesPro {
    public $_table = '#__spr_taxes';
    public $id = 0;
    public $order = array(
        'sort' => 'name', 
        'dir' => 'ASC', 
        'limit' => 0, 
        'page' => 0, 
        'total' => 0
    );
    public $_vars = array(
        'id' => array('int', 25), 
        'regions' => array('json'),
        'name' => array('string', 50), 
        'type' => array('int', 3), 
        'value' => array('float', 10), 
        'status' => array('int', 3)
    );
    public $_taxOptions = array('1' => 'SPR_PERCENT', '2' => 'SPR_FLATRATE');
    public $actions = array('status');
    function __construct() {
        foreach($this->_taxOptions as $n=>$o) {
            $this->_taxOptions[$n] = JText::_($o);
        }
        parent::__construct();
    }
    function getTaxes() {
        $object = $this->db->getObjList($this->_table, $this->getVars(), array(), $this->order);
        if (sizeof($object) > 0) {
            foreach ($object as $o) {
                $myregions = json_decode($o->regions);
                if (count($myregions) > 1) $o->region = JText::_('SPR_MULTIPLE_SELECTED');
                elseif (count($myregions) === 0) $o->region = JText::_('SPR_NONE_SELECTED');
                else  $o->region = sprRegions::_getName($myregions[0]);
            }
            return $object;
        }
        else {
            return array();
        }
    }
    function getTax($id = 0) {
        if ($id === 0) $id = $this->id;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) > 0) {
            $object->regions = json_decode($object->regions);
        } else {
            $object = $this->getDefaultObject();
        }
        return $object;
    }
    function getRegionalTax($region = 0) { //, $parent = 0
        $taxes = $this->getTaxes();
        $taxes = $this->osort($taxes, 'value');
        $ret = array();
        foreach ($taxes as $tax) {
            $regions = json_decode($tax->regions);
            if (count($regions) < 1) continue;
            if (in_array($region, $regions)) {
                $ret[] = $tax;
            }
        }
        if (count($ret)<1) {
            return FALSE;
            /*
            if($parent !== 0) {
                return $this->getRegionalTax($parent);
            } else {
                return $this->getDefaultObject();
            }
            */
        } 
        return $ret;
    }
    function calculateTax($tax, $value) {
        $mytax = 0;
        if (!isset($tax->type)) return FALSE;
        $tax_add = sprConfig::_load('core')->taxes;
        switch ($tax->type) {
            case '1': //PERCENTAGE ADDED TO VALUE
                if($tax_add == '2') $taxval = $value * ($tax->value/100);
                else $taxval = $value - ($value / (1 + ($tax->value/100)));
                break;
            case '2': //FLAT RATE APPLIED TO VALUE
                if($tax_add == '2') $taxval = $value + $tax->value;
                $taxval = $tax->value;
                break;
            default: //TAX TYPE NOT DEFINED - ILLEGAL CALL
                return FALSE;
                break;
        }
        $mytax += (float) $taxval;
        return $mytax;
    }
    function getValidTaxes($taxes,$allowed=0) {
        if($allowed !== 0 && !is_array($allowed)) $allowed = json_decode($allowed);
        $ret = array();
        if(!is_array($allowed) || !is_array($taxes)) return $ret;
        if(count($taxes)>0) {
            foreach($taxes as $tax) {
                if($tax->status !== '1') continue;
                if (!isset($tax->type)) continue;
                if(is_array($allowed) && !in_array($tax->id,$allowed)) continue;
                $ret[] = $tax;
            }
        }
        return $ret;
    }
}

class sprTaxes implements salesProFactory {
    
    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $taxes = NULL;
        $class = new salesProTaxes;
        if($id > 0) return $class->getTax($id);
        else {
            if(NULL === $taxes) {
                $taxes = $class->getTaxes();
            }
            return $taxes;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProTaxes;
        return $class->selectOptions($selected,$options,$text);
    }
    
    /* FACTORY METHOD TO CALCULATE TAX FOR A SPECIFIC PRICE AND TAX LEVEL */
    public static function calculateTax($tax, $value) {
        $class = new salesProTaxes;
        return $class->calculateTax($tax, $value);
    }
    
    /* FACTORY METHOD TO CALCULATE TOTAL TAX FOR A SPECIFIC REGION AND TAX BRACKET(S) */
    public static function getTaxesByRegion($valid_taxes, $region = 0, $price = 0) {
        if(!is_array($valid_taxes)) return FALSE;
        $class = new salesProTaxes;
        if(!$taxes = $class->getRegionalTax($region)) return FALSE;
        foreach($taxes as $t) {
            $t->tax = 0;
            if(in_array($t->id, $valid_taxes)) {
                $t->tax += sprTaxes::calculateTax($t,$price);
            }
        }
        return $taxes;
    }
}