<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProArchive extends salesPro {
    private $archive;
    private $_file;
    public $_readpath;
    public $_writepath;
    private $_backupfile;
    function __construct($file, $readpath = '', $writepath = '') {
        parent::__construct();
        $this->_file = $file;
        $this->_readpath = ($readpath !== '') ? $readpath : $this->_adminpath;
        $this->_writepath = ($writepath !== '') ? $writepath : $this->_adminpath;
        require_once ($this->_adminpath . '/resources/pclzip/pclzip.lib.php');
        $this->start();
    }
    function start() {
        $this->archive = new PclZip($this->_file);
    }
    function add($file, $golive = 0) {
        $file = $this->_readpath . $file;
        if (file_exists($file)) {
            $res = $this->archive->add($file, PCLZIP_OPT_REMOVE_PATH, $this->_readpath,
                PCLZIP_OPT_ADD_TEMP_FILE_THRESHOLD, 16);
            if ($res === 0) {
                echo $this->archive->errorInfo(true);
                die();
                return false;
            }
            return true;
        }
        else {
            return false;
        }
    }
    function extract($file = '',$remove = '') {
        if ($file !== '') $res = $this->archive->extract(PCLZIP_OPT_BY_NAME, $file,
                PCLZIP_OPT_PATH, $this->_writepath, PCLZIP_OPT_REPLACE_NEWER, PCLZIP_OPT_REMOVE_PATH, $remove);
        else  $res = $this->archive->extract(PCLZIP_OPT_PATH, $this->_writepath,
                PCLZIP_OPT_REPLACE_NEWER);
        if ($res === 0) {
            echo $this->archive->errorInfo(true);
            die();
            return false;
        }             
        return true;
    }
    function delete() {
        if (file_exists($this->_backupfile)) {
            unlink($this->_backupfile);
            return true;
        }
        else {
            return false;
        }
    }
}
