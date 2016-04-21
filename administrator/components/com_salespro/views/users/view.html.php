<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
mail: barnaby@php-web-design.com
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
class SalesProViewUsers extends JViewLegacy {
    
    public $search = array();
    
	function display( $tpl = null ) {
	   
        $this->class = new salesProUsers;
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_USR_EDIT');
                    $this->user = $this->class->getUser($id);
                } else {
                    $this->title = JText::_('SPR_USR_CREATE');
                    $this->user = $this->class->getUser();
                }
                break;
            default: 
                $tpl = null;
                $this->class->updateOrder();
                $this->users = $this->class->getUsers();
                $this->search = $this->class->search;
                $this->order = $this->class->order;
                break;
        }
        
        /* /// GET REGIONS /// */
        $regions = sprRegions::_load();
        $this->regions = array();
        foreach($regions as $n=>$region) {
            if($region->status === '1') {
                $this->regions[$region->id] = $region->name;
            }
        }

		parent::display($tpl);
	}
}