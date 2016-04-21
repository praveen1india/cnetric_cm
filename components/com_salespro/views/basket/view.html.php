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

class SalesProViewbasket extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        $layout = JRequest::getCmd('layout');
        $view = JRequest::getCmd('view');

        //GET THIS CATEGORY & CHECK IT EXISTS
        $id = JRequest::getCmd('id');
        $this->cart = new salesProCart;
        $this->cart->buildCart();

        if(isset($_POST['cart_item'])) {
            $item = (int)$_POST['cart_item'];
            $quantity = (int)$_POST['cart_quantity'];
            $this->cart->setQuantity($item,$quantity);
            $url = JURI::base().'index.php?option=com_salespro&view=basket';
            $msg = JText::_('SPR_BASKET_UPDATED');
            salesPro::redirect($url,$msg);
        }

        //LOAD THE CORRECT LAYOUT
		parent::display($tpl);
	}
}