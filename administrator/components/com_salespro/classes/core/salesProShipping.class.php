<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProShipping extends salesPro {
    public $_table = '#__spr_shipping';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'alias' => array('string', 50), 
        'name' => array('string', 50), 
        'sort' => array('int', 3), 
        'status' => array('int', 3),
        'paymentoptions' => array('json'),
        'info' => array('string')
    );
    public $order = array('sort' => 'sort', 'dir' => 'ASC', 'limit' => 0, 'page' => 0, 'total' => 0);
    public $actions = array('status');
    public $typeOptions = array('SPR_FREE', 'SPR_FLATRATE', 'SPR_ADVANCED');
    function __construct() {
        parent::__construct();
        foreach($this->typeOptions as $n=>$o) {
            $this->typeOptions[$n] = JText::_($o);
        }        
    }
    function getShipping() {
        $temp = $this->db->getObjList($this->_table, 'id', array(), $this->order);
        $object = array();
        if(sizeof($temp)>0) foreach($temp as $o) {
            $object[] = $this->getShip($o->id);
        }
        return $object;
    }
    function getShip($id = 0) {
        if ($id === 0) $id = $this->id;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) > 0) {
            $object->rules = $this->getRules($id);
            $object->paymentoptions = json_decode($object->paymentoptions);
        } else {
            $object = $this->getDefaultObject();
            $object->rules = array();
            $object->paymentoptions = array();
        }
        return $object;
    }
    function getRules($id = 0) {
        if ($id === 0) $id = $this->id;
        $rules = new salesProShippingRules;
        return $rules->getRules($id);
    }
    private function getShippingPrice($object, $attributes) {
        if (count($object->rules) < 1) {
            return false;
        }
        $rules = $this->osort($object->rules, 'price');
        foreach ($rules as $r) {
            $res = $this->checkRulePrice($r, $attributes);
            if ($res !== false) {
                $price = $res;
                break;
            }
        }
        //RETURN VALID SHIPPING METHOD PRICE
        if (isset($price))  {
            $price = sprCurrencies::_toXe($price);
            return $price;
        }
        else return FALSE;
    }
    function validateShippingMethod($method = 0, $region = 0, $attributes) {
        // GET A SPECIFIC SHIPPING METHOD PRICE BY REGION, WEIGHT, PRICE
        //IF SHIPPING METHOD NOT YET SELECTED - DEFAULT TO THE STANDARD ONE
        if ($method === 0) {
            if($methods = $this->getValidShippingMethods($region, $attributes) && count($method)>0) {
                $method = $methods[0]->id;
            }
        }
        //GET THE SHIPPING OBJECT
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $method, 'status' => '1'));
        if (sizeof($object) < 1) return FALSE;
        //GET THE SHIPPING RULES & REGIONS
        $rules = new salesProShippingRules;
        $object->rules = $rules->getRules($method);
        //CHECK IF EACH RULE IS VALID FOR THE USER'S REGION
        if(count($object->rules)>0) foreach($object->rules as $x=>$rule) {
            $regions = json_decode($rule->regions);
            if (count($regions)<1 || !in_array($region, $regions)) {
                unset($object->rules[$x]);
            }
        }
        //GET THE PRICE
        $object->price = $this->getShippingPrice($object, $attributes);
        if($object->price !== FALSE) return $object;
        return FALSE;
    }
    function getValidShippingMethods($region = 0, $attributes = array(), $payoptions = array()) {

        /* /// GET VALID SHIPPING METHODS FOR SPECIFIC REGION /// */

        //GET ACTIVE SHIPPING METHODS
        $object = $this->db->getObjList($this->_table, 'id', array('status' => '1'));
        if(count($object)>0) foreach ($object as $a => $o) {
            //CHECK THE METHOD IS VALID
            if (!$object[$a] = $this->validateShippingMethod($o->id, $region, $attributes)) {
                unset($object[$a]);
                continue;                
                            
            }
            
            //TOTAL SHIP + CART PRICE TO CHECK AGAINST FREE CHECKOUT METHOD
            $total_price = $attributes->grandtotal + $object[$a]->price;
            
            //PAYMENT TYPE VALIDATION
            $paytypes = json_decode($object[$a]->paymentoptions);
            $payarray = array();
            foreach($payoptions as $p) {
                if(in_array($p->id, $paytypes)) {
                    if($total_price > 0.00 && $p->method->alias === 'freecheckout') continue;
                    elseif($total_price == 0.00 && $p->method->alias !== 'freecheckout') continue;
                    else $payarray[] = $p->id;
                }
            }
            
            //CHECK WE HAVE A VALID PAYMENT OPTION
            if(count($payarray)<1) unset($object[$a]);
            else $object[$a]->paymentoptions = json_encode($payarray);
        }
        //SORT METHODS BY PRICE ASCENDING
        $object = $this->osort($object, 'price');
        return $object;
    }
    function checkRulePrice($rule, $attributes = array()) {
        //CONVERT RULES BASED ON EXCHANGE RATE
        $rule->start_price = sprCurrencies::_toXe($rule->start_price);
        $rule->end_price = sprCurrencies::_toXe($rule->end_price);
        if ($rule->start_items > 0 && $rule->start_items > $attributes->quantity) return false;
        if ($rule->end_items > 0 && $rule->end_items < $attributes->quantity) return false;
        if ($rule->start_price > 0 && $rule->start_price > $attributes->price) return false;
        if ($rule->end_price > 0 && $rule->end_price < $attributes->price) return false;
        if ($rule->start_weight > 0 && $rule->start_weight > $attributes->weight) return false;
        if ($rule->end_weight > 0 && $rule->end_weight < $attributes->weight) return false;
        $area = array($attributes->height,$attributes->width,$attributes->depth);
        $rulearea = array($rule->height,$rule->width,$rule->depth);
        sort($area);
        sort($rulearea);
        foreach($area as $n=>$a) {
            if($rulearea[$n] == 0) continue;
            if($a > $rulearea[$n]) return FALSE;
        }
        return $rule->price;
    }
    function saveData($id = '', $array = array()) {
        if(isset($_POST['spr_shipping_name'])) $_POST['spr_shipping_alias'] = $_POST['spr_shipping_name'];
        return parent::saveData($id, $array);
    }
}
