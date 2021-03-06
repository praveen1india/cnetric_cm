<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class salesProPaymentTypeFreeCheckout extends salesProPaymentType {
    
    function beginPayment($sale_id,$user,$cart) {
        //NO NEED TO REDIRECT TO PAYMENT GATEWAY
        //JUST INSTANTLY ACCEPT
        //WITH STATUS COMPLETE
        parent::completePayment($sale_id,$cart->_hash);
        return TRUE;
    }
}