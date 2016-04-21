<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProWidgets extends salesPro {
    public $_table = '#__spr_widgets';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'name' => array('string', 255),
        'type' => array('string', 255),
        'params' => array('json'),
        'views' => array('json'),
        'sort' => array('int', 3),
        'status' => array('int',1)
        );
    public $params = array(
        'showtitle' => '1',
        'btn' => '1',
        'cols' => '3',
        'count' => '6',
        'layout' => '0'
    );
    public $views = array(
        'home' => '1',
        'category' => '2',
        'item' => '2',
        'basket' => '2',
        'checkout' => '2',
        'thankyou' => '2',
    );
    public $order = array('sort' => 'sort', 'dir' => 'ASC', 'limit' => 20, 'page' => 0, 'total' => 0);
    public $actions = array('status','resort','delete');
    function __construct() {
        parent::__construct();
    }
    
    function getWidgets($view='') {
        $where = array();
        if($view != '') $where = array('views'=>"LIKE%\"{$view}\":\"1\"%");
        $object = $this->db->getObjList($this->_table,'id',$where,$this->order);
        if(count($object)>0) foreach($object as $n=>$o) {
            $object[$n] = $this->getWidget($o->id);
        }
        return $object;
    }
    function getWidget($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        $object->params = $this->getParams($object->params);
        $object->views = $this->getParams($object->views,$this->views);
        return $object;
    }
    function saveData($id = '', $array = array()) {
        $id = parent::saveData($id, $array);
        if(isset($_POST['spr_widgets_type'])) {
            $this->redirect('index.php?option=com_salespro&view=widgets&layout=edit&id='.$id);
        }
    }
}

class sprWidgets {
    
    public static function _showWidgets($view='') {
        if($view === '') return FALSE;
        $class = new salesProWidgets;
        $widgets = $class->getWidgets($view);
        $ret = '';
        foreach($widgets as $w) {
            if($w->status !== '1') continue;
            $ret .= sprWidgets::_showWidget($w);
        }
        return $ret;
    }
    
    public static function _showWidget($w,$params=array(),$items=array()) {
        $ret = '';
        if(count($params) === 0) $params = $w->params;
        $params = (object)$params;
        switch($w->type) {
            case 'featured':
                if(count($items)<1) {
                    $catid = (isset($params->catid)) ? (int)$params->catid : 0;
                    $page = (isset($params->page)) ? (int)$params->page : 0;
                    $limit = (isset($params->count)) ? (int)$params->count : 20;
                    $featured = sprItems::getFeaturedIds($catid,$page,$limit);
                } else {
                    $featured = $items;
                }
                if(count($featured)>0) {
                    $ret .= "<div class='spr_featured_items_widget'>";
                    if($params->showtitle === '1') $ret .= "<br /><h3>{$w->name}</h3><hr />";
                    if(isset($params->layout) && $params->layout == '0') $ret .= "<div class='spr_featured_items_list_widget'>".sprWidgets::showItemList($featured,$params)."</div></div>";
                    else $ret .= "<div class='spr_featured_items_grid_widget'>".sprWidgets::showItemGrid($featured,$params)."</div></div>";
                }
                break;
            case 'new':
                $ret = '';
                if(count($items)<1) {
                    $catid = (isset($params->catid)) ? (int)$params->catid : 0;
                    $page = (isset($params->page)) ? (int)$params->page : 0;
                    $limit = (isset($params->count)) ? (int)$params->count : 20;
                    $newitems = sprItems::getNewIds($catid,$page,$limit);
                } else {
                    $newitems = $items;
                }
                if(count($newitems)>0) {
                    $ret .=  "<div class='spr_new_items_widget'>";
                    if($params->showtitle === '1') $ret .=  "<br /><h3>{$w->name}</h3><hr />";
                    if(isset($params->layout) && $params->layout == '0') $ret .=  "<div class='spr_new_items_list_widget'>".sprWidgets::showItemList($newitems,$params)."</div></div>";
                    else $ret .=  "<div class='spr_new_items_grid_widget'>".sprWidgets::showItemGrid($newitems,$params)."</div></div>";
                }
                break;
            case 'catItems':
                $catid = (isset($params->catid)) ? (int)$params->catid : 0;
                $catitems = (count($items)<1) ? sprItems::getNewIds($catid) : $items;
                if(count($catitems)>0) {
                    $ret .=  "<div class='spr_cat_items_widget'>";
                    if(isset($params->layout) && $params->layout == '0') $ret .=  "<div class='spr_cat_items_list_widget'>".sprWidgets::showItemList($catitems,$params)."</div></div>";
                    else $ret .=  "<div class='spr_cat_items_grid_widget'>".sprWidgets::showItemGrid($catitems,$params)."</div></div>";
                }
                break;
            case 'categories':
                if(isset($params->id)) {
                    $id = (int)$params->id;
                    $levels = (isset($params->levels)) ? (int) $params->levels : 1;
                    $categories = sprCategories::_getSubCategories($id,$levels);
                } else {
                    $categories = sprCategories::_load();
                }
                if(count($categories)>0) {
                    $ret .=  "<div class='spr_categories_widget'>";
                    if($params->showtitle === '1') $ret .=  "<br /><h3>{$w->name}</h3><hr />";
                    $ret .=  "<div class='spr_categories_widget_grid'>".sprWidgets::showCategoryGrid($categories, $params)."</div></div>";
                }
                break;
            case 'showcase': 
                if(count($items)<1) {
                    $catid = (isset($params->catid)) ? (int)$params->catid : 0;
                    $page = (isset($params->page)) ? (int)$params->page : 0;
                    $limit = (isset($params->count)) ? (int)$params->count : 20;
                    $featured = sprItems::getFeaturedIds($catid,$page,$limit);
                } else {
                    $featured = $items;
                }
                if(count($featured)>0) {
                $ret .=  "<div class='spr_showcase_items_widget'>";
                if($params->showtitle === '1') $ret .=  "<br /><h3>{$w->name}</h3><hr />";
                $ret .=  "<div class='spr_showcase_items_widget_container' style='position: relative;'><ul class='rslides' style='overflow: visible;'>";
                foreach($featured as $f) $ret .=  "<li class='rslide'>".sprWidgets::showShowcaseItem($f,$params)."</li>";
                $ret .=  "</ul></div></div>";
                }
                break;
            case 'random':
                $ret = '';
                if(count($items)<1) {
                    $catid = (isset($params->catid)) ? (int)$params->catid : 0;
                    $page = (isset($params->page)) ? (int)$params->page : 0;
                    $limit = (isset($params->count)) ? (int)$params->count : 20;
                    $rnditems = sprItems::getRandomIds($limit);
                } else {
                    $rnditems = $items;
                }
                if(count($rnditems)>0) {
                    $ret .=  "<div class='spr_random_items_widget'>";
                    if($params->showtitle === '1') $ret .=  "<br /><h3>{$w->name}</h3><hr />";
                    if(isset($params->layout) && $params->layout == '0') $ret .=  "<div class='spr_random_items_list_widget'>".sprWidgets::showItemList($rnditems,$params)."</div></div>";
                    else $ret .=  "<div class='spr_random_items_grid_widget'>".sprWidgets::showItemGrid($rnditems,$params)."</div></div>";
                }
                break;
        }
        return $ret;
    }
    
