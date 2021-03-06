<?php
/**
* @version      3.15.0 02.11.2010
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

class jshopProductLabel extends JTable{

    function __construct( &$_db ){
        parent::__construct('#__jshopping_product_labels', 'id', $_db);
    }

    function getListLabels(){
        $lang = JSFactory::getLang();
        $db = JFactory::getDBO();
        $query = "SELECT id, image, `".$lang->get("name")."` as name FROM `#__jshopping_product_labels` ORDER BY name";
        $db->setQuery($query);
        $list = $db->loadObjectList();
        $rows = array();
        foreach($list as $row){
            $rows[$row->id] = $row;
        }
    return $rows;
    }
}
?>