<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

$this->home = (object) array(
    'show_title' => '1',
    'show_categories' => '1',
    'category_items' => '6',
    'category_boxcols' => '3'
); ?>

<?php 
    if(count($this->subcategories) < $this->home->category_boxcols) {
        $mywidth = (count($this->subcategories) / $this->home->category_boxcols) * 100;
        $boxwidth = 100 / $this->home->category_boxcols;
    } else {
        $mywidth = 100;
        $boxwidth = 100 / $this->home->category_boxcols;
    }
    echo "<div id='spr_home_categories' style='width: {$mywidth}%;'>";
    if(count($this->subcategories)>0) foreach($this->subcategories as $n=>$i) {
        if($n%$this->home->category_boxcols === 0) echo "<div class='spr_home_categories_row'>";
        echo "
        <div class='spr_home_categories_box' style='overflow: hidden; max-width: {$boxwidth}%;'>
            <a href='{$i->direct_link}'>
                <div class='spr_home_categories_box_image' style='overflow: hidden'>
                    <img src='".salesProImage::_($i->image,300, 225)."' />
                </div>
                <h3 class='spr_home_categories_box_name'>{$i->name}</h3>
            </a>
        </div>";
        if($n%$this->home->category_boxcols === ($this->home->category_boxcols - 1) || ($n === count($this->subcategories)-1)) echo "</div>";
    }
    echo "</div>";
?>