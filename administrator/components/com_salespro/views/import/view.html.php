<?php
/* -------------------------------------------
Component: com_MigrateMe
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
jimport('joomla.application.component.helper');
if(!class_exists('JViewLegacy')) {
    class JViewLegacy extends JView {
        function __construct() {
            parent::__construct();
        }
    }
}

class salesProImportViewImport extends JViewLegacy {
	function display( $tpl = null ) {
        
        //CHECK SAFE MODE
        if(ini_get('safe_mode')) $this->check['safe_mode'] = 0;
        else $this->check['safe_mode'] = 1;
       
        //CHECK MEMORY
        $this->check['memory'] = 0;
        $temp = (int) ini_get('memory_limit');
        if($temp <= 128) {
            if($this->check['safe_mode'] === 1) {
                ini_set('memory_limit','128M');
                $temp = (int) ini_get('memory_limit');
            }
        }
        $this->check['memory'] = $temp;
        
        //CHECK UPLOAD LIMIT
        $this->upload_limit = 0;
        $max_upload = (int) ini_get('upload_max_filesize');
        $max_post = (int) ini_get('post_max_size');
        $this->upload_limit = min($max_upload, $max_post);
        
        //CHECK TIMEOUT LIMIT
        @set_time_limit(6000);
        $timeout = ini_get('max_execution_time');
        
        //CREATE SPEED VARIABLE
        if($temp <= 128) $this->speed = 35;
        elseif($temp <= 256) $this->speed = 70;
        elseif($temp <= 512) $this->speed = 120;
        else $this->speed = 150;
        $this->speed = 5;
        
        if($timeout < 6000) $this->speed = 10;
                
		parent::display($tpl);
	}
}