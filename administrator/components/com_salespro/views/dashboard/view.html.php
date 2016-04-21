<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
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

class SalesProDashboardViewDashboard extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        $this->update = sprUpdate::_checkUpdate();
        $this->version = sprUpdate::_getCurrentVersion();
        
        //CHECK LAYOUT TYPE        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'wizard':
                $tpl = 'wizard';
                if(isset($_GET['do'])) {
                    $do = htmlentities($_GET['do'],ENT_QUOTES,"UTF-8");
                    $wiz = new salesProWizard;
                    $wiz->$do();
                    return;
                }
                $this->install = (isset($_GET['action']) && $_GET['action'] === 'install') ? 1 : 0;
                break;
            case 'update':
                $tpl = 'update';
                $this->updates = sprUpdate::_getUpdates();
                break;
            default:
                $tpl = null;
                break;
        }
        
        //CHECK FOR WELCOME MESSAGE
        $this->welcome = sprConfig::_load('core')->show_welcome;
        if($this->welcome === '1') {
            sprConfig::_saveParam('core', 'show_welcome','2');
        }
        
        //CHECK FOR A NEW VERSION
        if($this->update === TRUE) {
            sprUpdate::_getUpdates();
            $this->update = sprUpdate::_checkUpdate();
            if($this->update === TRUE) JFactory::getApplication()->enqueueMessage(JText::_('SPR_DASH_NEWVERSION').' - <a href="index.php?option=com_salespro&view=dashboard&layout=update">'.JText::_('SPR_DASH_CLICKUPDATE').'</a>.');
        }

        //CHECK SAFE MODE
        if(ini_get('safe_mode')) $sprcheck['safe_mode'] = 0;
        else $sprcheck['safe_mode'] = 1;
        
        //CHECK MEMORY
        $sprcheck['memory'] = 0;
        $temp = (int) ini_get('memory_limit');
        if($temp <= 128) {
            if($sprcheck['safe_mode'] === 1) {
                ini_set('memory_limit','256M');
                $temp = (int) ini_get('memory_limit');
            }
        }
        $sprcheck['memory'] = $temp;
        
        //CHECK TIMEOUT LIMIT
        $timeout = ini_get('max_execution_time');
        if($timeout <= 60) {
            if($sprcheck['safe_mode'] === 1) {
                @set_time_limit(300);
                $timeout = ini_get('max_execution_time');
            }
        }
        $sprcheck['time'] = $timeout;

        //CHECK CURL
        $sprcheck['curl'] = (function_exists('curl_init')) ? 1 : 0;

        //CONVERT CHECKS TO MESSAGES
        $checks = array();
        $checks[] = array('safe_mode', JText::_('SPR_DASH_SAFEMODECHECK'));
        $checks[] = array('curl', JText::_('SPR_DASH_CURLCHECK'));
        $checks[] = array('memory', JText::_('SPR_DASH_MEMORYCHECK').': ');
        $checks[] = array('time', JText::_('SPR_DASH_TIMEOUTCHECK').': ');
        
        $this->checks = array();
        foreach($checks as $check) {
            $name = $check[0];
            $result = $sprcheck[$name];
            $text = '';
            switch($name) {
                case 'memory':
                    if($result < 128) $text = $check[1].$result.'Mb';
                    break;
                case 'time':
                    if($result < 30) $text = $check[1].$result.' seconds';
                    break;
                default:
                    if($result != 1) $text = $check[1];
                    break;
            }
            if($text !== '') $this->checks[] = $text;
        }
        if(count($this->checks)>0) foreach($this->checks as $c) {
            JError::raiseNotice(100, $c);
        }

		parent::display($tpl);
	}
}