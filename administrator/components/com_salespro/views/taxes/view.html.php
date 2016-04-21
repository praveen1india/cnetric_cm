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
class SalesProViewTaxes extends JViewLegacy {
	function display( $tpl = null ) {

        $this->class = new salesProTaxes;

        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_TAX_EDIT');
                    $this->tax = $this->class->getTax($id);
                } else {
                    $this->title = JText::_('SPR_TAX_CREATE');
                    $this->tax = $this->class->getTax();
                    $this->tax->regions = array();
                }
                $regions = sprRegions::_load();
                $this->regions = array();
                if(count($regions)>0) foreach($regions as $r) {
                    if($r->status !== '1') continue;
                    $this->regions[$r->id] = $r->name;
                }
                break;
            default: 
                $tpl = null;
                $this->taxes = $this->class->getTaxes();
                break;
        }
		parent::display($tpl);
	}
}