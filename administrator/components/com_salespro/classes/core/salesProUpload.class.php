<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProUpload extends salesProAjax {
    private $_validFileTypes = array(
        'images' => array('jpg', 'gif', 'png'),
        'files' => array('pdf', 'html')
    );
    private $_postVars = array('hash');
    public $actions = array('upload');
    private $destinations = array(
        'items/',
        'categories/'
    );
    private $destID = 0;
    private $dest = '';
    
    function __construct($dest = 0, $override = '') {
        parent::__construct();
        $imgconfig = sprConfig::_load('images');
        $fileconfig = sprConfig::_load('files');
        $this->_validFileTypes['images'] = explode(',',$imgconfig->valid);
        $this->_validFileTypes['files'] = explode(',',$fileconfig->valid);
        foreach($this->destinations as $n=>$d) {
            $this->destinations[$n] = $imgconfig->loc.$d;
        }
        if($override !== '') {
            $this->dest = $override;
        } else {
            $this->destID = (int) $dest;
            $this->dest = $this->destinations[$this->destID];
        }
        $this->checkDirs();
        
    }
    private function checkDirs() {
        $destinations = $this->destinations;
        $destinations[] = $this->dest;
        foreach($destinations as $dir) {
            $dirs = explode('/',$dir);
            $cdir = $this->_filebasepath;
            foreach($dirs as $d) {
                if(strlen($d)<1) continue;
                $cdir .= '/'.$d;
                if(!is_dir($cdir)) @mkdir($cdir);
                if(!is_dir($cdir))  $this->json['error'] = JText::_('SPR_UPL_FOLDER_CREATE').' '.$cdir;
            }
        }
    }
    function uploadFile($field = 'Filedata') {
        
        if (!empty($_FILES[$field])) {
            $error = $_FILES[$field]['error'];
            if ($error === 0 && $_FILES[$field]['size'] >= $this->upload_limit) $error = 2;
            if ((int)$error !== 0) {
                switch ($error) {
                    case 1: //reserved;
                        break;
                    case 2:
                        $msg = JText::_('SPR_UPL_ERROR_2');
                        break;
                    case 3:
                        $msg = JText::_('SPR_UPL_ERROR_3');
                        break;
                    case 4:
                        $msg = JText::_('SPR_UPL_ERROR_4');
                        break;
                    case 6:
                        $msg = JText::_('SPR_UPL_ERROR_6');
                        break;
                    case 7:
                        $msg = JText::_('SPR_UPL_ERROR_7');
                        break;
                    case 8:
                        $msg = JText::_('SPR_UPL_ERROR_8');
                        break;
                    default:
                        $msg = JText::_('SPR_UPL_ERROR_DEFAULT');
                        break;
                }
                $this->json['error'] = $msg;
                return $this->json;
            }
            
            //CHECK THE FILE EXTENSION & DESTINATION ARE VALID
            $fileParts = pathinfo($_FILES[$field]['name']);
            $ext = strtolower($fileParts['extension']);
            $valid = 0;
            foreach ($this->_validFileTypes as $types) {
                if (in_array($ext, $types)) {
                    //if(in_array($this->dest,$this->destinations)) {
                        $valid = 1;
                        break;
                    //}
                }
            }
            
            if($valid === 1) {
                $tempFile = $_FILES[$field]['tmp_name'];
                $name = $fileParts['filename'];
                $newDir = $this->_filebasepath . '/' . $this->dest;
                $filename = $this->uniqId();
                $newFile = $newDir . $filename . '.' . $ext;
                if (!move_uploaded_file($tempFile, $newFile)) {
                    $msg = JText::_('SPR_UPL_CANT_SAVE').' '.$newDir;
                    $this->error($msg);
                } else {
                    $this->json['name'] = $name;
                    $this->json['error'] = 0;
                    $this->json['filename'] = $filename;
                    $this->json['ext'] = $ext;
                    $this->json['src'] = $this->_basedir.$this->dest.$filename.'.'.$ext;
                }
                return $this->json;
            }
            $this->json['error'] = JText::_('SPR_UPL_FILETYPE');
        } else {
            $this->json['error'] = JText::_('SPR_UPL_SELECTFILE');
        }
        return $this->json;
    }
}