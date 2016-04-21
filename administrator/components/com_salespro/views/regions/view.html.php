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
class SalesProViewRegions extends JViewLegacy {
    
	function display( $tpl = null ) {

        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_REG_EDIT');
                    $this->region = sprRegions::_load($id);
                    $this->states = sprRegions::_getStates($id);
                } else {
                    $this->title = JText::_('SPR_REG_CREATE');
                    $this->region = sprRegions::_getBlank();
                    $this->states = array();
                }
                break;
            default: 
                $this->regions = sprRegions::_load();
                $tpl = null;
                break;
        }
        
		parent::display($tpl);
	}
}