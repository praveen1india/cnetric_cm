<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProTemplates extends salesPro {
    public $_vars = array(
        'id' => array('int', 11),
        'name' => array('string', 50),
        'alias' => array('string', 50),
        'default' => array('int', 4),
        'params' => array('json')
    );
    public $_table = '#__spr_templates';
    public $order = array(
        'sort' => 'name',
        'dir' => 'ASC',
        'limit' => 20,
        'page' => 0,
        'total' => 0
    );
    public $params = array(
        'color' => '0'
    );
    public $actions = array('setDefault');
    function __construct() {
        parent::__construct();
    }
    function getTemplate($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
            $object->params = (object)$this->params;
        } else {
            $object->params = $this->getParams($object->params);
        }
        $object->color = $object->params->color;
        //GET POTENTIAL COLOUR SCHEMES FOR THIS TEMPLATE
        $object->colors = array();
        $dirPath = $this->_sitepath . 'templates/' . $object->alias . '/css/colors/';
        if (is_dir($dirPath)) {
            $files = glob($dirPath . '*.css', GLOB_MARK);
            foreach ($files as $file) {
                $path = pathinfo($file);
                $object->colors[] = $path['filename'];
            }
        }
        return $object;
    }
    function getTemplates() {
        $object = $this->db->getObjList($this->_table, 'id');
        if (sizeof($object) > 0) foreach($object as $n=>$o) {
            $object[$n] = $this->getTemplate($o->id);
        }
        return $object;
    }
    function deleteData($id = 0) {
        if($id === 0) $id = (int)$_POST['spr_id'];
        if ($id > 0) {
            $tpl = $this->getDefault();
            if($tpl->id == $id) {
                JFactory::getApplication()->enqueueMessage(JText::_('You cannot delete the default template'));
                return FALSE;
            }
            return parent::deleteData();
        }
    }
    function getDefault() {
        $id = $this->db->getResult($this->_table, 'id', array('default' => '1'));
        if ((int) $id < 1) {
            $id = $this->db->getResult($this->_table, 'id');
            $this->setDefault($id);
        }
        $object = $this->getTemplate($id);
        return $object;
    }
    function ajaxDefault($id = 0) {
        $this->db->updateData($this->_table, array('default' => 0));
        $this->db->updateData($this->_table, array('default' => 1), array('id' => $id));
    }
}
