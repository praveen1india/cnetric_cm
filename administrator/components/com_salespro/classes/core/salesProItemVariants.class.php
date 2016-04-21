<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemVariants extends salesPro {
    public $_table = '#__spr_item_variants';
    public $itemid = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'item_id' => array('int', 6),
        'price' => array('float'),
        'sku' => array('string', 20),
        'image_id' => array('int', 6),
        'sale' => array('float'),
        'onsale' => array('int',1,'2'),
        'stock' => array('int',11),
        'status' => array('int',1,'1'),
    );
    public $actions = array('save', 'delete', 'status', 'onsale', 'getImages');
    function __construct($itemid = 0) {
        parent::__construct();
    }
    function checkSKU($sku='',$id=0) {
        $id = (int)$id;
        $res = $this->db->getObjList($this->_table,'id',array('sku'=>$sku));
        if(count($res) === 0) return TRUE;
        elseif(count($res) === 1 && (int)$res[0]->id === $id) return TRUE;
        return FALSE;
    }
    function getVariants($item_id = 0) {
        if($item_id === 0) $item_id = $this->itemid;
        $where = array('item_id' => $item_id);
        $object = $this->db->getObjList($this->_table, 'id', $where);
        if(count($object)>0) foreach($object as $n=>$o) {
            $object[$n] = $this->getVariant($o->id,$item_id);
        }        
        return $object;
    }
    function getVariant($id = 0,$item_id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        $object->image = sprItemImages::_getImage($object->image_id)->image;
        $object->attributes = sprItemVariantsMap::_loadAttributes($id);
        if($item_id > 0) {
            $item = sprItems::_loadBasics($item_id);
            $okattrs = sprItemAttributesMap::_loadMap($item->id);
            foreach($object->attributes as $n=>$attr) {
                if(!in_array($attr->id,$okattrs)) unset($object->attributes[$n]);
            }
            if($object->image_id < 1) $object->image = $item->mainimage;
        }
        return $object;
    }
    function ajaxDelete() {
        
        //CONFORM POSTED DATA TO CORRECT TYPES
        $_POST = $this->sanitizeAll($_POST);
                
        $variant_id = $_POST['id'];
        sprItemVariantsMap::_deleteValues($variant_id);
        $ajax = new salesProAjax;
        $ajax->delete($this->_table,$this->getVars());
    }
    function ajaxSave() {
        
        //CONFORM POSTED DATA TO CORRECT TYPES
        $_POST = $this->sanitizeAll($_POST);
        
        $item_id = (isset($_POST['item_id'])) ? (int) $_POST['item_id'] : 0;
        $var_id = (isset($_POST['id'])) ? (int) $_POST['id'] : 0;
        $cat_id = (isset($_POST['category'])) ? (int) $_POST['category'] : 0;
        unset($_POST['category']);

        $attributes = array();
        if(isset($_POST['attributes'])) {
            $temp = (array)json_decode($_POST['attributes']);
            unset($_POST['attributes']);
            foreach($temp as $n=>$a) {
                $x = (int)$n;
                $attributes[$x] = $a;
            }
        }

        $ajax = new salesProAjax;
        
        if(sizeof($attributes)<1) {
            $ajax->error('Please select one or more attributes for this item. If you need to set up your Product Attributes, please do this before creating the product');
        }

        //CHECK IF THE VARIANT ALREADY EXISTS
        $oldvariants = sprItemVariants::_load($item_id);

        foreach($oldvariants as $v) {
            if($v->id == $var_id) continue;
            $testattr = array();
            $bad = 0;
            foreach($v->attributes as $n=>$a) {
                $id = (int)$a->id;
                if(!isset($attributes[$id])) continue;
                if(strcasecmp($a->value,$attributes[$id]) === 0) {
                    $bad++;
                }
            }
            if($bad >= count($v->attributes)) $ajax->error(JText::_('SPR_ITEM_VARS_NOEDIT'));
        }
        
        //CHECK ATTRIBUTES ARE VALID AND ARE ALL FILLED
        foreach($attributes as $n=>$a) {
            if(strlen(trim($a))<1) unset($attributes[$n]);
        }
        
        $valid_attrs = sprItemAttributesMap::_loadMap($item_id);
        foreach($valid_attrs as $a) {
            $a = (int)$a;
            if(!isset($attributes[$a])) {
                $ajax->error(JText::_('SPR_ITEM_VARS_NOVAL'));
            }
        }
        
        //CHECK THE SKU IS VALID
        if(isset($_POST['sku'])) {
            $sku = $_POST['sku'];
            $skuok = $this->checkSKU($sku,$var_id);
            if($skuok !== FALSE) {
                $items = new salesProItems;
                $skuok = $items->checkSKU($sku,$item_id);
            }
            if($skuok === FALSE) {
                $ajax->error(JText::_('SPR_ITEM_ERROR_SKU'));
            }
        }

        
        //SAVE THE VARIANT
        $ajax->save($this->_table, $this->getVars(), 0);        
        if($ajax->json['id'] > 0) {
            $id = (int)$ajax->json['id'];
            if(count($attributes)>0) {
                foreach($attributes as $attr=>$v) {
                    if(!$value = sprAttributesValues::_checkValue($attr,$v)) continue;
                    sprItemVariantsMap::_saveValue($id,$attr,$value);
                }
            }
            $var = $this->getVariant($id);
            $ajax->json['string'] = sprItemVariants::_getHTML($var);
        }
        $ajax->saveJson();
    }
    function ajaxOnSale() {
        
        //CONFORM POSTED DATA TO CORRECT TYPES
        $_POST = $this->sanitizeAll($_POST);
        
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $res = $this->db->getResult($this->_table, 'onsale', array('id' => $id));
            $res = ($res === '1') ? '2' : '1';
            $this->db->updateData($this->_table, array('onsale' => $res), array('id' => $id));
        }
        $this->json['onsale'] = $res;
        $this->saveJson();
    }
    public function ajaxGetImages() {

        //CONFORM POSTED DATA TO CORRECT TYPES
        $_POST = $this->sanitizeAll($_POST);
        
        $item_id = (isset($_POST['item'])) ? (int) $_POST['item'] : 0;
        $images = sprItemImages::_getImages($item_id, 0);
        $images2 = sprItemImages::_getImages(0, 0);
        $images = array_merge($images,$images2);
        $images = array_map("unserialize", array_unique(array_map("serialize", $images)));
        sort($images);
        $this->json['data'] = $images;
        $this->saveJson();
    }
}

