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
<ul class="moderna_breadcrumbs"><li><a href='<?php echo JURI::base().'index.php?option=com_salespro'; ?>'><?php echo JText::_('SPR_HOME'); ?></a></li><li class="moderna_active_breadcrumb"><?php echo $this->category->name; ?></li></ul>
</div>

<div id='moderna_cat_desc' style='margin: 0 0 20px 0; overflow: auto; clear: both;'>

<?php
if($this->category->params->show_image === '1' && strlen($this->category->image)>0) {
    echo "<img id='moderna_cat_image' src='".salesProImage::_($this->category->image,0,250)."' style='max-height: 250px; width: auto; float: right;' />";
} ?>
<?php if($this->category->params->show_title === '1') echo "<h1>{$this->category->name}</h1><br />"; ?>
<?php
if($this->category->params->show_desc === '1') echo "<p>".nl2br($this->category->desc)."</p>";
?>
</div>

<?php if($this->category->params->subcategory === '0' || $this->category->params->subcategory === '2') include('subcats.php'); ?>

<?php if(count($this->subcategories)<1 || $this->category->params->subcategory !== '0') { //SHOW ITEMS IF THIS IS A FINAL SUBCAT OR SUBCAT ITEMS ARE ALLOWED ?>

<?php if($this->category->params->show_sortbar === '1') echo "<div id='moderna_cat_sort'><label>".JText::_('SPR_SORTBY').":</label><ul>{$this->sortvars_list}</ul></div>"; ?>

<?php if($this->category->params->show_pagesbar === '1' || $this->totalpages > 1) {
    echo "<div id='moderna_cat_pages'><div id='moderna_cat_pagelist'>{$this->pagenumbers}</div></div>";
} ?>

<?php

//DISPLAY SEARCH RESULTS
if(isset($this->search['name']) && strlen($this->search['name']) > 0) 
    echo "<p>".JText::_('SPR_SHOWING_RESULTS').": <strong>{$this->search['name']}</strong></p>";
if($this->total < 1) {
    if(count($this->subcategories)<1 || $this->category->params->subcategory === '1') echo "<h3>".JText::_('SPR_SORRY_NOTHINGFOUND')."</h3>";
} else { ?>

<form action="" method="get" id="modernaSearchForm" autocomplete="off">
<input type="hidden" name="option" value="com_salespro" />
<input type="hidden" name="spr_page" id="spr_page" value="" />
<input type="hidden" name="spr_sort" id="spr_sort" value="<?php echo $this->order['sortvars']; ?>" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="id" value="<?php echo $this->category->id; ?>" />
<input type="hidden" name="spr_search_name" value="<?php echo $this->search['name']; ?>" />
<script>
jQuery('#moderna_cat_sort li').click(function() {
    var rel = jQuery('a',this).attr('rel');
    jQuery('#spr_sort').val(rel);
    jQuery('#modernaSearchForm').submit();
});
jQuery('.spr_category_sort_page').click(function() {
    var rel = jQuery(this).attr('rel');
    jQuery('#spr_page').val(rel);
    jQuery('#modernaSearchForm').submit();
});
</script>
</form>
<?php
//GET CATEGORY SPECIFIC LAYOUT
if(file_exists($this->layout)) include($this->layout);

}

} ?>
</div>