<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProSalesItems extends salesPro {
    public $_table = '#__spr_sales_items';
    public $_vars = array(
        'id' => array('int', 11),
        'sales_id' => array('int', 11),
        'sales_hash' => array('string', 6),
        'item_id' => array('int', 6),
        'variant_id' => array('int', 6),
        'quantity' => array('int', 6),
        'category' => array('int', 6),
        'category_name' => array('string', 100),
        'name' => array('string', 100),        
        'price' => array('float'),
        'f_price' => array('string',20),
        'sku' => array('string', 20),
        'image' => array('string',100),
        'tax' => array('float', 14),
        'tax_details' => array('json'),
        'attributes' => array('json'),
        'params' => array('json'),
        'weight' => array('float'),
        'height' => array('float'),
        'width' => array('float'),
        'depth' => array('float'),
        'manufacturer' => array('string', 50),
        'origin' => array('string', 50)
    );
    function __construct() {
        parent::__construct();
    }
    function getItems($salesid = 0) {
        $object = $this->db->getObjList($this->_table, 'id', array('sales_id' => $salesid));
        if(sizeof($object)>0) foreach($object as $n=>$o) {
            $object[$n] = $this->getItem($o->id);
        }
        return $object;
    }
    function getItem($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
        }
        $items = new salesProItems;
        $item = $items->getItem($object->item_id);
        $object->mainimage = $item->mainimage;
        //GET ITEM DOWNLOADS & GENERATE LINKS
        $dllinks = new salesProItemDlsLinks;
        $object->dls = $item->dls;
        if(count($object->dls)>0) {
            $app = JFactory::getApplication();
            if ($app->isSite()) {
                $user = JFactory::getUser();
                if($user->guest === 0) {
                    foreach($object->dls as $n=>$dl) {
                        if($dl->status !== '1') {
                            unset($object->dls[$n]);
                        } else {
                            $dl->link = $dllinks->genLink($object->item_id, $user->id, $dl->id);
                        }
                    }
                }
            }
        }

        $object->taxes = json_decode($object->tax_details);
        return $object;
    }
    function addSale($sale_id, $sale_hash, $item) {
        $data = array(
            'sales_id' => $sale_id,
            'sales_hash' => $sale_hash, 
            'item_id' => $item->item_id,
            'variant_id' => $item->variant_id,
            'quantity' => $item->quantity,
            'category' => $item->data->category,
            'category_name' => $item->data->category_name,
            'name' => $item->data->name,
            'price' => $item->data->price,
            'f_price' => $item->f_price,
            'onsale' => $item->data->onsale,
            'sku' => $item->data->sku,
            'image' => $item->data->image,
            'tax' => $item->tax,
            'tax_details' => (array) $item->tax_details,
            'attributes' => $item->data->attributes,
            'params' => $item->data->params,
            'weight' => $item->data->weight,
            'height' => $item->data->height, 
            'width' => $item->data->width,
            'depth' => $item->data->depth, 
            'manufacturer' => $item->data->manufacturer, 
            'origin' => $item->data->origin
        );
        $this->saveData(0, $data);
    }
}
