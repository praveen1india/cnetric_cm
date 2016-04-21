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

class SalesProViewhome extends JViewLegacy {
    
    public $items = array();
    
	function display( $tpl = null ) {

        //GET TOP LEVEL CATEGORIES
        $id = JRequest::getCmd('id');
        $categories = new salesProCategories;
        $this->categories = $categories->getCategories();
        
        //GET FEATURED ITEMS
        $this->featured = sprItems::getFeaturedIds();
        
        //GET NEW ITEMS
        $this->newitems = sprItems::getNewIds();
        
        $this->hp_title = sprConfig::_load('core')->hp_title;

        $templates = new salesProTemplates;
        $template = $templates->getDefault()->alias;

        $layout = 'components/com_salespro/templates/'.$template.'/views/home/tmpl/default.php';
        if(file_exists($layout)) $this->layout = $layout;
        else $this->layout = 'components/com_salespro/views/home/tmpl/default.php';

        //SET META TAGS (NEEDS TO BE SET UP IN CONFIGURATION)
        /*
        $doc = JFactory::getDocument();
        if(strlen(trim($this->category->meta_desc))>0) $doc->setMetaData( 'description', $this->category->meta_desc);
        if(strlen(trim($this->category->meta_keys))>0) $doc->setMetaData( 'keywords', $this->category->meta_keys);
        if(strlen(trim($this->category->meta_title))>0) $doc->setTitle($this->category->meta_title);
        */

        //LOAD THE CORRECT LAYOUT
		parent::display();
	}
}