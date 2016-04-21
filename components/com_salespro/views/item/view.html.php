<?php
/* -------------------------------------------
Component: com_salesPro
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
class salesProViewitem extends JViewLegacy {
    
    public $items = array();
    
	function display( $tpl = null ) {

        //GET THIS ITEM & CHECK IT EXISTS
        $id = JRequest::getCmd('id');
        $this->class = new salesProItems;
        $this->item = $this->class->getItem($id);
        if($this->item->id < 1) {
            $this->class->redirect('404');
            return false;
        }
        if(count($this->item->variants)>0) foreach($this->item->variants as $v) {
            if($v->onsale !== '1') $v->sale = '';
        } else if($this->item->onsale !== '1') {
            $this->item->sale = '';
        }
        
        $this->units = sprConfig::_load('units');
        
        //SET META TAGS
        $doc = JFactory::getDocument();
        if(strlen(trim($this->item->meta_desc))>0) $doc->setMetaData( 'description', $this->item->meta_desc);
        if(strlen(trim($this->item->meta_keys))>0) $doc->setMetaData( 'keywords', $this->item->meta_keys);
        if(strlen(trim($this->item->meta_title))>0) $doc->setTitle($this->item->meta_title);
                
		parent::display($tpl);
	}
}