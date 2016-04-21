<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProWizard extends salesPro {
    
    private $sampleData = 'sql/sample.sql';
    private $sampleZip = 'sample.zip';
    private $sampleArchive = 'https://www.sales-pro.co.uk/samples/1.1.0.zip';
    
    function __construct() {
        parent::__construct();
        $this->sampleZip = $this->_adminpath.$this->sampleZip;
        $this->sampleData = $this->_adminpath.$this->sampleData;
    }

    function getZip() {
        
        //BEGIN CREATION OF THE NEW ZIP
        $file = fopen($this->sampleZip, 'wb+');
        if(!$file) {
            $msg = JText::_('SPR_WIZ_FILE_CREATE');
            $msg .= ' '.$this->sampleZip.'. ';
            $msg .= JText::_('SPR_WIZ_FILE_CHECK');
            $this->error($msg);
            return;
        }

        //USE APPROPRIATE METHOD TO GET JOOMLA
        if(function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->sampleArchive);
            curl_setopt($ch, CURLOPT_FILE, $file);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->_adminpath. $this->certFile);
           	session_write_close();
            $res = curl_exec($ch);
            if($res === false) {
                $msg = JText::_('SPR_WIZ_SAMPLE_DL');
                $this->error($msg);
                return;
            }
            curl_close ($ch);
        }

        //ALTERNATIVE METHOD - FOPEN
        else if(ini_get('allow_url_fopen') == 1) {
            $contents = file_get_contents($this->sampleArchive);
            fwrite($file,$contents);
        }
        else {
            $msg = JText::_('SPR_WIZ_ENABLE_CURL');
            $this->error();
        }
        fclose($file);

        //CHECK FILESIZE OF NEW JOOMLA ZIP
        $size = filesize($this->sampleZip);
        if($size < 1024 * 1024 * 1) {
            $error = JText::_('SPR_WIZ_CANT_DL');
            $this->error($error);
            return;
        }

        $this->json['progress'] = 30;
        $this->json['text'] = JText::_('SPR_WIZ_INSTALLING_IMGS');
        $this->json['point'] = 'installSampleImages';
        sleep(2);
        $this->saveJson();
    }

	function installSampleImages() {

        $writepath = JPATH_SITE;
        $archive = new salesProArchive($this->sampleZip,'',$writepath);
        $res = $archive->extract('images/');

        $this->json['progress'] = 60;
        $this->json['text'] = JText::_('SPR_WIZ_INSTALLING_DATA');
        $this->json['point'] = 'installSampleData';
        sleep(2);
        $this->saveJson();
    }

	function installSampleData() {
	   
        $archive = new salesProArchive($this->sampleZip,'',$this->_adminpath);
        $archive->extract('sql/');

        $handle = fopen($this->sampleData, 'r+');
        if($handle === FALSE) {
            $error = JText::_('SPR_WIZ_CANT_OPEN');
            $this->error($error.' '.$this->sampleData);
        }
        $speed = 1024;
        $sql = '';
        $db = JFactory::getDBO();

        //READ THE DATABASE FILE & SAVE AS TEMPORARY TABLES        
        while(!feof($handle)) {
            $sql .= fread($handle, $speed);
            if(!isset($separator)) {
                foreach(array(";\r\n",";\n",";\r") as $a) {
                    if(strpos($sql,$a)!== FALSE) {
                        $separator = $a;
                        break;
                    }
                }
            }
            if(substr_count($sql, $a) > 1) { 
                $queries = explode($a, $sql);
                if(!feof($handle)) $sql = array_pop($queries);
                if(count($queries)>0) foreach($queries as $q) {
                    if(strlen(trim($q))<5) continue;
                    try {
                        $db->setQuery($q);
                        $db->query();
                    }
                    catch (Exception $e) {
                        //JUST CONTINUE FOR NOW
                    }
                }
            }
        }
        
        //CLOSE THE HANDLE
        fclose($handle);
        $msg = JText::_('SPR_WIZ_AJCOMPLETE');
        $this->json['progress'] = 100;
        $this->json['text'] = $msg;
        $this->json['point'] = 1;
        sleep(2);
        $this->saveJson();
    }
}