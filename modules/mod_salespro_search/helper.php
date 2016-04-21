<?php
/* -------------------------------------------
Module: mod_salespro_search
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_salespro/classes/core/salesPro.class.php';

class ModSalesProSearchHelper extends salesPro {

    function __construct() {
        parent::__construct();
    }

    function getCats() {
        $cats = new salesProCategories;
        $categories = $cats->getCategories();
        return $categories;
    }

}