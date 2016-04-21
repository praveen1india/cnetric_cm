<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemImages extends salesPro {
    public $_table = '#__spr_item_images';
    public $_imgdir = 'images/salesPro/';
    public $itemid = 0;
    public $images;
    public $mainimage;
    public $_vars = array(
        'id' => array('int', 11),
        'item_id' => array('int', 6),
        'name' => array('string', 100),
        'ext' => array('string', 4),
        'sort' => array('int', 4),
        'date' => array('datetime'),
        'status' => array('int', 4),
        'title' => array('string',100),
        'desc' => array('string')
    );
    public $default_img = 'default/default.jpg';
    public $actions = array(
        'resort',
        'delete',
        'save',
        'uploadImage'
    );
    function __construct($itemid = 0) {
        parent::__construct();
        $this->_imgdir = sprConfig::_load('images')->loc;
        if ($itemid > 0) $this->itemid = (int)$itemid;
    }
    function getImages($itemid=0,$usedefault=1) {
        
        if ($itemid === 0) $itemid = $this->itemid;

        //GET ITEM IMAGES
        $object = array();
        $where = array('item_id' => $itemid);
        $sort = array('sort' => 'sort','dir'=>'ASC');            
        $object = $this->db->getObjList($this->_table, $this->getVars(), $where, $sort);
        if (sizeof($object) > 0) foreach ($object as $o) {
            $file = $o->name . '.' . $o->ext;
            $o->filename = 'items/' . $file;
            $o->src = JURI::root(). $this->_imgdir.$o->filename;
            $o->html = "<img src='{$o->src}' />";
        } else if($usedefault === 1) {
            $object = array();
            $object[0] = $this->getDefaultObject();
            $object[0]->filename = $this->default_img;
            $object[0]->src = JURI::root(). $this->_imgdir.$this->default_img;
            $object[0]->html = "<img src='{$object[0]->src}' />";
        }
        $this->images = $object;
        return $this->images;
    }
    function getImage($id=0) {
    
        $where = array('id'=>$id);
        $object = $this->db->getObj($this->_table, $this->getVars(), $where);
        if (sizeof($object) > 0) {
            $file = $object->name . '.' . $object->ext;
            $filename = 'items/' . $file;
            $object->image = $filename;
        } else {
            $object = $this->getDefaultObject();
            $object->image = $this->_default_img;
        }
        return $object;
    }
    function getMainImage($item_id=0) {
        if((int) $item_id === 0) $item_id = $this->itemid;
        $this->mainimage = $this->_default_img;
        $where = array('item_id' => $item_id);
        $sort = array('sort' => 'sort','dir'=>'ASC');
        $object = $this->db->getObj($this->_table, $this->getVars(), $where, $sort);        
        if(isset($object->name)) {
            $file = $object->name . '.' . $object->ext;
            $filename = 'items/' . $file;            
            $this->mainimage = $filename;
        }
        return $this->mainimage;
    }
    function ajaxUploadImage() {
        
        $upload = new salesProUpload;
        
        if (sizeof($_FILES) > 0) {
            $upload = new salesProUpload(0);
            $ret = $upload->uploadFile();
            if (!$ret || $ret['error'] != '0' || !isset($ret['filename'])) return false;
            $this->json = $ret;
            $filename = $ret['filename'];
            $images = new salesProItemImages;
            $data = array(
                'item_id' => '0', 
                'name' => $ret['filename'],
                'ext' => $ret['ext'], 
                'date' => $this->_dateTime, 
                'sort' => '0',
                'status' => '0'
            );
            $id = $images->saveData(0, $data);
            $this->json['id'] = $id;
        }
        else {
            $this->json['error'] = JText::_('SPR_ITEM_IMGS_NOFILE');
        }
        $this->saveJson();
    }
}

class sprItemImages {
    
    public static function _getMainImage($item_id) {
        $class = new salesProItemImages;
        $image = $class->getMainImage($item_id);
        return $image;
    }
    
    public static function _getImage($id) {
        $class = new salesProItemImages;
        $image = $class->getImage($id);
        return $image;
    }
    
    public static function _getImages($item_id = 0, $usedefault=1) {
        $class = new salesProItemImages;
        $images = $class->getImages($item_id, $usedefault);
        return $images;
    }
}