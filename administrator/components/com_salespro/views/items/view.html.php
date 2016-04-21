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
class SalesProItemsViewItems extends JViewLegacy {

    public $search = array();
    
	function display( $tpl = null ) {

        $this->class = new salesProItems;
        
        $layout = JRequest::getCmd('layout');
        $id = (int) JRequest::getCmd('id');
        
        switch($layout) {
            case 'edit':
                $tpl = 'edit';
                $this->class->editTask();
                if($id > 0) {
                    $this->title = 'Edit this product';
                    $this->item = $this->class->getItem($id);
                } else {
                    $this->title = 'Create a product';
                    $this->item = $this->class->getItem();
                }
                
                //FIX DEFAULT IMAGE
                if(count($this->item->images) === 1 && $this->item->images[0]->filename === 'default/default.jpg') {
                    unset($this->item->images[0]);
                }
                
                $this->prodTypes = sprProdTypes::_load();
                $this->categories = new salesProCategories;
                $this->all_categories = $this->categories->getCategories();
                $taxes = new salesProTaxes;
                $this->taxes = array();
                $res = $taxes->getTaxes();
                if(count($res)>0) foreach($res as $r) {
                    $this->taxes[$r->id] = $r->name;
                }
                $units = sprConfig::_load('units');
                $this->weightunit = $units->weight;
                $this->sizeunit = $units->size;
                break;
            case 'copy':
                $tpl = 'edit';
                $this->item = $this->class->getItem($id);
                $this->item->id = 0;
                $this->item->alias = '';
                $this->title = 'Create a product';
                
                //GET IMAGES, DLs, FAQs, VIDs
                $imgs = new salesProItemImages;
                $this->item->images = $imgs->getImages(0);
                $dls = new salesProItemDls;
                $this->item->dls = $dls->getDls(0);
                $vids = new salesProItemVideos;
                $this->item->videos = $vids->getVideos(0);
                $faqs = new salesProItemFaqs;
                $this->item->faqs = $faqs->getFaqs(0);

                //FIX DEFAULT IMAGE
                if(count($this->item->images) === 1 && $this->item->images[0]->filename === 'default/default.jpg') {
                    unset($this->item->images[0]);
                }
                $this->prodTypes = sprProdTypes::_load();
                $this->categories = new salesProCategories;
                $this->all_categories = $this->categories->getCategories();
                $taxes = new salesProTaxes;
                $this->taxes = array();
                $res = $taxes->getTaxes();
                if(count($res)>0) foreach($res as $r) {
                    $this->taxes[$r->id] = $r->name;
                }
                $units = sprConfig::_load('units');
                $this->weightunit = $units->weight;
                $this->sizeunit = $units->size;
                break;
            default: 
                $tpl = null;
                $this->categories = new salesProCategories;
                $this->all_categories = $this->categories->getCategories();
                $this->class->updateOrder();
                $this->class->getOrder();
                $this->items = $this->class->getItems();
                $this->search = $this->class->search;
                $this->order = $this->class->order;
                $this->icon = ($this->order['dir'] === 'ASC') ? '<span class="spr-sort-up">&nbsp;</span>' : '<span class="spr-sort-down">&nbsp;</span>';
                $this->prodtypes = sprProdTypes::_load();
                $this->attributes = sprAttributes::_load(); 
                break;
        }

		parent::display($tpl);
	}
}