<?php
/* -------------------------------------------
Component: plg_SalesPro
Author: Barnaby Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
License: http:www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

ini_set('display_errors', 'On');
error_reporting(E_ALL);

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgSystemSalesPro extends JPlugin {
	function __construct($subject) {
		parent::__construct($subject);
        $x = new salesProTasks;
        $x->runTasks();
    }
}

class salesProTasks { 
    
    private $cpath = '/components/com_salespro/';
    
    function __construct() {
        $this->cpath = JPATH_ADMINISTRATOR.$this->cpath;
    }
    
    public function runTasks() {

		if(!file_exists($this->cpath.'classes/core/salesPro.class.php')) return;
        
        require_once($this->cpath.'classes/core/salesPro.class.php');
        $timer = sprConfig::_load('core')->timer;
        
        $now = time();
        
        $files = scandir($this->cpath.'classes/core/');
        $update = 0;
        if(count($files)>0) foreach($files as $file) {

            $path = pathinfo($file);
            if(!isset($path['extension']) || $path['extension'] !== 'php') continue;
            $class = explode('.',$path['filename']);
            $class = $class[0];
            require_once($this->cpath.'classes/core/'.$file);
            if(method_exists($class,'autoTasks')) {
                $x = new $class;
                if($x->autoTasks($now,$timer)) {
                    sprConfig::_saveParam('core', 'timer', $now);
                }
            }
        }
    }
}