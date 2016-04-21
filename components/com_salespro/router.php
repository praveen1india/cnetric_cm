<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die( 'Restricted access' );

function salesProBuildRoute(&$query) {

    //GET THE DEFAULT MENU ITEM
    $app = JFactory::getApplication(); 
    $menu = $app->getMenu();
    $menuItem = $menu->getItems('link', 'index.php?option=com_salespro&view=home', true);
    if(!empty($menuItem)) {
        $home = $menuItem->id;
    }
    
    //FORCE SALES PRO COMPONENT
    $query['option'] = 'com_salespro';
    
    //RETURN THE DEFAULT MENU ITEM IF NO VIEW IS SET
    if(!isset($query['view']) || $query['view'] === 'home') {
        if(!isset($query['Itemid']) && isset($home)) {
            $query['Itemid'] = $home;
        }
        return array();
    }
    
    //CHECK VIEW AGAINST KNOWN MENU ITEMS    
    $stdlink = 'index.php?option=com_salespro&view='.$query['view'];
    if(isset($query['id'])) $stdlink .= '&id='.$query['id'];
    $menuItem = $menu->getItems('link', $stdlink, true);
    if(!empty($menuItem)) {
        unset($query['view']);
        $query['option']='com_salespro';
        $query['Itemid']=$menuItem->id;
        return array();
    }
    
    //GET THE CURRENT MENU ITEM - USE HOME AS BASE IF INVALID
    $mymenu = $menu->getActive();
    if(isset($mymenu->query['id']) && isset($query['id']) && $mymenu->query['id'] !== $query['id'] && isset($home)) {
        $query['Itemid'] = $home;
    }
    if(isset($mymenu->query['view']) && $mymenu->query['view'] !== $query['view'] && isset($home)) {
        $query['Itemid'] = $home;
    }
    if(!isset($query['Itemid']) && isset($home)) $query['Itemid'] = $home;
    
    //BUILD THE ROUTER SEGMENTS
    $segments = array();
    if(isset($query['view'])) {
        if($query['view'] !== 'category' && $query['view'] !== 'item') $segments[] = $query['view'];
        unset($query['view']);
    }
    if(isset($query['category'])) {
        $alias = JFilterOutput::stringURLSafe($query['category']);
        if(isset($query['catid'])) {
            $alias = $query['catid'].':'.$alias;
        }
        $segments[] = $alias;
        unset($query['category']);
    }
    if(isset($query['name'])) {
        $alias = JFilterOutput::stringURLSafe($query['name']);
        if(isset($query['id'])) {
            $alias = $query['id'].':'.$alias;
        }
        $segments[] = $alias;
        unset($query['name']);
    }
    if(isset($query['spr_page'])) {
        $segments[] = $query['spr_page'];
        unset($query['spr_page']);
    }
    if(isset($query['spr_sort']) && isset($query['spr_dir'])) {
        $segments[] = $query['spr_sort'].':'.$query['spr_dir'];
        unset($query['spr_sort'],$query['spr_dir']);
    }
    if(isset($query['spr_search_name'])) {
        $segments[] = $query['spr_search_name'];
        unset($query['spr_search_name']);
    }
    unset($query['id'],$query['catid']);
    return $segments;
}

function salesProParseRoute($segments) {
    $vars = array();
    
    //ITEM VIEW
    if(count($segments) === 2) {
        $vars['view'] = 'item';
        $vars['category'] = $segments[0];
        $alias = explode(':',$segments[1]);
        $vars['id'] = array_shift($alias);
        $vars['name'] = implode('-',$alias);
    //OTHER VIEWS
    } elseif ($segments[0] === 'basket') {
        $vars['view'] = 'basket';
    } elseif ($segments[0] === 'checkout') {
        $vars['view'] = 'checkout';
    } elseif ($segments[0] === 'login') {
        $vars['view'] = 'login';
    } elseif ($segments[0] === 'thankyou') {
        $vars['view'] = 'thankyou';
    } elseif ($segments[0] === 'downloads') {
        $vars['view'] = 'downloads';
    //CATEGORY VIEW
    } else {
        $vars['view'] = 'category';
        $alias = explode(':',$segments[0]);
        $vars['id'] = array_shift($alias);
        $vars['name'] = implode('-',$alias);
        if(isset($segments[1])) $vars['spr_page'] = $segments[1];
        if(isset($segments[2])) {
            $sort = explode(':',$segments[2]);
            if(count($sort)===2) {
                $vars['spr_sort'] = $sort[0];
                $vars['spr_dir'] = $sort[1];
            }
        }
        if(isset($segments[3])) {
            $vars['spr_search_name'] = $segments[3];
        }
    }
    return $vars;
}