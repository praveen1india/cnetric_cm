<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

?>

<link rel="stylesheet" href="components/com_salespro/resources/js/responsiveslides/themes/themes.css" />

<div id="salespro" class="salespro">

<?php if(strlen($this->hp_title)>0) echo "<div class='spr_category_heading'><h1>{$this->hp_title}</h1></div>";
?>

<?php echo sprWidgets::_showWidgets('home'); ?>

</div>