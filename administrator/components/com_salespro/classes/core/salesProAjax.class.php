<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

//CHECK SAFE MODE
if (ini_get('safe_mode')) die(JText::_('SPR_AJAX_SAFEMODE'));
//CHECK TIMEZONE
if (!ini_get('date.timezone')) date_default_timezone_set('UTC');
//CHECK THE MEMORY
$temp = (int)ini_get('memory_limit');
if ($temp <= 128) {
    ini_set('memory_limit', '128M');
    $temp = (int)ini_get('memory_limit');
}
if ($temp < 128) die(JText::_('SPR_AJAX_MEMORY'));
//SET THE TIMEOUT LIMIT
set_time_limit(6000);
class salesProAjax extends salesPro {
    public $max_upload = 0;
    public $max_post = 0;
    public $memory_limit = 0;
    public $upload_limit = 0;
    public $json = array();
    function __construct() {
        parent::__construct();
        //CHECK UPLOAD LIMIT
        $this->max_upload = (int)ini_get('upload_max_filesize');
        $this->max_post = (int)ini_get('post_max_size');
        $this->memory_limit = (int)ini_get('memory_limit');
        $this->upload_limit = min($this->max_upload, $this->max_post, $this->memory_limit);
        $this->upload_limit = ($this->upload_limit * 1024 * 1024);
    }
    function checkAjax() {
        $this->json['data'] = '1';
        $this->saveJson();
    }
    function resort($table = '', $goodVars = array(), $saveJson = 1) {
        
        if (!in_array('sort', $goodVars)) return false;
        $tab = str_replace('#__', '', $table);
        if(isset($_POST['order'])) {
            parse_str($_POST['order']);
            $_POST[$tab] = $$tab;
        }
        
        $add = 0;
        if(isset($_POST['page']) && isset($_POST['limit'])) {
            $add = $_POST['page'] * $_POST['limit'];
        }
        if (isset($_POST[$tab])) {
            $data = $_POST[$tab];
            foreach ($data as $order => $id) {
                $order = $order + $add;
                $this->db->updateData($table, array('sort' => $order), array('id' => $id));
            }
        }
        $this->json['id'] = $id;
        if($saveJson === 1) $this->saveJson();
    }
    function status($table = '', $goodVars = array(), $saveJson = 1) {
        if (!in_array('status', $goodVars)) return false;
        $tab = str_replace('#__', '', $table);
        $res = '1';
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $res = $this->db->getResult($table, 'status', array('id' => $id));
            $res = ($res === '1') ? '2' : '1';
            $this->db->updateData($table, array('status' => $res), array('id' => $id));
        }
        $this->json['status'] = $res;
        if($saveJson === 1) $this->saveJson();
    }
    function setDefault($table = '', $goodVars = array(), $saveJson = 1) {
        if (!in_array('default', $goodVars)) return false;
        $tab = str_replace('#__', '', $table);
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $this->db->updateData($table, array('default' => 0), array('id' => '!=' . $id));
            $this->db->updateData($table, array('default' => 1), array('id' => $id));
            $this->json['id'] = $id;
            if($saveJson === 1) $this->saveJson();
        }
    }
    function featured($table = '', $goodVars = array(), $saveJson = 1) {
        if (!in_array('featured', $goodVars)) return false;
        $tab = str_replace('#__', '', $table);
        $res = '1';
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
            $res = $this->db->getResult($table, 'featured', array('id' => $id));
            $res = ($res === '1') ? '2' : '1';
            $this->db->updateData($table, array('featured' => $res), array('id' => $id));
        }
        $this->json['featured'] = $res;
        if($saveJson === 1) $this->saveJson();
    }
    function save($table = '', $goodVars = array(), $saveJson = 1) {
        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        $data = array();
        if (count($goodVars) < 1) {
            $this->error(JText::_('SPR_AJAX_NOFIELDS'));
        }
        //VALIDATE THE POST DATA AND LOOP INTO AN ARRAY
        foreach ($_POST as $field => $var) {
            if (!in_array($field, $goodVars)) {
                $this->error(JText::_('SPR_AJAX_BADFIELDS'));
            }
            if(is_array($var)) $var = json_encode($var);
            $data[$field] = $var;
        }
        if (count($data) < 1) {
            $this->error(JText::_('SPR_AJAX_NODATA'));
        }
        //CHECK IF THIS IS AN INSERT OR AN UPDATE... THEN RUN IT
        if (!$this->db->getResult($table, 'id', array('id' => $id))) {
            $id = $this->db->insertData($table, $data);
        }
        else {
            $this->db->updateData($table, $data, array('id' => $id));
        }
        $this->json['id'] = $id;
        if($saveJson === 1) $this->saveJson();
    }
    function delete($table = '', $goodVars = array(), $saveJson = 1) {
        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        $this->db->deleteData($table, array('id' => $id));
        $this->json['id'] = $id;
        if($saveJson === 1) $this->saveJson();
        return $id;
    }
}