    //VERY USEFUL FUNCTION TO CREATE A NEW WIDGET ON THE FLY WITH GIVEN NAME, TYPE AND PARAMS
    public static function showNewWidget($name='',$type='',$params=array(),$items=array()) {
        $class = new salesProWidgets;
        foreach($class->params as $field=>$val) {
            if(!isset($params[$field])) $params[$field] = $val;
        }
        $widget = array('name' => $name, 'type' => $type, 'params' => $params);
        $widget = json_decode(json_encode($widget));
        return sprWidgets::_showWidget($widget, $params, $items);
    }
    
    public static function showCategoryGrid($cats,$params) {

        $grid = "<div class='spr_widget_category_grid_container'><div class='spr_widget_category_grid'>";
        $boxwidth = 100/$params->cols;
        foreach($cats as $n=>$i) {
            $x = $n%$params->cols;
            if($x === 0) $grid .= "<div class='spr_widget_category_grid_row'>";
            $grid .= sprWidgets::showCategory($i,$boxwidth,$params);
            if(($params->count > 0 && $n >= ($params->count-1)) || $n === count($cats)-1) {
                if($x === ($params->cols - 1)) $grid .= "</div>";
                else {
                    $y = $params->cols - $x - 1;
                    for($i = 0; $i < $y; $i++) {
                        $grid .= "<div class='spr_widget_category_empty_cell' style='min-width: {$boxwidth}%; display: inline-block;'></div>";
                    }
                    $grid .= "</div>";
                }
                break;
            }
            elseif($x === ($params->cols - 1)) $grid .= "</div>";
        }
        $grid .= "</div></div>";
        return $grid;
    }
    
    public static function showItemGrid($items,$params) {
        
        $grid = "<div class='spr_widget_item_grid_container'><div class='spr_widget_item_grid'>";
        $boxwidth = 100/$params->cols;
        foreach($items as $n=>$i) {
            if(is_object($i)) $i = $i->id;
            $x = $n%$params->cols;
            if($x === 0) $grid .= "<div class='spr_widget_item_grid_row'>";
            $grid .= sprWidgets::showItem($i,$boxwidth,$params);
            if(($params->count > 0 && $n >= ($params->count - 1)) || $n === count($items)-1) {
                if($x === ($params->cols - 1)) $grid .= "</div>";
                else {
                    $y = $params->cols - $x - 1;
                    for($i = 0; $i < $y; $i++) {
                        $grid .= "<div class='spr_widget_item_empty_cell' style='min-width: {$boxwidth}%'></div>";
                    }
                    $grid .= "</div>";
                }
                break;
            }
            elseif($x === ($params->cols - 1)) $grid .= "</div>";
        }
        $grid .= "</div></div>";
        return $grid;
    }
    
