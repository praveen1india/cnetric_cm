<?php
/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controller');
if(!class_exists('JControllerLegacy')) {
    class JControllerLegacy extends JController {
        function __construct() {
            parent::__construct();
        }
    }
}

class salesProController extends JControllerLegacy {
    
	function __construct( $default = array()) {
        require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/classes/core/salesPro.class.php');
        
        //HTTPS REDIRECT
        if(sprConfig::_load('core')->ssl === '1') {
            if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "" || $_SERVER['HTTPS'] == "off"){
                $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                header("Location: $redirect");
            }
        }
		parent::__construct( $default );
	}
    
    function buy () {
        
        if(!JRequest::checkToken()) return;
        $id = (int) JRequest::getCmd('spr_id');
        $variant = JRequest::getVar('spr_variant');
        
        $cart = new salesProCart;
        $cart->buildCart();
        $type = 'message';
        if(!$res = $cart->addItem($id,$variant)) {
            $msg = JText::_('SPR_CART_PRODUCT_NOEXIST');
            $type = 'error';
        } else if($res === 'error1') {
            $msg = JText::_('SPR_CART_PRODUCT_MAXIMUM');
            $type = 'error';
        } else if ($res === 'error2') {
            $msg = JTEXT::_('SPR_CART_PRODUCT_NOMORE');
            $type = 'error';
        } else {
            $msg = JTEXT::_('SPR_CART_PRODUCT_ADDED');
        }
        
        //GET SHOP VARIABLES
        if(sprConfig::_load('core')->cart_action === '2') $link = JRoute::_('index.php?option=com_salespro&view=item&id='.$id);
        else $link = JRoute::_('index.php?option=com_salespro&view=basket');
        
        $this->setRedirect($link,$msg,$type);
    }
    
	function display ($cachable = false, $urlparams = false) {
	   
        $myview = JRequest::getCmd('view');
        $layout = JRequest::getCmd('layout');

        if(strlen($myview)<1) {
            $myview = 'home';
            $layout = '';
        }
        JRequest::setVar('view', $myview);

        $document = JFactory::getDocument();
        $view_type   = $document->getType();
        
        //INCLUDE THE STANDARD STYLESHEET
        $document->addStyleSheet('components/com_salespro/resources/css/stylesheet.css');
        $document->addStyleSheet('components/com_salespro/resources/js/responsiveslides/themes/themes.css');
        
        //INCLUDE THE STANDARD JAVASCRIPT
        jimport('joomla.version');
        $version = new JVersion();
        if((int) $version->getShortVersion() >= 3) JHtml::_('jquery.framework');
        else {
            $document->addScript('administrator/components/com_salespro/resources/js/jquery.min.js');
            $document->addScript('administrator/components/com_salespro/resources/js/jquery-noconflict.js');
        }
        $document->addScript('components/com_salespro/resources/js/responsiveslides/responsiveslides.min.js');
        $document->addScript('components/com_salespro/resources/js/salespro.js');
        
        $templates = new salesProTemplates;
        $mytemplate = $templates->getDefault();
        
        //INCLUDE THE TEMPLATE CORE INCLUDE FILE
        $template_include = 'components/com_salespro/templates/'.$mytemplate->alias.'/includes.php';
        if(file_exists($template_include)) include_once($template_include);

        //SET THE CORRECT VIEW - ALLOWS TEMPLATE OVERRIDES
		$view = $this->getView($myview, $view_type);
		$view->addTemplatePath("components/com_salespro/templates/{$mytemplate->alias}/views/{$myview}/");
		$view->display();
	}
    
    function logout() {
        $msg = JText::_('SPR_LOGIN_AGAIN');
        $userToken = JSession::getFormToken();
        $url = 'index.php?option=com_users&task=user.logout&'.$userToken.'=1&return=';
        $url = JRoute::_($url);
        $redirect = 'index.php?option=com_salespro&view=checkout';
        $redirect = urlencode(base64_encode($redirect));
        $this->setRedirect($url.$redirect,$msg);
    }
    
    function processCheckout() {
        
        if(!JRequest::checkToken()) return;
        $view = JRequest::getCmd('view');
        $layout = JRequest::getCmd('layout');
        
        $user = new salesProUsers;
        if($thisuser = $user->getActiveUser()) {
            $user->saveData($thisuser->id);
        }

        $url = 'index.php?option=com_salespro&view='.$view.'&layout='.$layout;
        $url = JRoute::_($url);
        $this->setRedirect($url);
    }
    
    function completeCheckout() {
        
        if(!JRequest::checkToken()) return;
        $view = JRequest::getCmd('view');
        $layout = JRequest::getCmd('layout');
        
        $sale = new salesProSales;
        $res = $sale->addSale();
        if($res !== TRUE) {
            $url = 'index.php?option=com_salespro&view='.$view;
            $url = JRoute::_($url);
            $msg = JText::_('SPR_CHECKOUT_FAILURE_'.$res);
            $this->setRedirect($url,$msg);
            return;
        }

        $url = 'index.php?option=com_salespro&view='.$view.'&layout='.$layout;
        $url = JRoute::_($url);
        $this->setRedirect($url);
    }
    
    function processPayment() {
        if(!isset($_GET['method'])) return FALSE;
        $method = (int) $_GET['method'];
        if($method < 1) return FALSE;
        $option = sprPaymentOptions::_load($method);
        if(isset($option->method->class))
        $class = $option->method->class;
        if(class_exists($class)) {
            $sale = new $class($option->id);
        }
    }
}