<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemVideos extends salesPro {
    public $_table = '#__spr_item_videos';
    public $itemid = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'item_id' => array('int', 6),
        'url' => array('string', 100),
        'height' => array('int', 6),
        'width' => array('int', 6),
        'sort' => array('int', 4)
    );
    public $actions = array('resort', 'save', 'delete');
    function __construct($itemid = 0) {
        parent::__construct();
        if ($itemid > 0) $this->itemid = (int)$itemid;
    }
    function getVideos() {
        if ($this->itemid > 0) {
            $where = array('item_id' => $this->itemid);
            $sort = array('sort' => 'ASC');
            $object = $this->db->getObjList($this->_table, $this->getVars(), $where, $sort);
            foreach ($object as $o) {
                $o->url = str_replace('watch?v=', 'embed/', $o->url);
                $o->html = "<iframe width='{$o->width}' height='{$o->height}' src='{$o->url}' frameborder='0' allowfullscreen></iframe>";
            }
        }
        else {
            $object = array();
        }
        return $object;
    }
}