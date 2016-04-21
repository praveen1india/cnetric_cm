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
foreach($this->category->items as $i) {
    echo "
<div class='spr_category_item spr_list'>
    <a href='{$i->direct_link}'>
    <div class='spr_list_image'>
        <img src='".salesProImage::_($i->mainimage,90,120)."' />
    </div>
    <div class='spr_category_item_actions'>
        <h3 class='spr_category_item_price' style='text-align:right'>{$i->formatted_price}</h3>
        <div class='spr_category_button'>Details</div>
    </div>
    <div class='spr_list_content'>
        <h3 class='spr_category_item_name'>{$i->name}</h3>
        <p>{$i->mini_desc}</p>
    </div>
    </a>
</div>";
} ?>
</div>