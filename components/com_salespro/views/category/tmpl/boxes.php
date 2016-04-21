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

<div class="spr_category_items">

<?php
if(count($this->category->items)>0) foreach($this->category->items as $i) {
    echo "
<div class='spr_box spr_category_item'>
    <a href='{$i->direct_link}'>
    <div class='spr_box_image'>
        <img src='{$i->mainimage}' />
    </div>
    <div class='spr_box_content'>
        <h3 class='spr_category_item_name'>{$i->name}</h3>
    </div>        
    <div class='spr_category_box_price'>
        <h3 class='spr_category_item_price' style='float:left'>{$i->formatted_price}</h3>
    </div>
    <div class='spr_category_button'>Details</div>
    </a>
</div>";
} ?>

</div>