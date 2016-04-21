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

class SalesProViewthankyou extends JViewLegacy {
    
	function display( $tpl = null ) {
	   
        //CHECK LOGIN STATUS & REDIRECT AS NEEDED
        $user = JFactory::getUser();
        $this->class = new salesProUsers;
        if($user->guest === 1) {
            $joomlaLoginUrl = 'index.php?option=com_users&view=login&return=';
            $redirectUrl = 'index.php?option=com_salespro&view=checkout';
            $redirectUrl = urlencode(base64_encode($redirectUrl));
            $finalUrl = $joomlaLoginUrl . $redirectUrl;
            $this->class->redirect($finalUrl);
            return;
        } else {
            $this->user = $this->class->getActiveUser();
        }
        
        //GET SHOP SETTINGS
        $this->thankyou = sprConfig::_load('thankyou');
        
		parent::display();
	}
}