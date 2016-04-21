<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProUpdate extends salesPro {
    private $_updateUrl = 'https://www.sales-pro.co.uk/updates/update.php';
    public $version = 0;
    public $actions = array('download', 'extract');
    public $_table;
    public $_vars;
    public $json = array();
    public $sprxml = 'salespro.xml';
    public $_recheck = 2; //RECHECK FOR UPDATES EVERY 60 MINUTES
    
    function __construct($id = 0) {
        parent::__construct();
        $sprck = new sprCk;
        if($sprck->check() !== TRUE) $this->_updateUrl = str_replace( '.php', '-demo.php',$this->_updateUrl);
        $this->pkgxml = $this->_adminpath.$this->sprxml;
    }
    
    function checkUpdates() {
        static $done = NULL;
        if(NULL === $done) {
            $checked = sprConfig::_load('core')->updates_checked;
            if($checked < (time() - $this->_recheck)) {
                $this->fetchUpdates();
            }
            $done = 1;
        }
    }
    
    function getCurrentVersion() {
        //GET THIS COMPONENT VERSION NUMBER
        if(file_exists($this->pkgxml)) {
            $xml = JFactory::getXML($this->pkgxml);
            $version = (string) $xml->version;
        }
        return $version;
    }
    
    function fetchUpdates() {

        $version = $this->getCurrentVersion();

        //GET UPDATE INFORMATION
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_updateUrl);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CAINFO, $this->_adminpath. $this->certFile);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($ch);
            curl_close($ch);
        }
        //ALTERNATIVE METHOD - FOPEN
        elseif (ini_get('allow_url_fopen') == 1) {
                $res = file_get_contents($this->_updateUrl);
        }
        else {
            return false;
        }
        if(strlen($res)<2) return false;
        
        $updates = json_decode($res);
        if(count($updates)>0) foreach($updates as $n=>$update) {
            if (version_compare($version,$update->version) >= 0) {
                unset($updates[$n]);
                continue;
            }
        }
        //SAVE CURRENT VERSION DETAILS
        $data['updates'] = $updates;
        $data['updates_checked'] = time();
        sprConfig::_save('core', $data);
    }
    
    function getUpdates() {
        $updates = (array) sprConfig::_load('core')->updates;        
        krsort($updates);
        return $updates;
    }
}

class sprUpdate {
    
    /*FACTORY METHOD TO CHECK THE LATEST VERSION NUMBER AVAILABLE */
    public static function _getCurrentVersion() {
        $class = new salesProUpdate;
        if(file_exists($class->pkgxml)) {
            $xml = JFactory::getXML($class->pkgxml);
            $version = (string) $xml->version;
        }
        return $version;
    }
    
    public static function _getLatestVersion() {
        $class = new salesProUpdate;
        $updates = $class->getUpdates();
        if(count($updates)>0) {
            $update = array_shift($updates);
            return $update;
        } else return FALSE;
    }
    
    /* FACTORY METHOD TO CHECK IF AN UPDATE IS AVAILABLE */
    public static function _checkUpdate() {
        static $update = NULL;
        if(NULL === $update) {
            $update = FALSE;
            $class = new salesProUpdate;
            $class->checkUpdates();
            $current = $class->getCurrentVersion();
            $latest = sprUpdate::_getLatestVersion();
            if ($latest && version_compare($latest->version,$current) > 0) {
                $update = TRUE;
            }
        }
        return $update;
    }
    
    /* FACTORY METHOD TO GET THE AVAILABLE UPDATES AS AN ARRAY */
    public static function _getUpdates() {
        static $updates = NULL;
        if(NULL === $updates) {
            $class = new salesProUpdate;
            $updates = $class->getUpdates();
        }
        return $updates;
    }

    /* FACTORY METHOD TO CHECK FOR NEW UPDATES */
    public static function _fetchUpdates() {
        $class = new salesProUpdate;
        return $class->fetchUpdates();
    }
}