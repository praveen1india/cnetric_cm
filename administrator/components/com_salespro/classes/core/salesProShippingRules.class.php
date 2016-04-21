<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProShippingRules extends salesPro {
    public $_table = '#__spr_shippingrules';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 11), 
        'shipping_id' => array('int', 6),
        'regions' => array('json'),
        'price' => array('float'),
        'start_weight' => array('float'), 
        'end_weight' => array('float'), 
        'start_items' => array('int', 11), 
        'end_items' => array('int', 11), 
        'start_price' => array('float'),
        'end_price' => array('float'),
        'height' => array('float'),
        'width' => array('float'),
        'depth' => array('float'),
    );
    public $order = array('sort' => 'id', 'dir' => 'ASC', 'limit' => 0, 'page' => 0, 'total' => 0);
    public $actions = array('save', 'delete');
    public $typeOptions = array('SPR_SIMPLE', 'SPR_ADVANCED');
    function __construct() {
        parent::__construct();
        foreach($this->typeOptions as $n=>$o) {
            $this->typeOptions[$n] = JText::_($o);
        }
    }
    function getRules($shipping_id = 0) {
        $object = $this->db->getObjList($this->_table, $this->getVars(), array('shipping_id' => $shipping_id));
        if(sizeof($object)>0) {
            foreach($object as $n=>$o) {
                $object[$n] = $this->getRule($o->id);
            }
        }
        return $object;
    }
    function getRule($id=0) {
        if((int)$id < 1) return FALSE;
        $object = $this->db->getObj($this->_table,$this->getVars(),array('id'=>$id));
        if (sizeof($object) < 1) return FALSE;
        $weightunit = sprConfig::_load('units')->weight;
        $sizeunit = sprConfig::_load('units')->size;
        $temp = json_decode($object->regions);
        $regions = '';
        $n = 0;
        if(sizeof($temp)>0) foreach($temp as $reg) {
            if($n++ > 0) $regions .= ',';
            $regions .= sprRegions::_getName($reg);
            if($n > 4) {
                $total = count($temp) - $n;
                if($total > 0) $regions .= "... + {$total} more";
                break;
            }
        }
        $object->string = "<tr id='spr_shipping_rules_{$object->id}' r_id='{$object->id}' r_price='{$object->price}' r_start_weight='{$object->start_weight}' r_end_weight='{$object->end_weight}' r_start_items='{$object->start_items}' r_end_items='{$object->end_items}' r_start_price='{$object->start_price}' r_end_price='{$object->end_price}' r_regions='{$object->regions}' r_height='{$object->height}' r_width='{$object->width}' r_depth='{$object->depth}'>
            <td>{$object->price}</td>
            <td>{$regions}</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMIN').": {$object->start_items}<br />".JText::_('SPR_SHP_BASKETMAX').": {$object->end_items}</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMIN').": {$object->start_price}<br />".JText::_('SPR_SHP_BASKETMAX').": {$object->end_price}</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMIN').": {$object->start_weight} $weightunit<br />".JText::_('SPR_SHP_BASKETMAX').": {$object->end_weight} $weightunit</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMAX').": {$object->height} $sizeunit</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMAX').": {$object->width} $sizeunit</td>
            <td class='nowrap'>".JText::_('SPR_SHP_BASKETMAX').": {$object->depth} $sizeunit</td>
            <td width='1%' class='nowrap center'><a href='#' onclick='editRule({$object->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteRule({$object->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
        return $object;
    }
    
    function ajaxSave() {
        
        $ajax = new salesProAjax;
        $ajax->save($this->_table, $this->getVars(), 0);
        if($ajax->json['id'] > 0) {
            $id = (int) $ajax->json['id'];
            $rule = $this->getRule($id);
            $ajax->json['string'] = $rule->string;
        }
        $ajax->saveJson();
    }
}