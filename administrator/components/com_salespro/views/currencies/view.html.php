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
class SalesProViewCurrencies extends JViewLegacy {
    
	function display( $tpl = null ) {
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_CUR_EDIT');
                    $this->currency = sprCurrencies::_load($id);
                } else {
                    $this->title = JText::_('SPR_CUR_CREATE');
                    $this->currency = sprCurrencies::_getBlank();
                }
                break;
            default: 
                $tpl = null;
                $this->currencies = sprCurrencies::_load();
                break;
        }
		parent::display($tpl);
	}
}