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

class SalesProViewcategory extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        if(isset($_GET['spr_search_name'])) {
            $route = JRoute::_('index.php?'.http_build_query($_GET));
            header("location:".$route);
        }
	   
        //GET THIS CATEGORY & CHECK IT EXISTS
        $id = JRequest::getCmd('id');
        $c = new salesProCategories;
        $this->category = $c->getCategory($id);
        $this->subcategories = sprCategories::_getSubCategories($id,$this->category->params->subcategory_levels);
        if(sizeof($this->category)<1) {
            $c->redirect('404');
            return false;
        }
        
        //GET AND SET THE DISPLAY ORDER
        $i = new salesProItems;
        $this->order = sprSearch::_get('items',$i->order);
        $this->order['limit'] = $c->order['limit'];
        
        //CHECK FOR ANY SEARCH TERMS
        $this->search = sprSearch::_getVal('items', 'search');
        
        //GET THE CATEGORY ITEMS
        $categories = array($id);
        if($this->category->params->subcatitems === '1') {
            if(count($this->subcategories)>0) foreach($this->subcategories as $subcat) {
                $categories[] = $subcat->id;
            }
        }
        $items = sprItems::_getCatItems($categories,$this->order);
        $this->category->items = $items['ids'];

        //GET THE TOTAL AVAILABLE ITEMS
        $this->total = $items['total'];

        //BUILD THE PAGE NUMBERS
        $this->totalpages = $this->total/$this->order['limit'];
        $from = ($this->order['page']*$this->order['limit']) +1;
        $to = ($from + $this->order['limit']) -1;
        if($to > $this->total) $to = $this->total;
        $this->totalresults = JText::_('SPR_RESULTS')." <strong>{$from}-{$to} (".JText::_('SPR_RESULTS_MAX')." {$this->total})</strong>";

        //SET THE PAGE NUMBERS
        $this->pagenumbers = "";
        $prev = ($this->order['page'] <= 1) ? 0 : $this->order['page']-1;
        $next = ($this->order['page']+1 < $this->totalpages) ? $this->order['page']+1 : $this->order['page'];
        $this->pagenumbers = "<a class='spr_category_sort_page' rel='{$prev}'>&lt;</a>";
        $x = 0;
        for($i=$prev;$i<($this->totalpages);$i++) {
            $pageno = $i+1;
            if($prev > 0 && $x === 0) {
                $this->pagenumbers .= "<a class='spr_category_sort_page' rel='".($i-1)."'>...</a>";
            }
            $myclass = '';
            if($this->order['page'] == $i) {
                $myclass = 'spr_active_page';
                $pageno = "<strong>{$pageno}</strong>";
            }
            if($x++ > 2) {
                $pageno = '...';
            }
            $this->pagenumbers .= "<a class='sort_selected spr_category_sort_page {$myclass}' rel='{$i}'>{$pageno}</a>";
            if($x >= 4) break;
        }
        $this->pagenumbers .= "<a class='spr_category_sort_page' rel='{$next}'>&gt;</a>";
        
        //BUILD THE SORT BAR
        $sortvars = array(
            array('sort','ASC',JText::_('Best Match')),
            array('name','ASC',JText::_('SPR_A_Z')),
            array('name','DESC',JText::_('SPR_Z_A')),
            array('price','ASC',JText::_('SPR_PRICE_LOWHIGH')),
            array('price','DESC',JText::_('SPR_PRICE_HIGHLOW'))
        );
        $this->sortvars_options = "";
        $this->sortvars_list = "";
        
        foreach($sortvars as $val) {
            if($this->order['sort'] === $val[0] && $this->order['dir'] === $val[1]) {
                $sel = " selected='selected'";
                $sellist = " sort_selected";
            } else {
                $sel = '';
                $sellist = '';
            }
            $this->sortvars_options .= "<option class='spr_cat_sort' sort='{$val[0]}' dir='{$val[1]}' {$sel}>{$val[2]}</option>";
            $this->sortvars_list .= "<li><a class='spr_cat_sort {$sellist}' sort='{$val[0]}' dir='{$val[1]}' {$sellist}>{$val[2]}</a></li>";
        }

        //GET LAYOUT TYPE
        $layout_name = $c->getLayoutName($this->category->params->layout);
        $view = JRequest::getCmd('view');
        
        $templates = new salesProTemplates;
        $template = $templates->getDefault()->alias;
        
        $layout = 'components/com_salespro/templates/'.$template.'/views/'.$view.'/'.$layout_name.'.php';
        if(file_exists($layout)) $this->layout = $layout;
        else $this->layout = 'components/com_salespro/views/'.$view.'/tmpl/'.$layout_name.'.php';
        
        //GET ACTIVE CURRENCY
        $this->currency = sprCurrencies::_getActive();
        
        //SET META TAGS
        $doc = JFactory::getDocument();
        if(strlen(trim($this->category->meta_desc))>0) $doc->setMetaData( 'description', $this->category->meta_desc);
        if(strlen(trim($this->category->meta_keys))>0) $doc->setMetaData( 'keywords', $this->category->meta_keys);
        if(strlen(trim($this->category->meta_title))>0) $doc->setTitle($this->category->meta_title);

        //LOAD THE CORRECT LAYOUT
		parent::display();
	}
}