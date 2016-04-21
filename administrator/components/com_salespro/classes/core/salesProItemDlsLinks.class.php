<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemDlsLinks extends salesPro {

    public $_table = '#__spr_item_dls_links';
    public $_vars = array(
        'id' => array('int', 11),
        'item_id' => array('int', 6),
        'user_id' => array('int', 11),
        'dl_id' => array('int', 6),
        'time' => array('int', 11),
        'expiry' => array('int', 11),
        'hash' => array('varchar', 20),
        'dls' => array('int', 11),
        'last_ip' => array('varchar', 16)
    );
    
    function __construct() {
        parent::__construct();
    }

    /* FACTORY METHOD TO GENERATE A DOWNLOAD LINK */
    public function genLink($item_id = 0, $user_id = 0, $dl_id=0) {
        foreach(func_get_args() as $arg) {
            if((int)$arg <= 0) return FALSE;
        }
        $parent = new salesProItemDls;
        $dl = $parent->getDl($dl_id);
        $time = time();
        
        //CHECK IF LINK ALREADY EXISTS
        $object = $this->db->getObj($this->_table,array('id','time','expiry','hash','dls'),array('item_id'=>$item_id,'user_id'=>$user_id,'dl_id'=>$dl_id));
        
        if(sizeof($object)>=1) {
            //FIX THE EXPIRY IF REQUIRED
            $expiry = ($dl->days > 0) ? $object->time + ($dl->days * 86400) : 0;
            $object->expiry = $expiry;
            $data = array('expiry' => $expiry, 'last_ip' => $_SERVER['REMOTE_ADDR']);
            $this->db->updateData($this->_table,$data,array('id'=>$object->id));
        }
        
        //GENERATE LINK IF NOT
        else {
            $hash = $this->uniqId(get_class($this), 20);
            $data = array(
                'item_id' => $item_id,
                'user_id' => $user_id,
                'dl_id' => $dl_id,
                'time' => $time,
                'expiry' => $expiry,
                'hash' => $hash,
                'dls' => 0,
                'last_ip' => $_SERVER['REMOTE_ADDR']
            );
            $this->db->insertData($this->_table,$data);
            $object = $this->db->getObj($this->_table,array('id','hash'),array('hash'=>$hash));
        }
        $dls_left = $dl->times - $object->dls;
        $object->src = JURI::root().'index.php?option=com_salespro&view=downloads&dl='.$object->hash;
        return $object;
    }
    
    public function dlLink($hash='') {
        if($hash === '') return FALSE;
        $object = $this->db->getObj($this->_table,array('dl_id','time','expiry','dls'),array('hash'=>$hash,'last_ip' => $_SERVER['REMOTE_ADDR']));
        if(sizeof($object)<1) return FALSE;
        
        //GET THE DOWNLOAD
        $item_dls = new salesProItemDls;
        $dl = $item_dls->getDl($object->dl_id);

        //VALIDATE THE DL
        $time = time();
        if(((int)$object->expiry !== 0 && $object->expiry < $time) || $dl->times > 0 && $object->dls >= $dl->times) {
            $this->redirect('index.php?option=com_salespro&view=downloads',JText::_('SPR_ITEM_DLS_EXCEEDED
'));
            return FALSE;
        }

        //UPDATE THE DL COUNT
        $dls = $object->dls + 1;
        $this->db->updateData($this->_table,array('dls'=>$dls), array('hash' => $hash));
        
        //DELIVER THE DOWNLOAD
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"{$dl->name}.{$dl->ext}\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($dl->src));
        ob_end_flush();
        @readfile($dl->src);
        die();
    }
}