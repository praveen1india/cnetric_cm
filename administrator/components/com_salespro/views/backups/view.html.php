<?php
/* -------------------------------------------
Component: com_MigrateMe
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2015 Barnaby Dixon. All Rights Reserved.
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

class salesProBackupsViewBackups extends JViewLegacy {
	function display( $tpl = null ) {
        $this->backups = sprBackups::_load();
        $this->config = sprBackups::_getConfig();
		parent::display($tpl);
	}
}