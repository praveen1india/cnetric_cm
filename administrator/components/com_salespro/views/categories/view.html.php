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
class SalesProViewCategories extends JViewLegacy {
	function display( $tpl = null ) {
	   
        $this->class = new salesProCategories;
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_CAT_EDIT');
                    $this->category = $this->class->getCategory($id);
                } else {
                    $this->title = JText::_('SPR_CAT_CREATE');
                    $this->category = $this->class->getCategory();
                }
                break;
            case 'copy':
                $tpl = 'edit';
                $this->title = JText::_('SPR_CAT_CREATE');
                $this->category = $this->class->getCategory($id);
                $this->category->id = 0;
                $this->category->alias = '';
                break;
            default: 
                $tpl = null;
                $this->class->updateOrder();
                $this->class->getOrder();
                $this->icon = ($this->class->order['dir'] === 'ASC') ? '<span class="spr-sort-up">&nbsp;</span>' : '<span class="spr-sort-down">&nbsp;</span>';
                break;
        }
        $this->categories = $this->class->getCategories();
		parent::display($tpl);
	}
}