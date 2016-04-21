<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();

if ($app->isSite()) {
	JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
}

?>
<div id="spr_container">

<div id="spr_header">
    <img src="components/com_salespro/resources/images/salespro.png" style="height: 85px;" />
    <h1 style="float: right; margin-top: 30px;"><?php echo JText::_('SPR_ITEM_HEADING'); ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div>

<!-- SEARCH BOX -->
<div class="spr_filter" style="width: auto; margin: 10px 0;">
    <div id="spr_search_box">
        <div class="spr_field spr_search_field">
            <label for="spr_search_name">Search:</label>
            <input id="spr_search_name" name="spr_search_name" type="text" placeholder="Search: Product name" value="<?php echo $this->search['name']; ?>" />
            <div style="float:left;" rel="spr_search_box" class="spr_icon spr_icon_plus" onclick="upDown(this);">&nbsp;</div>
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_category_id">Category:</label>
            <select id="spr_search_category_id" name="spr_search_category_id">
            <option value="">-- All categories --</option>
            <?php if(count($this->all_categories)>0)
            foreach($this->all_categories as $c) {
                echo $this->categories->showCatOption($c,$this->search['category_id']);
            } ?>
            </select>
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_min_price">Min price:</label>
            <input id="spr_search_min_price" name="spr_search_min_price" type="text" value="<?php echo $this->search['min_price']; ?>" placeholder="Min. price" />
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_max_price">Max Price:</label>
            <input id="spr_search_max_price" name="spr_search_max_price" type="text" class="spr_date" value="<?php echo $this->search['max_price']; ?>" placeholder="Max. price" />
        </div>
        <div class="spr_search_buttons">
            <input type="submit" name="spr_search_submit" class="spr_input spr_search_button" value="Go!" />
            <input type="submit" name="spr_search_clear" class="spr_input spr_search_button" value="Clear" />
        </div>
    </div>
</div>
<!-- END OF SEARCH BOX -->

<fieldset class="spr_fieldset">

<table class="table table-striped" id="categoryList">
<thead>
<tr>
<th><a href="#" onclick="sort('z.name')">Name</a></th>
<th><a href="#" onclick="sort('c.name')">Category</a></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.status')">Status</a></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.featured')">Featured</a></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.price')">Price</a></th>
</tr>
</thead>
<tbody id='spr_items_list'>
<?php
foreach($this->items as $item) {
    $ayes = ($item->status === '1') ? 'yes':'no';
    $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$item->id}' onclick='status({$item->id});' style='margin:0 auto;'>&nbsp;</a>";
    $ayes = ($item->featured === '1') ? 'yes':'no';
    $feat = "<div class='spr_icon spr_icon_{$ayes}' id='featured_{$item->id}' onclick='featured({$item->id});' style='margin:0 auto;'>&nbsp;</div>";
    echo "<tr id='spr_items_{$item->id}'><td>
    <a href='#' onclick='if(window.parent) window.parent.selectItem(\"".(int)$item->id."\",\"".$this->escape(addslashes($item->name))."\")'>{$item->name}</a></td><td>{$item->category}</td><td class='center'>{$status}</td><td class='center'>{$feat}</td><td width='1%' class='nowrap center'>{$item->price}</td></tr>";
} ?>
</tbody>
</table>

</fieldset>
<?php echo $this->class->pageControls(); ?>
</div>
<input type="hidden" name="spr_table" id="spr_table" value="items" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="items" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>