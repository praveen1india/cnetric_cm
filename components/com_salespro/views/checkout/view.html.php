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

class SalesProViewcheckout extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        //CHECK LOGIN STATUS & REDIRECT AS NEEDED
        $user = JFactory::getUser();
        $this->class = new salesProUsers;
        if($user->guest === 1) {
            $joomlaLoginUrl = 'index.php?option=com_salespro&view=login&origin=checkout&return=';
            $redirectUrl = 'index.php?option=com_salespro&view=checkout';
            $redirectUrl = urlencode(base64_encode($redirectUrl));
            $finalUrl = JRoute::_($joomlaLoginUrl . $redirectUrl);
            $this->class->redirect($finalUrl);
            return;
        } else {
            $this->user = $this->class->getActiveUser();
        }
        
        //CHECK THE CART
        $this->cart = new salesProCart;
        $this->cart->buildCart();
        
        //UPDATE BASKET IF NEEDED
        if(isset($_POST['cart_item'])) {
            $item = (int)$_POST['cart_item'];
            $quantity = (int)$_POST['cart_quantity'];
            $this->cart->setQuantity($item,$quantity);
            JFactory::getApplication()->enqueueMessage(JText::_('SPR_BASKET_UPDATED'));
        }
        
        //CHECK THE CART HAS ITEMS
        if($this->cart->totals->quantity < 1) {
            $url = JURI::base().'index.php?option=com_salespro&view=basket';
            $url = JRoute::_($url);
            salesPro::redirect($url);
            return;
        }
        
        //CHECK THE CART HAS PRICE
        if($this->cart->totals->price < 0.00) {
            $url = JURI::base().'index.php?option=com_salespro&view=basket';
            $url = JRoute::_($url);
            $msg = JText::_('SPR_BASKET_NOFREE');
            salesPro::redirect($url,$msg);
            return;
        }
        
        //GET THE TEMPLATE LAYOUT
        $layout = JRequest::getCmd('layout');
        $view = JRequest::getCmd('view');
        
        if(strlen($layout)>0) $tpl = $layout;
        else $tpl = 'payment';
        
        switch($tpl) {
            case 'payment':
                $this->errorfields = $this->class->checkUserDetails();
                if($this->errorfields !== TRUE) {
                    foreach($this->errorfields as $r) {
                        if(strpos($r,'bill') !== FALSE) {
                            $url = 'index.php?option=com_salespro&view=checkout&layout=billing';
                            $url = JRoute::_($url);
                            $this->class->redirect($url, JText::_('SPR_CHECKOUT_COMPLETEFIELDS'));
                            break;
                        } elseif (strpos($r, 'del') !== FALSE && $this->cart->virtual === 0) {
                            $url = 'index.php?option=com_salespro&view=checkout&layout=delivery';
                            $url = JRoute::_($url);
                            $this->class->redirect($url, JText::_('SPR_CHECKOUT_COMPLETEFIELDS'));
                            break;
                        }
                    }
                }
                if($this->cart->virtual === 0) {
                    if(count($this->cart->ship_methods)<1) JError::raiseNotice( 100, JText::_('SPR_SHIPPING_NOT_SETUP') );
                }
                $this->tclink = sprConfig::_load('core')->tcpage;
                if($this->tclink == 0) $this->tclink = '';
                else $this->tclink = JRoute::_('index.php?option=com_content&view=article&id='.$this->tclink);
                break;
            case 'delivery':
                $this->errorfields = $this->class->checkUserDetails();
                if($this->errorfields !== TRUE) {
                    foreach($this->errorfields as $r) {
                        if(strpos($r,'bill') !== FALSE) {
                            $url = JRoute::_('index.php?option=com_salespro&view=checkout&layout=billing');
                            $this->class->redirect($url, JText::_('SPR_CHECKOUT_COMPLETEFIELDS'));
                            break;
                        }
                    }
                }
                break;
            case 'billing':
                $this->errorfields = $this->class->checkUserDetails();
                break;
        }

        $this->currency = sprCurrencies::_getActive();
        $this->regions = array();
        $regions = sprRegions::_load();
        foreach($regions as $n=>$region) {
            if($region->status === '1') {
                $this->regions[$region->id] = $region->name;
            }
        }

        //LOAD THE CORRECT LAYOUT
		parent::display($tpl);
	}
}