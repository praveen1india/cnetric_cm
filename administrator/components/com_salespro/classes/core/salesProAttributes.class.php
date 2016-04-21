<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProAttributes extends salesPro {
    public $_vars = array(
        'id' => array('int', 6),
        'name' => array('string', 100),
        'params' => array('json')
    );
    public $_table = '#__spr_attributes';
    public $order = array('sort' => 'name', 'dir' => 'ASC', 'limit' => 20, 'page' => 0, 'total' => 0);
    public $actions = array('getFields', 'save', 'delete');
    function __construct() {
        parent::__construct();
    }
    function getAttributes($catid = 0) {
        if((int) $catid > 0) {
            $attributes = sprAttributesMap::_getAtts($catid);
        } else {
            $attributes = $this->db->getAssocList($this->_table,'id');
        }
        $ret = array();
        if(count($attributes)>0) foreach($attributes as $a) {
            if($attribute = $this->getAttribute($a)) {
                $ret[] = $attribute;
            }
        }
        return $ret;
    }
    function getAttribute($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            return FALSE;
        } else {
            $object->categories = sprAttributesMap::_getCats($object->id);
            $object->category_names = '';
            if(count($object->categories)>0) {
                if(count($object->categories)<3) foreach($object->categories as $n=>$cat) {
                    if($n > 0) $object->category_names .= ', ';
                    $object->category_names .= sprCategories::_load($cat)->name;
                } else {
                    $object->category_names = JText::_('SPR_MULTIPLE_SELECTED');
                }
            } else {
                $object->category_names = JText::_('None selected');
            }
            $object->values = sprAttributesValues::_load($object->id);
        }
        return $object;
    }
    function saveData($id = '', $array = array()) {
        $id = parent::saveData($id, $array);
        //SAVE ATTRIBUTE CATEGORIES
        $cats = (isset($_POST['attribute_categories'])) ? $_POST['attribute_categories'] : array();
        sprAttributesMap::_saveCats($id,$cats);
        
        //SAVE ATTRIBUTE VALUES
        sprAttributesValues::_saveAttr($id);
        return $id;
    }
    
    function deleteData($id='') {
        
        if($id === '') $id = (int)$_POST['spr_id'];
        if(parent::deleteData($id)) {

            //DELETE ATTRIBUTE CATEGORIES
            sprAttributesMap::_delCats($id);
            
            //DELETE ATTRIBUTE VALUES
            sprAttributesValues::_delValues($id);
        }
    }
    
    function ajaxSave() {
        $categories = (isset($_POST['categories'])) ? json_decode($_POST['categories']) : array();
        unset($_POST['categories']);
        
        $ajax = new salesProAjax;
        $ajax->save($this->_table, $this->getVars(), 0);
        
        $id = (int)$ajax->json['id'];
        sprAttributesMap::_saveCats($id,$categories);
        
        $a = $this->getAttribute($id);
        
        $values = '';
        foreach($a->values as $v) {
            $values .= str_replace("'", '"',$v->html);
        }

        $string = "<tr id='spr_attributes_{$a->id}' a_name='{$a->name}' a_categories='".json_encode($a->categories)."' a_values='{$values}'><td class='a_name'>{$a->name}</td><td class='a_cats'>{$a->category_names}</td><td class='nowrap center' width='1%'><a href='#' onclick='editAT({$a->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteAT({$a->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
        $ajax->json['string'] = $string;
        
        $ajax->saveJson();
    }
    
    function ajaxDelete() {

        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        
        $this->deleteData($id);
        
        $ajax = new salesProAjax;
        $ajax->delete($this->_table, $this->getVars(), 0);
        
        $ajax->saveJson();
    }
}

class sprAttributes implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        $attributes = NULL;
        $class = new salesProAttributes;
        if($id > 0) {
            $attributes = $class->getAttribute($id);
        } else if(NULL === $attributes) {
            $attributes = $class->getAttributes();
        }
        return $attributes;
    }

    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProAttributes;
        return $class->selectOptions($selected,$options,$text);
    }
    
    public static function _getName($id=0) {
        $class = new salesProAttributes;
        return $class->getVar('name',$id);
    }
    
    /* FACTORY METHOD TO LOAD A DEFAULT OBJECT */
    public static function _default() {
        static $default = NULL;
        if(NULL === $default) {
            $class = new salesProAttributes;
            $default = $class->getDefaultObject();
            $default->values = array();
        }
        return $default;
    }

    /* FACTORY METHOD TO LOAD VARIABLES FOR SPECIFIC CATEGORY */
    public static function _byCategory($catid=0) {
        $class = new salesProAttributes;
        if($catid > 0) {
            $attributes = $class->getAttributes($catid);
        } else {
            $attributes = array();
        }
        return $attributes;
    }
    
    /* FACTORY METHOD TO REMOVE OLD UNUSED VALUES AT CREATE ATTRIBUTE STAGE */
    public static function _delOldValues() {
        sprAttributesValues::_delValues(0);
    }
}