class sprItemVariants {
    
    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    /* GET ALL VARIANTS FOR ITEM ID */
    public static function _load($id) {
        $class = new salesProItemVariants;
        return $class->getVariants($id);
    }
    
    /* GET INDIVIDUAL VARIANT DETAILS */
    public static function _getVar($id,$item_id=0) {
        $class = new salesProItemVariants;
        return $class->getVariant($id,$item_id);
    }

    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProAttributes;
        return $class->selectOptions($selected,$options,$text);
    }
    
    public static function _getHTML($object) {
        
        $atts = '';
        foreach(array('id','price','sku','image_id','sale','stock','onsale','status') as $a) {
            $atts .= "var_{$a}='{$object->$a}' ";
        }

        $atthtml = '';
        $attributes = '';
        if(count($object->attributes)>0) {
            $atthtml .= "<ul>";
            foreach($object->attributes as $a) {
                $attributes .= "attr_{$a->id}='{$a->value}'";
                $atthtml .= "<li>{$a->name}: {$a->value}</li>";
            }
            $atthtml .= "</ul>";
        }
        
        $oyes = ($object->onsale === '1') ? 'yes':'no';
        $onsale = "<a class='spr_icon spr_icon_{$oyes}' id='var_onsale_{$object->id}' onclick='varOnSale({$object->id});' style='margin:0 auto;'>&nbsp;</a>";
        $ayes = ($object->status === '1') ? 'yes':'no';
        $status = "<a class='spr_icon spr_icon_{$ayes}' id='var_status_{$object->id}' onclick='varStatus({$object->id});' style='margin:0 auto;'>&nbsp;</a>";

        $html = "<tr id='variant_{$object->id}' {$attributes} {$atts}><td width='1%'><img src='".salesProImage::_($object->image,150)."' /></td><td class='var_attr'>{$atthtml}SKU: {$object->sku}</td><td class='var_price'>{$object->price}</td><td class='var_stock'>{$object->stock}</td><td class='var_sale'>{$object->sale}</td><td class='var_onsale'>{$onsale}</td><td class='var_status'>$status</td><td class='var_actions'><a href='#' onclick='editVariant({$object->id})' class='spr_icon spr_icon_edit'>&nbsp;</a>&nbsp;<a href='#' onclick='deleteVariant({$object->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
        return $html;
    }
    
    /* FACTORY METHOD TO SET STOCK FOR A SPECIFIC VARIANT */
    public static function setStock($variant_id,$stock) {
        $class = new salesProItemVariants;
        if($stock < 0) $stock = 0;
        $class->setVar($variant_id, array('stock'=>$stock));
    }
    
    /* FACTORY METHOD TO SAVE VARIANT FOR A NEW ITEM */
    public static function _assignNewItemAttributes($item_id) {
        $class = new salesProItemVariants;
        $class->db->updateData($class->_table, array('item_id' => $item_id), array('item_id' => 0));
    }
    
    /* FACTORY METHOD TO DELETE OLD VARIANTS FOR A SPECIFIC ITEM */
    public static function _deleteVariant($variant=0) {
        $class = new salesProItemVariants;
        if((int) $variant === 0) return FALSE;
        $where['id']=$variant;
        sprItemVariantsMap::_deleteValues($variant);
        $class->db->deleteData($class->_table, $where);
        return TRUE;
    }
}