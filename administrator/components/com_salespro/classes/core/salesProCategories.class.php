<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCategories extends salesPro {
    public $_vars = array(
        'id' => array('int', 6),
        'parent' => array('int', 6),
        'level' => array('int', 4),
        'sort' => array('int', 6),
        'name' => array('string', 100),
        'alias' => array('string', 100),
        'image' => array('string', 100),
        'status' => array('int', 4),
        'desc' => array('string'),
        'short_des' => array('string'),
        'meta_title' => array('string', 100),
        'meta_keys' => array('string', 255),
        'meta_desc' => array('string'),
        'params' => array('json')
    );
    public $_table = '#__spr_categories';
    public $layouts = array('list','boxes');
    public $catid = 0;
    public $order = array('sort' => 'sort', 'dir' => 'ASC', 'limit' => 20, 'page' => 0);
    public $actions = array('status','resort');
    public $params = array(
        'layout' => '1',
        'show_title' => '1',
        'show_desc' => '1',
        'show_image' => '1',
        'show_sortbar' => '1',
        'show_pagesbar' => '1',
        'items' => '9',
        'boxcols' => '3',
        'subcats' => '1',
        'subcatitems' => '1',
        'subcategory_levels' => '1'
    );

    function __construct() {
        parent::__construct();
        foreach($this->layouts as $n=>$o) {
            $this->layouts[$n] = JText::_($o);
        }
    }

    function getLayoutName($id = 0) {
        $id = (int)$id;
        return $this->layouts[$id];
    }
    function getCategories($status = 0) {
        $where = ($status === 1) ? array('status' => '1') : array();
        $object = $this->db->getObjList($this->_table, 'id', $where, $this->order);
        if(sizeof($object)>0) foreach($object as $n=>$o) {
            $object[$n] = $this->getCategory($o->id);
        }
        $object = $this->getChildren($object);
        $this->order['total'] = parent::getCount();
        return $object;
    }
    function getCategory($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
            $object->params = (object)$this->params;
            $object->children = array();
        } else {
            $object->image = 'categories/'.$object->image;
            $object->params = $this->getParams($object->params);
            $this->order['limit'] = $object->params->items;
            $object->itemcount = sprItems::_count($id);
        }
        return $object;
    }
    function getChildrenIds($id=0,$levels=10) {
        $res = $this->db->getAssocList($this->_table, 'id', array('parent' => $id));
        $levels = $levels - 1;
        $array = array();
        if(count($res)>0) foreach($res as $r) {
            $id = (int)$r['id'];
            $array[] = $id;
            if($levels > 0) $array = array_merge($array,$this->getChildrenIds($id,$levels));
        }
        return $array;
    }


    function getItems($catid = 0,$limit = 0,$subcat_levels = 0) {
        /*
        $i = new salesProItems($catid,$limit);
        $res = $i->getItems();

        */

        $items = new salesProItems(0,$limit);
        $categories = array();
        if($subcat_levels > 0) $categories = $this->getChildrenIds($catid, $subcat_levels);
        $categories[] = $catid;
        $where = array('z.category_id'=>array("z.category_id"=>$categories));
        $itemids = $items->getSearch($items->_table,$where);
        if(count($itemids)>0) {
            $res = $items->getItems($itemids);
        } else {
            $res = array();
        }
        $this->order = $items->order;
        $this->search = $items->search;
        return $res;
    }
    function showCatOption($c, $cat, $myid = 0, $level = 0) {
        if(!is_array($cat)) $cat = json_decode($cat);
        if(!is_array($cat)) $cat = array($cat);
        if ($myid === $c->id) return;
        $sel = (in_array($c->id,$cat)) ? "selected='selected'" : "";
        $sub = '';
        for ($x = 0; $x < $level; $x++) $sub .= '&mdash;';
        $ret = "<option value='{$c->id}' {$sel}>{$sub} {$c->name}</option>";
        if (isset($c->children) && count($c->children) > 0) {
            $level++;
            foreach ($c->children as $d) $ret .= $this->showCatOption($d, $cat, $myid, $level);
        }
        return $ret;
    }
    function showCat($r,$level=0) {
        $name = '';
        if ($level === 0) {
            $name = $r->name;
        }
        else {
            for ($i = 0; $i < $level; $i++) $name .= '&mdash;';
            $name .= ' ' . $r->name;
        }
        $ayes = ($r->status === '1') ? 'yes' : 'no';
        $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$r->id}' onclick='status({$r->id});' style='margin:0 auto;'>&nbsp;</a>";
        $order = ($this->order['sort'] === 'sort') ? "<span class='ui-icon ui-icon-arrowthick-2-n-s' style='float:left; margin: 0 28px 0 10px;'>&nbsp;</span>" : "<span style='float:left; min-width: 40px; text-align:center;'>{$r->sort}</span>";

        $ret = "<li id='spr_categories_{$r->id}'><div class='category_container'>{$order}<span style='float:left;'><a href='#' onclick='edit({$r->id});'>$name</a></span><span><a href='#' onclick='copy({$r->id})' class='spr_icon spr_icon_copy' title='Copy'>&nbsp;</a>&nbsp;<a href='#' onclick='edit({$r->id})' title='Edit' class='spr_icon spr_icon_edit'>&nbsp;</a>&nbsp;<a title='Delete' href='#' onclick='del({$r->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></span>";
        $ret .= "<span style='width: 30px'>{$status}</span>";
        $ret .= "<span style='padding: 0 30px'>{$r->itemcount}</span>";
        $ret .= "</div>";
        $level++;
        if (count($r->children) > 0) {
            $x = $level%2;
            $ret .= "<ul class='categories cat{$x}'>";
            foreach ($r->children as $c) {
                $ret .=$this->showCat($c, $level);
            }
            $ret .= "</ul>";
        }
        $ret .= "</li>";
        return $ret;
    }
    function saveData($id = '', $array = array()) {
        //UPLOAD CATEGORY IMAGE
        if (isset($_FILES['spr_categories_image']) && $_FILES['spr_categories_image']['size'] > 0) {
            $upload = new salesProUpload(1);
            $ret = $upload->uploadFile('spr_categories_image');
            if (!$ret || $ret['error'] != '0' || !isset($ret['filename'])) return false;
            $filename = $ret['filename'].'.'.$ret['ext'];
            $_POST['spr_categories_image'] = $filename;
        }
        //FIX SORTING
        if($id === '') {
            $id = (int)$_POST['spr_id'];
            $db = JFactory::getDBO();
            $query = 'UPDATE '.$db->quoteName($this->_table).' SET '.$db->quoteName('sort').' = '.$db->quoteName('sort').' + 1';
            $db->setQuery($query);
            $db->query();
        }
        parent::saveData($id, $array);
    }
}

