<?php
/* -------------------------------------------
Module: mod_salespro_categories
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;
?> 

<div id="mod_salespro_search">
<form action="<?php echo $formurl; ?>" method="get">
<input type="hidden" name="option" value="com_salespro" />
<input type="hidden" name="view" value="category" />
<input type="hidden" name="name" id="spr_srch_cat_name" value="" />
<input type="hidden" name="spr_page" value="0" />
<input type="hidden" name="spr_sort" value="sort" />
<input type="hidden" name="spr_dir" value="ASC" />
<input type="submit" value="Search" class="mod_salespro_search_submit" />
<select name="id" id="spr_srch_cat_id" onchange="changeSPCat()">
<option value="0" category=""><?php echo JText::_('MOD_SALESPRO_SEARCH_CATEGORY'); ?></option>
<?php
foreach($categories as $cat) {
    echo "<option value='{$cat->id}' category='{$cat->alias}'>{$cat->name}</option>";
} ?>
</select>
<input type="text" name="spr_search_name" id="spr_srch_search_name" placeholder="<?php echo JText::_('MOD_SALESPRO_SEARCH_SEARCHING'); ?>" />
</form>
<script>
function changeSPCat() {
    jQuery('#spr_srch_cat_name').val(jQuery('#spr_srch_cat_id option:selected').attr('category'));
}
</script>
</div>
<style>
#mod_salespro_search {
    overflow: auto;
    clear: both;
    margin: 0 0 0 10px;
	float: right;
}

#mod_salespro_search form {
    margin: 0;
    padding: 0;
}

#mod_salespro_search input {
    margin: 0;
    padding: 0 4px;
    font-size: 12px;
    height: 30px;
    width: 290px;
    border: 1px solid #ddd;
    border-right: 0;
    float: right;
	border-radius: 0;
}

#mod_salespro_search select {
    line-height: 26px;
    margin: 0;
    padding: 0;
    font-size: 12px;
    padding: 5px;
    width: auto;
    height: 32px;
    border: 1px solid #ddd;
    float: right;
	border-radius: 0;
    
}

#mod_salespro_search input.mod_salespro_search_submit {
    padding: 6px 25px;
    text-align: center;
    background: #c12200;
    color: white;
    border: 0;
    font-family: Arial, Verdana, sans-serif;
    font-size: 12px;
    font-weight: bold;
    height: 32px;
    width: auto;
    margin: 0;
    cursor: pointer;
    float: right;
}

#mod_salespro_search input.mod_salespro_search_submit:hover {
    text-decoration: underline;
}
</style>