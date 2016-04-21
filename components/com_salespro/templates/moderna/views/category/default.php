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

<div id="salespro" class="salespro">

<div id="moderna_breadcrumbs">
<ul class="moderna_breadcrumbs"><?php echo sprCategories::_getBreadCrumbs($this->category->id); ?></ul>
</div>

<div id='moderna_cat_desc' style='margin: 0 0 20px 0; overflow: auto; clear: both;'>

<?php
if($this->category->params->show_title === '1') echo "<h1>{$this->category->name}</h1>";
if($this->category->params->show_image === '1' && strlen($this->category->image)>0) {
    echo "<img id='moderna_cat_image' src='".salesProImage::_($this->category->image,0,300)."' style='width: 50% !important; float: right; margin-left: 10px;' />";
}
if($this->category->params->show_desc === '1') echo "<p>".nl2br($this->category->desc)."</p>";
?>
</div>

<?php if($this->category->params->subcats === '1') {
    $params = array(
        'id'=>$this->category->id,
        'showtitle' => '2',
        'btn' => '1',
        'cols' => $this->category->params->boxcols,
        'count' => $this->category->params->items,
        'levels' => $this->category->params->subcategory_levels
    );
    echo sprWidgets::showNewWidget('Subcategories','categories',$params);
}

//DISPLAY SEARCH RESULTS
if(isset($this->search['name']) && strlen($this->search['name']) > 0) 
    echo "<p>".JText::_('SPR_SHOWING_RESULTS').": <strong>{$this->search['name']}</strong></p>";
if($this->total < 1) {
    if($this->category->params->subcats === '2' || count($this->subcategories)<1) echo "<h3>".JText::_('SPR_SORRY_NOTHINGFOUND')."</h3>";
} else {
    
    //SHOW SORT BAR
    if($this->category->params->show_sortbar === '1') echo "<div id='moderna_cat_sort'><label>".JText::_('SPR_SORTBY').":</label><ul>{$this->sortvars_list}</ul></div>";
?>

<form action="" method="get" id="modernaSearchForm" autocomplete="off">
<input type="hidden" name="option" value="com_salespro" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
<input type="hidden" name="name" value="<?php echo $this->category->alias; ?>" />
<input type="hidden" name="spr_page" id="spr_page" value="0" />
<input type="hidden" name="spr_sort" id="spr_sort" value="<?php echo $this->order['sort']; ?>" />
<input type="hidden" name="spr_dir" id="spr_dir" value="<?php echo $this->order['dir']; ?>" />
<input type="hidden" name="spr_search_name" value="<?php //echo $this->search['name']; ?>" />
</form>
<?php
//GET CATEGORY SPECIFIC LAYOUT
//if(file_exists($this->layout)) include($this->layout);
$params = array(
    'catid'=>$this->category->id,
    'showtitle' => '2',
    'btn' => '1',
    'cols' => $this->category->params->boxcols,
    'count' => $this->category->params->items,
    'layout' => $this->category->params->layout,
);
echo sprWidgets::showNewWidget('','catItems',$params,$this->category->items);

if($this->category->params->show_pagesbar === '1' || $this->totalpages > 1) {
    echo "<div id='moderna_cat_pages'><div id='moderna_cat_pagelist'>{$this->pagenumbers}</div></div>";
} } ?>


<?php echo sprWidgets::_showWidgets('category'); ?>

</div>