    public static function showItemList($items,$params) {
        
        $grid = "<div class='spr_widget_item_list_container'><div class='spr_widget_item_list'>";
        foreach($items as $n=>$i) {
            if(is_object($i)) $i = $i->id;
            $grid .= sprWidgets::showListItem($i,$params);
        }
        $grid .= "</div></div>";
        return $grid;
    }
    
    public static function showCategory($category='', $boxwidth = 30, $params = array(), $imgwidth = 300, $imgheight = 300) {
        $i = $category;
        $link = sprCategories::_directLink($i->id,$i->name,$i->alias);
        $ret = "<div  class='spr_widget_category_cell' style='width: {$boxwidth}%;'>
            <a href='{$link}'>
                <div class='spr_widget_category_img'>
                    <img src='".salesProImage::_($i->image,$imgwidth, $imgheight)."' />
                </div>";
        if($params->btn === '1') $ret .= "<div class='spr_widget_category_name'>{$i->name}</div>";
        $ret .= "</a></div>";
        return $ret;
    }
     
    public static function showItem($id=0, $boxwidth = 30, $params = array(), $imgwidth = 300, $imgheight = 300) {
        if((int)$id === 0) return FALSE;
        $i = sprItems::_load($id);
        $link = sprItems::_directLink($id,$i->name,$i->alias,$i->category,$i->category_name,$i->category_alias);
        $ret = "
        <div class='spr_widget_item_cell' style='width: {$boxwidth}%;'>
            <a href='{$link}'>
                <div class='spr_widget_item_img'>
                    <img src='".salesProImage::_($i->mainimage,$imgwidth, $imgheight)."' />
                </div>
                <div class='spr_widget_item_details'>";
        if($params->btn === '1') {
            $ret .= "<div class='spr_widget_item_link_holder'>
                        <div class='spr_widget_item_link'>".JText::_('SPR_DETAILS')."</div>
                    </div>";
        }
        $ret .= "
                    <div class='spr_widget_item_name'>{$i->name}</div>
                    <div class='spr_widget_item_price'>{$i->f_price}</div>
                </div>
            </a>
        </div>";
        return $ret;
    }
    
    public static function showListItem($id=0, $params = array(), $imgwidth = 200, $imgheight = 200) {
        
        if((int)$id === 0) return FALSE;
        $i = sprItems::_load($id);
        $link = sprItems::_directLink($id,$i->name,$i->alias,$i->category,$i->category_name,$i->category_alias);
        $ret = "
        <div class='spr_widget_listitem'>
            <a href='{$link}'>
            <div class='spr_widget_listitem_img'>
                <img src='".salesProImage::_($i->mainimage,$imgwidth,$imgheight)."' />
            </div>
            <div class='spr_widget_listitem_details'>
                <div class='spr_widget_listitem_price'>{$i->f_price}</div>";
        if($params->btn === '1') $ret .= "<div class='spr_widget_listitem_button'>".JText::_('SPR_DETAILS')."</div>";
        $ret .= "</div>
            <div class='spr_widget_listitem_info'>
                <div class='spr_widget_listitem_name'>{$i->name}</div>
                <div class='spr_widget_listitem_desc'>{$i->mini_desc}</div>
            </div>
            </a>
        </div>";
        return $ret;
    }

    public static function showShowcaseItem($id=0, $params = array(), $imgwidth = 0, $imgheight = 300) {
        if((int)$id === 0) return FALSE;
        $i = sprItems::_load($id);
        $link = sprItems::_directLink($id,$i->name,$i->alias,$i->category,$i->category_name,$i->category_alias);
        $ret = "
        <div class='spr_widget_showcase_item_cell' style='height: 300px'>
            <div class='spr_widget_showcase_item_img'>
                <img src='".salesProImage::_($i->mainimage,$imgwidth, $imgheight)."' />
            </div>
            <div class='spr_widget_tint'>            
                <div class='spr_widget_showcase_item_details'>
                    <div class='spr_widget_showcase_item_name'>{$i->name}</div>
                    <div class='spr_widget_showcase_item_desc'>{$i->mini_desc}</div>
                    <div class='spr_widget_showcase_item_link'>
                        <div class='spr_widget_showcase_item_price' style='float:left'>{$i->f_price}</div>";
        if($params->btn === '1') $ret .= "<div class='spr_widget_item_link_holder' style='margin: 0; top: 0;'><a href='{$link}' style='overflow: auto; clear: both; display: block;'><div class='spr_widget_item_link' style='margin: 20px 10px;'>".JText::_('SPR_DETAILS')."</div></a></div>";
        $ret .= "</div>
                </div>
            </div>            
        </div>";
        return $ret;
    }
}

