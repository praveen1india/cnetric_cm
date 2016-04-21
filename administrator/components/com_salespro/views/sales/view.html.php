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
class SalesProViewSales extends JViewLegacy {

    public $search = array();
    
	function display( $tpl = null ) {

        $this->class = new salesProSales;
        $this->units = sprConfig::_load('units');

        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');

        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_SL_EDIT');
                    $this->sale = $this->class->getSale($id);
                }
                if(is_null($this->sale) || !isset($this->sale->id) || $this->sale->id === 0) {
                    $this->class->redirect('index.php?option=com_salespro&view=sales', JText::_('SPR_SL_NOTFOUND'));
                }
                $regions = sprRegions::_load();
                $this->regions = array();
                if(count($regions)>0) foreach($regions as $region) {
                    if($region->status !== '1') continue;
                    $this->regions[$region->id] = $region->name;
                }
                break;
            default: 
                $tpl = null;
                $this->class->updateOrder();
                $this->sales = $this->class->getSales();
                $this->search = $this->class->search;
                $this->order = $this->class->order;
                $this->icon = ($this->order['dir'] === 'ASC') ? '<span class="spr-sort-up">&nbsp;</span>' : '<span class="spr-sort-down">&nbsp;</span>';
                break;
        }
		parent::display($tpl);
	}
}