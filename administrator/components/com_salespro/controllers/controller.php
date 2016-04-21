<?php

/* -------------------------------------------
Component: com_SalesPro
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

class salesProControllerAdmin extends JControllerLegacy {
    
    public $version = 0;
    
	function __construct( $default = array()) {
        require_once(JPATH_ADMINISTRATOR.'/components/com_salespro/classes/core/salesPro.class.php');
		parent::__construct( $default );
        jimport('joomla.version');
        $version = new JVersion();
        $this->version = $version->RELEASE;
	}
    
    function auth() {
        if (!JFactory::getUser()->authorise('core.manage', 'com_salespro')) {
        	return FALSE;
        }
        return TRUE;
    }
    
    function config() {
        JRequest::checkToken() or jexit( JText::_('SPR_BADTOKEN') );
        $this->setRedirect( 'index.php?option=com_salespro&view=config');
    }
    
    //PUBLIC FUNCTION: PROCESS VARIOUS AJAX REQUESTS
   	public function runAjax() {
        if(!isset($_GET['tab']) || !isset($_GET['action'])) return false;
        $action = htmlentities($_GET['action']);
        $table = htmlentities($_GET[ 'tab']);
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        $class = 'salesPro'.$table;
        if(class_exists($class) && $x = new $class()) {
            if(in_array($action,$x->actions)) $x->action();
        }
	}

    public function checkAjax() {
        $ajax = new salesProAjax;
        $ajax->checkAjax();
    }

    function save($redirect = 1) {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));

        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        
        //GET THE TABLE NAME
        $table = JRequest::getCmd('spr_table');
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        $id = (int) JRequest::getCmd('id');
        
        //START THE CLASS
        $class = 'salesPro'.$table;
        $result = TRUE;
        if(class_exists($class) && $x = new $class()) {
            $result = $x->saveData();
        }
        
        //TREAT AS AN APPLY IF WE HIT AN ERROR ON SAVE
        if($result === FALSE) {
            $redirect = JFactory::getURI()->toString();
        } else {
            $msg = JText::_('SPR_ADMIN_DATA_SAVED');
        }
        
        if($redirect !== 1) {
            $redir = $redirect;
        }

        //NORMAL REDIRECT
        $this->setRedirect($redir, $msg);
    }

    function apply() {
        
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));
        $url = JFactory::getURI()->toString();
        return $this->save($url);
    }
    
    function delete() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));
        
        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        
        //GET THE TABLE NAME & ID
        $table = JRequest::getCmd('spr_table');
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        $id = JRequest::getCmd('spr_id');
        
        //START THE CLASS
        $class = 'salesPro'.$table;
        if(class_exists($class) && $x = new $class()) {
            $ret = $x->deleteData();
            if($ret === TRUE) {
                //NORMAL REDIRECT
                $msg = JText::_('SPR_ADMIN_ITEM_DELETED');
                $this->setRedirect($redir,$msg);
            } else {
                $this->setRedirect($redir);
            }
        }
    }
    
    function edit() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));
        
        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        $redir .= '&layout=edit';
        $id = JRequest::getVar('spr_id');
        $cid = JRequest::getVar('cid');
        if((int)$cid[0] > 0) $id = $cid[0];
        $redir .= '&id='.$id;
        $this->setRedirect($redir);
    }
    
    function copy() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));

        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        $redir .= '&layout=copy';
        $id = JRequest::getVar('spr_id');
        $cid = JRequest::getVar('cid');
        if((int)$cid[0] > 0) $id = $cid[0];
        $redir .= '&id='.$id;
        $this->setRedirect($redir);
    }
    
    function create() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));
        
        //GET THE TABLE NAME
        $table = JRequest::getCmd('spr_table');
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        
        //START THE CLASS
        $class = 'salesPro'.$table;
        if(class_exists($class) && $x = new $class()) {
            $x->cancelSave();
        }
        
        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        
        $redir .= '&layout=edit';
        $this->setRedirect($redir,$msg);
    }
    
    function cancel() {
        JRequest::checkToken() or jexit(JText::_('SPR_BADTOKEN'));
        
        //GET THE TABLE NAME
        $table = JRequest::getCmd('spr_table');
        $tab = explode('_',$table);
        $table = '';
        foreach($tab as $t) {
            $table .= ucfirst($t);
        }
        $id = (int) JRequest::getCmd('id');
        
        //START THE CLASS
        $class = 'salesPro'.$table;
        if(class_exists($class) && $x = new $class()) {
            $x->cancelSave();
        }

        //REDIRECT URL
        $redir = 'index.php?option=com_salespro';
        $view = JRequest::getCmd('view');
        if(strlen($view)>0) $redir .= '&view='.$view;
        $this->setRedirect($redir);
    }

	function display ($cachable = false, $urlparams = false) {
        $myview = JRequest::getCmd('view');
        if(strlen($myview)<1) $myview = 'dashboard';
        
        JRequest::setVar('view', $myview);
        $document = JFactory::getDocument();

        //STYLESHEETS
        $document->addStyleSheet('components/com_salespro/resources/css/stylesheet2.css');
        $document->addStyleSheet('components/com_salespro/resources/css/ui/jquery-ui.min.css');

        //JQUERY UI + JAVASCRIPT
        if($this->version >= 3) JHtml::_('jquery.framework');
        else {
            $document->addScript('components/com_salespro/resources/js/jquery.min.js');
            $document->addScript('components/com_salespro/resources/js/jquery-noconflict.js');
        }
        $document->addScript('components/com_salespro/resources/js/jquery-sortable.min.js');
        $document->addScript('components/com_salespro/resources/js/jquery-ui.min.js');
        $document->addScript('components/com_salespro/resources/js/salespro.js');
        $script = 'components/com_salespro/views/'.$myview.'/js/'.$myview.'.js';
        if(file_exists($script)) 
            $document->addScript($script);

        //SUBMENU
        $submenu = array(
            'SPR_ADMIN_DASHBOARD' => '',
            'SPR_ADMIN_CATEGORIES' => 'view=categories',
            'SPR_ADMIN_PRODUCTS' => 'view=items',
            'SPR_ADMIN_SALES' => 'view=sales',
            'SPR_ADMIN_CUSTOMERS' => 'view=users',
            'SPR_ADMIN_CURRENCIES' => 'view=currencies',
            'SPR_ADMIN_REGIONS' => 'view=regions',
            'SPR_ADMIN_TAXES' => 'view=taxes',
            'SPR_ADMIN_SHIPPING' => 'view=shipping',
            'SPR_ADMIN_PAYMENT' => 'view=payment_options',
            'SPR_ADMIN_EMAILS' => 'view=emails',
            'SPR_ADMIN_TEMPLATES' => 'view=templates',
            'SPR_ADMIN_WIDGETS' => 'view=widgets',
            'SPR_ADMIN_CONFIG' => 'view=config',
            'SPR_ADMIN_IMPORT' => 'view=import',
            'SPR_ADMIN_CSV' => 'view=csv',
            'SPR_ADMIN_BACKUPS' => 'view=backups'
        );
        //JOOMLA 3 STYLE                
        JHtmlSidebar::addEntry('<img src="components/com_salespro/resources/images/salespro.png" alt="SalesPro logo" width="180" />', 'index.php?option=com_salespro');
        foreach($submenu as $name=>$view) {
            if(strlen($view)>0) $view = '&'.$view;
            JHtmlSidebar::addEntry(JText::_($name),'index.php?option=com_salespro'.$view);
        }
        //PRESELECT TABS
        if(isset($_GET['tab'])) {
            $tab = $_GET['tab'];
            $script = "jQuery(document).ready(function() { switchTab('{$tab}'); });";
            $document->addScriptDeclaration($script);
        }
		parent::display();
	}
}

if(!class_exists('salesProController')) {
    class salesProController extends salesProControllerAdmin {
        function __construct() {
            parent::__construct();
        }
    }
}