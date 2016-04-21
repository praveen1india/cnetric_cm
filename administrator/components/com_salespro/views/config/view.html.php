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
class SalesProViewConfig extends JViewLegacy {
    
	function display( $tpl = null ) {

        //GET SHOP VARIABLES
        $this->core = sprConfig::_load('core');
        $this->images = sprConfig::_load('images');
        $this->files = sprConfig::_load('files');
        $this->units = sprConfig::_load('units');
        $this->thankyou = sprConfig::_load('thankyou');
        
        $article = JTable::getInstance("content");
        $article->load($this->core->tcpage);
        $this->tcpage = $article->get("title");
        if(strlen($this->tcpage)<1) $this->tcpage = JText::_('SPR_CON_ARTICLE_SELECT');
		parent::display($tpl);
	}
}