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
class SalesProViewWidgets extends JViewLegacy {
    
	function display( $tpl = null ) {

        $this->class = new salesProWidgets;
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->widget = $this->class->getWidget($id);
                    $this->widget->about = sprWidgetTypes::_getType($this->widget->type)->about;
                } else {
                    $tpl = 'create';
                    $this->widgettypes = sprWidgetTypes::_getTypes();
                    foreach($this->widgettypes as $t) {
                        $t->about = sprWidgetTypes::_getType($t->type)->about;
                    }
                }
                break;
            default: 
                $tpl = null;
                $this->widgets = $this->class->getWidgets();
                break;
        }
		parent::display($tpl);
	}
}