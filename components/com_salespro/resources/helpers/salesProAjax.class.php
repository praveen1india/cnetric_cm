<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

error_reporting(E_ALL);
ini_set('display_errors', 'On');

class salesProAjax extends salesPro {

    private $_actions = array('deleteCartItem');
    public $json = array();

    function __construct() {
        parent::__construct();
    }
}