class sprCategories implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $classes = NULL;
        $class = new salesProCategories;
        if($id > 0) return $class->getCategory($id);
        else {
            if(NULL === $classes) {
                $classes = $class->getCategories();
            }
            return $classes;
        }
    }

    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProCategories;
        return $class->selectOptions($selected,$options,$text);
    }

    public static function _showOptions($c, $cat) {
        $class = new salesProCategories;
        $ret = $class->showCatOption($c,$cat);
        return $ret;
    }

    public static function _getSubCategories($parent = 0,$levels = 1) {
        $class = new salesProCategories;
        $ret = array();
        $subcats = $class->getChildrenIds($parent,$levels);
        if(count($subcats)>0) foreach($subcats as $s) {
            $ret[] = $class->getCategory($s);
        }
        return $ret;
    }

    public static function _getBreadCrumbs($catid=0,$active=1) {
        $class = new salesProCategories;
        $ret = '';
        $array = array();
        $done = 0;
        $id = $catid;
        do {
            $cat = $class->getCategory($id);
            if(isset($cat->id)) {
                $element = array(
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'alias' => $cat->alias);
                array_unshift($array,$element);
            } else {
                break;
            }
            if($cat->parent < 1) {
                $done = 1;
            }
            $id = $cat->parent;
        } while ($done === 0);
        foreach($array as $cat) {
            if($cat['id'] == $catid && $active !== 0) {
                $myclass =  'class="moderna_active_breadcrumb"';
                $link = $cat['name'];
            } else {
                $myclass = '';
                $link = sprCategories::_directLink($cat['id'],$cat['name'],$cat['alias']);
                $link = "<a href='{$link}'>{$cat['name']}</a>";
            }
            $ret .= "<li {$myclass}>{$link}</li>";
        }
        $link = JRoute::_('index.php?option=com_salespro&view=home');
        $ret = "<li><a href='{$link}'>".JText::_('SPR_HOME')."</a></li>{$ret}";
        return $ret;
    }
    /* FACTORY METHOD TO GET A CATEGORY NAME */
    public static function _getName($id=0) {
        if($id === 0) return '';
        $class = new salesProCategories;
        return $class->getVar('name',$id);
    }
    /* FACTORY METHOD TO GET A CATEGORY ALIAS */
    public static function _getAlias($id=0) {
        if($id === 0) return '';
        $class = new salesProCategories;
        return $class->getVar('alias',$id);
    }

    /* FACTORY METHOD TO GET SINGLE CATEGORY ITEMS */
    public static function _getItems($category = 0, $order=array()) {

/*
        $class = new salesProCategories;
        $myorder = $class->order;
        foreach($myorder as $field=>$val) {
            if(isset($order[$field])) {
                $myval = $order[$field];
                $myorder[$field] = $myval;
            }
        }
*/

        $itemids = sprItems::getByCatID($category, $myorder);
        $res = array();
        if(count($itemids)>0) foreach($itemids as $id) {
            $res[] = sprItems::_load($id);
        }
        return $res;
    }

    public static function _directLink($id=0,$name='',$alias='') {

        $name = JFilterOutput::stringURLSafe($name);
        $alias = JFilterOutput::stringURLSafe($alias);
        $direct_link = 'index.php?option=com_salespro&view=category&id=' . $id;
        if(!empty($alias)) $direct_link .= '&name='.$alias;
        else $direct_link .= '&name='.$name;
        $direct_link = JRoute::_($direct_link);
        return $direct_link;
    }
}