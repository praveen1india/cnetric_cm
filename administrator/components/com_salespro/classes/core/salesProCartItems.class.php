<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCartItems extends salesPro {
    public $_table = '#__spr_cart_items';
    public $_vars = array(
        'id' => array('int', 11), 
        'cart_id' => array('int', 11), 
        'hash' => array('string', 6),
        'item_id' => array('int', 6), 
        'variant_id' => array('int', 6), 
        'quantity' => array('int', 6)
    );
    function __construct() {
        parent::__construct();
    }
    function getItems($cart, $hash) {
        $object = $this->db->getObjList($this->_table, 'id', array('cart_id' => $cart, 'hash' => $hash));
        if (sizeof($object) > 0) {
            foreach ($object as $n=>$o) {
                if($temp = $this->getItem($o->id, $cart, $hash)) {
                    $object[$n] = $temp;
                }
            }
        } else {
            $object = array();
        }
        return $object;
    }
    function getItem($id = 0, $cartid = 0, $hash = '') {
        $where = array('id' => $id, 'cart_id' => $cartid, 'hash' => $hash);
        $object = $this->db->getObj($this->_table, $this->getVars(), $where);
        if (sizeof($object) > 0) {

            //GET ITEM BASIC INFORMATION
            $object->data = sprItems::_loadBasics($object->item_id);
            if(!isset($object->id)) return FALSE;
            $object->data->category_name = sprCategories::_getName($object->data->category);
            $object->data->category_alias = sprCategories::_getAlias($object->data->category);
            $object->data->image = sprItemImages::_getMainImage($object->item_id);
            $object->data->params = sprProdTypes::_loadParams($object->data->type);
            $object->data->attributes = array();

            //PARSE THE VARIANT DETAILS IF APPLICABLE
            if((int)$object->variant_id >0) {
                $myvariant = sprItemVariants::_getVar($object->variant_id,$object->item_id);
                if(!isset($myvariant->id)) return FALSE;
                $object->price = ($myvariant->onsale === '1') ? $myvariant->sale : $myvariant->price;
                $object->data->sku = $myvariant->sku;
                $object->data->onsale = $myvariant->onsale;
                $object->data->stock = $myvariant->stock;
                $object->data->status = $myvariant->status;
                $object->data->image = $myvariant->image;
                $object->data->attributes = $myvariant->attributes;
            } else {
                //PARSE THE STANDARD ITEM TYPE SALE PRICE IF APPLICABLE
                $object->price = ($object->data->onsale === '1') ? $object->data->sale : $object->data->price;
            }
            
            //STOCK MANAGEMENT CHECK
            if($object->data->params->sm === '1') {
                $newquantity = $object->data->stock - $object->quantity;
                if($newquantity < 0) {
                    $this->setQuantity ($object->id,$object->data->stock,$object->cart_id,$object->hash);
                    $this->showMsg('One of your items is out of stock. Your cart has been updated.');
                    return $this->getItem($id,$cartid,$hash);
                }
            }
            $object->xe_price = sprCurrencies::_toXe($object->price);
            $object->f_price = sprCurrencies::_format($object->xe_price);
        }  else {
            return FALSE;
        }
        return $object;
    }
    function getItemByItemID($item_id = 0, $cartid = 0, $hash = '', $variant_id = 0) {
        $where = array('item_id' => $item_id, 'cart_id' => $cartid, 'hash' => $hash);
        if((int)$variant_id>0) {
            $where['variant_id'] = $variant_id;
        }
        $object = $this->db->getObj($this->_table, $this->getVars(), $where);
        if (sizeof($object) > 0) return $object;
        else return $this->getDefaultObject();        
    }
    function getCartItem($id = 0, $cartid = 0, $hash = '') {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id, 'cart_id' => $cartid, 'hash' => $hash));
        return $object;
    }
    function addItem($item_id, $variant_id, $quantity, $cartid, $hash) {
        $item_id = (int)$item_id;
        $variant_id = (int)$variant_id;
        $quantity = (int)$quantity;
        $cartid = (int)$cartid;
        
        //CHECK IF THE ITEM STILL EXISTS
        $itemdata = sprItems::_loadBasics($item_id);
        if (!isset($itemdata->id)) {
            return false;
        }
        
        //GET VARIANT IF NEEDED
        if($variant_id>0) {
            $myvariant = sprItemVariants::_getVar($variant_id);
            if(isset($myvariant->item_id) && (int)$myvariant->item_id === $item_id) {
                if($myvariant->onsale === '1') $itemdata->price = $myvariant->sale;
                else $itemdata->price = $myvariant->price;
                $itemdata->sku = $myvariant->sku;
                $itemdata->onsale = $myvariant->onsale;
                $itemdata->stock = $myvariant->stock;
                $itemdata->status = $myvariant->status;
                $itemdata->image = $myvariant->image;
                $itemdata->attributes = $myvariant->attributes;
            }
        }

        //CHECK HOW MANY OF THIS ITEM ARE ALREADY IN THE CART
        $data = $this->getItemByItemID($item_id, $cartid, $hash, $variant_id);
        if ($data->id > 0) {
            $newquantity = $data->quantity + $quantity;
            //CHECK NUMBER OF ITEMS OF THIS TYPE ADDED
            if ($itemdata->params === 1 && $newquantity >= $itemdata->stock) return 'error2';
            elseif ($itemdata->max_cart > 0 && $newquantity >= $itemdata->max_cart) return 'error2';
            //CHECK IF THE QUANTITY IS LIMITED TO 1
            $prodtype = sprProdTypes::_load($itemdata->type);
            if($prodtype->params->quantity !== '1') {
                $newquantity = 1;
            }
            //WE CAN ADD MORE - SO LETS DO SO
            $savedata = array('quantity' => $newquantity);
            $this->db->updateData($this->_table, $savedata, array('id' => $data->id));
            return $data->id;
        }
        //THERE ARE NO ITEMS OF THIS TYPE IN THE CART ALREADY - SO LETS ADD ONE
        $jsonoptions = json_encode($options);
        $savedata = array('item_id' => $item_id, 'variant_id' => $variant_id, 'quantity' => $quantity, 'cart_id' => $cartid, 'hash' => $hash);
        $res = $this->db->insertData($this->_table, $savedata);
        return $res;
    }
    function removeCartItem($id, $cartid, $hash) {
        $id = (int)$id;
        $cartid = (int)$cartid;
        $cartitem = $this->getCartItem($id, $cartid, $hash);
        $this->db->deleteData($this->_table, array('id' => $cartitem->id, 'cart_id' => $cartitem->cart_id, 'hash' => $cartitem->hash));
        return $id;
    }
    function emptyCart($cartid, $hash) {
        $cartid = (int)$cartid;
        $this->db->deleteData($this->_table, array('cart_id' => $cartid, 'hash' => $hash));
    }
    function setQuantity($id, $quantity, $cartid, $hash) {
        $id = (int)$id;
        $quantity = (int)$quantity;
        $cartid = (int)$cartid;
        //GET THE CART ITEM DATA
        $cartitem = $this->getCartItem($id, $cartid, $hash);
        //REMOVE IF ZERO
        if ($quantity <= 0) return $this->removeCartItem($cartitem->id, $cartid, $hash);
        //UPDATE THE QUANTITY AS REQUIRED
        $data = array('quantity'=>$quantity);
        $this->db->updateData($this->_table,$data,array('id'=>$cartitem->id));
    }
}
