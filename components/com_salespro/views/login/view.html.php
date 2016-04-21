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

class SalesProViewlogin extends JViewLegacy {
    
	function display( $tpl = null ) {

        $redirectUrl = 'index.php?option=com_salespro&view=checkout';
        $this->redirectUrl = urlencode(base64_encode($redirectUrl));
        
        $this->origin = (isset($_GET['origin'])) ? $_GET['origin'] : '';
        
        //CHECK THE CART
        switch($this->origin) {
            
            case 'checkout':
                $this->cart = new salesProCart;
                $this->cart->buildCart();
                $this->items = $this->cart->getItems();
                $this->title = JText::_('SPR_CHECKOUT_FAILURE_1');
                break;
            case 'downloads':
                $this->title = 'Please log in to access your downloads';
                break;
            default:
                $this->title = 'Please log in to continue';
                break;
        }
        
        //LOAD THE CORRECT LAYOUT
		parent::display($tpl);
	}
}