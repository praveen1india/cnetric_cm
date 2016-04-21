<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemDls extends salesPro {
    public $_table = '#__spr_item_dls';
    public $itemid = 0;
    public $dls;
    public $_vars = array(
        'id' => array('int', 11),
        'item_id' => array('int', 6),
        'name' => array('string', 100),
        'filename' => array('string', 5),
        'ext' => array('string', 4),
        'sort' => array('int', 4),
        'date' => array('datetime'),
        'status' => array('int', 4),
        'days' => array('int', 6),
        'times' => array('int', 6)
    );
    public $actions = array(
        'status',
        'save',
        'resort',
        'delete',
        'uploadFiles'
    );
    public $_dldir = 'dls/';
    function __construct($itemid = 0) {
        parent::__construct();
        if ($itemid > 0) $this->itemid = (int)$itemid;
    }
    function getDls($itemid = 0) {

        //GET ITEM DOWNLOADS
        $object = array();
        if((int) $itemid < 1) $itemid = $this->itemid;
        $where = array('item_id' => $itemid);
        $sort = array('sort' => 'sort','dir'=>'ASC');
        $object = $this->db->getObjList($this->_table, 'id', $where, $sort);
        if (sizeof($object) > 0) foreach ($object as $n=>$o) {
            $object[$n] = $this->getDl($o->id);
        }
        return $object;
    }
    
    function getDl($id=0) {
        if((int)$id < 1) return FALSE;
        $object = $this->db->getObj($this->_table,$this->getVars(),array('id'=>$id));
        if (sizeof($object) < 1) $object = $this->getDefaultObject();
        $object->src = $this->_filebasepath.'/'.$this->_dldir.$object->filename.'.'.$object->ext;
        $status = ($object->status === '1') ? 'yes' : 'no';
        $object->string = "<tr class='ui-sortable-handle' id='spr_item_dls_{$object->id}' d_id='{$object->id}' d_name='{$object->name}' d_ext='.{$object->ext}' d_days='{$object->days}' d_times='{$object->times}' d_status='{$object->status}'><td class='nowrap center' width='1%'><span class='ui-icon ui-icon-arrowthick-2-n-s' style='margin: 0 10px;'>&nbsp;</span></td><td><a href='#' onclick='editDl({$object->id});' class='dl_name'>{$object->name}</a></td><td>.{$object->ext}</td><td class='nowrap center' width='1%'>{$object->days}</td><td class='nowrap center' width='1%'>{$object->times}</td><td class='center'><a class='spr_icon spr_icon_{$status}' id='dlstatus_{$object->id}' onclick='dlStatus({$object->id});' style='margin:0 auto;'>&nbsp;</a></td><td class='nowrap center' width='1%'><a href='#' onclick='editDl({$object->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteDl({$object->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
        return $object;
    }
    
    function ajaxSave() {
        $ajax = new salesProAjax;
        $ajax->save($this->_table, $this->getVars(), 0);
        if($ajax->json['id'] > 0) {
            $id = (int) $ajax->json['id'];
            $dl = $this->getDl($id);
            $ajax->json['string'] = $dl->string;
        }
        $ajax->saveJson();
    }
    
    function ajaxUploadFiles() {
        
        $upload = new salesProUpload;
        
        if (sizeof($_FILES) > 0) {
            $upload = new salesProUpload(0,$this->_dldir);
            $ret = $upload->uploadFile();
            if (!$ret || $ret['error'] != '0' || !isset($ret['filename'])) {
                $this->json['error'] = JText::_('SPR_ITEM_DLS_BADFILETYPE');
            } else {
                $this->json = $ret;
                $data = array(
                    'item_id' => '0', 
                    'name' => $ret['name'],
                    'filename' => $ret['filename'],
                    'ext' => $ret['ext'], 
                    'date' => $this->_dateTime, 
                    'sort' => '0',
                    'status' => '1'
                );
                $id = $this->saveData(0, $data);
                $this->json['id'] = $id;
                $dl = $this->getDl($id);
                $this->json['string'] = $dl->string;
            }
        }
        else {
            $this->json['error'] = JText::_('SPR_ITEM_DLS_NOFILE');
        }
        $this->saveJson();
    }
}