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

class SalesProViewShipping extends JViewLegacy {
	function display( $tpl = null ) {

        $this->class = new salesProShipping;
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_SHP_EDIT');
                    $this->ship = $this->class->getShip($id);
                    $this->ship->rules = $this->class->getRules($id);
                } else {
                    $this->title = JText::_('SPR_SHP_CREATE');
                    $data = array('info' => '');
                    $id = $this->class->saveData(0,$data);
                    $this->ship = $this->class->getShip($id);
                    $this->ship->rules = array();
                }
                $this->regions = sprRegions::_getActive();
                $payment_options = sprPaymentOptions::_load();
                $this->payoptions = array();
                $payoptions = array();
                foreach($payment_options as $o) {
                    $this->payoptions[$o->id] = $o->name;
                    $payoptions[] = $o->id;
                }
                if(count($this->ship->paymentoptions)<1) $this->ship->paymentoptions = $payoptions;
                $units = sprConfig::_load('units');
                $this->weightunit = $units->weight;
                $this->sizeunit = $units->size;
                $this->currency = sprCurrencies::_getDefault();
                break;
            default: 
                $tpl = null;
                $this->shipping = $this->class->getShipping();
                break;
        }
		parent::display($tpl);
	}
}