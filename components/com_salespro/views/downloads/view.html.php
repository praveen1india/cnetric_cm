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

class SalesProViewdownloads extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        //CHECK LOGIN STATUS & REDIRECT AS NEEDED
        $user = JFactory::getUser();
        $this->class = new salesProUsers;
        if($user->guest === 1) {
            $joomlaLoginUrl = 'index.php?option=com_salespro&view=login&origin=downloads&return=';
            $redirectUrl = 'index.php?option=com_salespro&view=downloads';
            $redirectUrl = urlencode(base64_encode($redirectUrl));
            $finalUrl = $joomlaLoginUrl . $redirectUrl;
            $this->class->redirect($finalUrl);
            return;
        } else {
            $this->user = $this->class->getActiveUser();
        }
        
        //DOWNLOAD PRODUCT IF LINK WAS CLICKED
        if(isset($_GET['dl'])) {
            $dls = new salesProItemDlsLinks;
            $dls->dlLink($_GET['dl']);
        }
        
        //CHECK THE AVAILABLE PRODUCTS & DOWNLOADS FOR THIS USER
        $sales = sprSales::_load($user->id);
        $this->dls = array();
        foreach($sales as $sale) {
            if($sale->status !== '1') continue; //COMPLETED SALES ONLY
            foreach($sale->items as $i) {
                $dls = array();
                if(count($i->dls)<1) continue;
                else foreach($i->dls as $d) $dls[] = array('name' => $d->name, 'ext' => $d->ext, 'link' => $d->link->src, 'expiry' => $d->link->expiry, 'dls' => $d->link->dls, 'limit' => $d->times);
                $this->dls[$i->item_id] = array(
                    'date' => $sale->date,
                    'name' => $i->name,
                    'dls' => $dls
                );
            }
        }

        //LOAD THE CORRECT LAYOUT
		parent::display($tpl);
	}
}