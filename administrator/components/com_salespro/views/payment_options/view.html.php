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
class SalesProViewPayment_Options extends JViewLegacy {
	function display( $tpl = null ) {

        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_PM_EDIT');
                    $this->option = sprPaymentOptions::_load($id);
                    $mytpl = "components/com_salespro/views/payment_options/tmpl/default_{$this->option->method->alias}.php";
                    if(file_exists($mytpl)) {
                        $tpl = $this->option->method->alias;
                    }
                } else {
                    $tpl = 'create';
                    $this->title = JText::_('SPR_PM_CREATE');
                    $this->option = sprPaymentOptions::_default();
                    $payment_methods = sprPaymentMethods::_load();
                    $this->payment_methods = array();
                    if(count($payment_methods)>0) foreach($payment_methods as $m) {
                        $this->payment_methods[$m->id] = $m->name;
                    }
                }
                break;
            default: 
                $tpl = null;
                $this->methods = sprPaymentOptions::_load();
                break;
        }
		parent::display($tpl);
	}
}