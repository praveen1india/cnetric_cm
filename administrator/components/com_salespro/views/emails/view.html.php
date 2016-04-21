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
class SalesProViewEmails extends JViewLegacy {
    
	function display( $tpl = null ) {

        $this->class = new salesProEmails;
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                if($id > 0) {
                    $this->title = JText::_('SPR_EMAIL_EDIT');
                    $this->email = $this->class->getEmail($id);
                } else {
                    $this->title = JText::_('SPR_EMAIL_CREATE');
                    $this->email = $this->class->getDefaultObject();
                }
                $this->prodtypes = array();
                $prodtypes = sprProdTypes::_load();
                if(count($prodtypes)>0) foreach($prodtypes as $p) {
                    $this->prodtypes[$p->id] = $p->name;
                } 
                break;
            default: 
                $tpl = null;
                $this->emails = $this->class->getEmails();
                break;
        }
		parent::display($tpl);
	}
}