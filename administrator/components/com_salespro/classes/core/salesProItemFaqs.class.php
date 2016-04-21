<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemFaqs extends salesPro {
    public $_table = '#__spr_item_faqs';
    public $itemid = 0;
    public $_vars = array(
        'id' => array('int', 11),
        'item_id' => array('int', 6), 
        'question' => array('string', 255), 
        'answer' => array('string'), 
        'sort' => array('int', 4)
    );
    public $actions = array('save', 'resort', 'delete');
    function __construct($itemid = 0) {
        parent::__construct();
        if ($itemid > 0) $this->itemid = (int)$itemid;
    }
    function getFaqs() {
        if ($this->itemid > 0) {
            $where = array('item_id' => $this->itemid);
            $sort = array('sort' => 'ASC');
            $object = $this->db->getObjList($this->_table, $this->getVars(), $where, $sort);
            $n = 0;
            foreach ($object as $o) {
                $o->html = "<p class='spr_faq_question' rel='spr_faq{$n}'>{$o->question}</p>";
                $o->html .= "<p class='spr_faq_answer' id='spr_faq{$n}'>{$o->answer}</p>";
                $n++;
            }
        }
        else {
            $object = array();
        }
        return $object;
    }
}
