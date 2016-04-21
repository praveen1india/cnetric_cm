<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_ITEM_HEADING'), 'salespro');
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );

?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_ITEM_HEADING'); ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div id="spr_tabs">
    <div class="spr_tabs">
    
        <ul>
            <li><a href="#details"><?php echo JText::_('SPR_ITEM_HEADING'); ?></a></li>
            <li><a href="#prodtypes"><?php echo JText::_('SPR_CON_PRODTYPES'); ?></a></li>
            <li><a href="#prodattrs"><?php echo JText::_('SPR_ATTR_HEADING'); ?></a></li>
        </ul>
                
        <div id="details">
            <div class="spr_notab">

<fieldset class="spr_fieldset" style="margin-top: 20px;">

<table class="spr_table" id="productList" style="overflow: hidden">
<thead>
<tr>
<th nowrap="nowrap"><a href="#" onclick="sort('z.sort')"><?php echo JText::_('SPR_ITEM_ORDER'); ?></a><?php if($this->order['sort'] === 'z.sort') echo $this->icon; ?></th>
<th align='left'><a href="#" onclick="sort('z.name')"><?php echo JText::_('SPR_ITEM_NAME'); ?></a><?php if($this->order['sort'] === 'z.name') echo $this->icon; ?></th>
<th align='left'><a href="#" onclick="sort('c.name')"><?php echo JText::_('SPR_ITEM_MAINCAT'); ?></a><?php if($this->order['sort'] === 'c.name') echo $this->icon; ?></th>
<th align='left'><a href="#" onclick="sort('z.type')"><?php echo JText::_('SPR_ITEM_PRODTYPE'); ?></a><?php if($this->order['sort'] === 'z.type') echo $this->icon; ?></th>
<th><a href="#" onclick="sort('z.id')">ID</a><?php if($this->order['sort'] === 'z.id') echo $this->icon; ?></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.status')"><?php echo JText::_('SPR_ITEM_STATUS'); ?></a><?php if($this->order['sort'] === 'z.status') echo $this->icon; ?></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.featured')"><?php echo JText::_('SPR_ITEM_FEATURED'); ?></a><?php if($this->order['sort'] === 'z.featured') echo $this->icon; ?></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('z.price')"><?php echo JText::_('SPR_ITEM_PRICE'); ?></a><?php if($this->order['sort'] === 'z.price') echo $this->icon; ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
</tr>
</thead>
<tbody id='spr_items_list'>
<?php
foreach($this->items as $item) {
    $ayes = ($item->status === '1') ? 'yes':'no';
    $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$item->id}' onclick='status({$item->id});' style='margin:0 auto;'>&nbsp;</a>";
    $ayes = ($item->featured === '1') ? 'yes':'no';
    $feat = "<a class='spr_icon spr_icon_{$ayes}' id='featured_{$item->id}' onclick='featured({$item->id});' style='margin:0 auto;'>&nbsp;</a>";
    $order = ($this->order['sort'] === 'z.sort') ? "<span class='ui-icon ui-icon-arrowthick-2-n-s' style='margin: 0 10px;'>&nbsp;</span>" : $item->sort;
    
    echo "<tr id='spr_items_{$item->id}' myid='{$item->id}'><td width='1%' class='nowrap center'>{$order}</td><td><a href='#' onclick='edit({$item->id});'>{$item->name}</a></td><td>{$item->category_name}</td><td>{$item->prodtype->name}</td><td width='1%' class='nowrap center'>{$item->id}</td><td class='center'>{$status}</td><td class='center'>{$feat}</td><td width='1%' class='nowrap center'>".sprCurrencies::_format($item->price)."</td><td width='1%' class='nowrap center'><a href='#' onclick='copy({$item->id})' class='spr_icon spr_icon_copy'>&nbsp;</a> <a href='#' onclick='edit({$item->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteItem({$item->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
} ?>
</tbody>
</table>

</fieldset>
<?php echo $this->class->pageControls(); ?>

</div>
</div>


<div id="prodtypes">
<div class="spr_notab">

<fieldset class="spr_fieldset">
    <div class="spr_tip"><p><?php echo JText::_('SPR_PRODTYPE_INFO'); ?></p></div>
        <div class="spr_tab_right" id="ptEdit" style="padding: 10px; border-left: 1px solid #ddd; margin-left: 10px;display:none;">
            <fieldset class="spr_fieldset">
                <input type="hidden" id="t_id" value="" />
                <h4><?php echo JText::_('SPR_PRODTYPE_EDIT'); ?></h4>
                <div class="spr_field">
                    <label for="t_name"><?php echo JText::_('SPR_PRODTYPE_NAME'); ?></label>
                    <input id="t_name" id="t_name" />
                </div>
                <div class="spr_field">
                    <label for="t_var"><?php echo JText::_('SPR_PRODTYPE_VARIANTS'); ?></label>
                    <select id="t_var">
                        <?php echo sprProdTypes::_options(1,'yesno'); ?>
                    </select>
                </div>
                <div class="spr_field">
                    <label for="t_del"><?php echo JText::_('SPR_PRODTYPE_DELIVERY'); ?></label>
                    <select id="t_del">
                        <?php echo sprProdTypes::_options(1,'yesno'); ?>
                    </select>
                </div>
                <div class="spr_field">
                    <label for="t_sm"><?php echo JText::_('SPR_PRODTYPE_STOCKMAN'); ?></label>
                    <select id="t_sm">
                        <?php echo sprProdTypes::_options(1,'yesno'); ?>
                    </select>
                </div>
                <div class="spr_field">
                    <label for="t_dl"><?php echo JText::_('SPR_PRODTYPE_DOWNLOADABLE'); ?></label>
                    <select id="t_dl">
                        <?php echo sprProdTypes::_options(1,'yesno'); ?>
                    </select>
                </div>
                <div class="spr_field">
                    <label for="t_quantity"><?php echo JText::_('SPR_PRODTYPE_QUANTITY'); ?></label>
                    <select id="t_quantity">
                        <?php echo sprProdTypes::_options(1,'yesno'); ?>
                    </select>
                </div>
                <div class="spr_field">
                    <label for="t_tc"><?php echo JText::_('SPR_PRODTYPE_TANDC'); ?></label>
                    <select id="t_tc">
                        <?php echo sprProdTypes::_options(1,'global'); ?>
                    </select>
                </div>
                <div class="spr_confirm_buttons">
                    <a href="#" onclick="cancelPT();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                    <a href="#" onclick="savePT();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
                </div>
            </fieldset>
        </div>
        <div class="spr_tab_left" id="spr_prod_types">
        <div class="spr_big_button" id="createProdType"><?php echo JText::_('SPR_PRODTYPE_ADD'); ?></div>
        <table class="spr_table" id="spr_prodtypes" style="overflow: hidden">
        <thead>
        <tr>
        <th align="left"><?php echo JText::_('SPR_PRODTYPE_NAME'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_VARIANTS'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_DELIVERY'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_STOCKMAN'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_DOWNLOADABLE'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_QUANTITY'); ?></th>
        <th align="left" class='nowrap center' width='1%'><?php echo JText::_('SPR_PRODTYPE_TANDC'); ?></th>
        <th class="nowrap center" width="1%"><?php echo JText::_('SPR_ITEM_ACTION'); ?></th>
        </tr>
        </thead>
        <tbody class="ui-sortable" id="spr_prodtypes_list">
        <?php if(count($this->prodtypes)>0) foreach($this->prodtypes as $p) {
            echo $p->string;
        } ?>
        </tbody>
        </table>
    </div>
</fieldset>

</div>
</div>


<div id="prodattrs">
<div class="spr_notab">

<fieldset class="spr_fieldset">
    <div class="spr_tip"><p><?php echo JText::_('SPR_ATTR_INFO'); ?></p></div>
        <div class="spr_tab_right" id="atEdit" style="padding: 10px; border-left: 1px solid #ddd; margin-left: 10px; display: none;">
            <fieldset class="spr_fieldset">
                <input type="hidden" id="a_id" value="" />
                <h4><?php echo JText::_('SPR_ATT_EDIT'); ?></h4>
                
                <div class="spr_field">
                    <label for="a_name" style="width:auto"><?php echo JText::_('SPR_ATTR_ANAME'); ?></label>
                    <input id="a_name" name="a_name" type="text" value="" style="width:262px;" />
                </div>

                <div class="spr_field">
                    <label for="a_categories" style="width:auto"><?php echo JText::_('SPR_ATTR_ACATS'); ?></label>
                    <select name="a_categories[]" id="a_categories" style="width: 275px; height: 300px;" multiple="multiple">
                    <?php if(count($this->all_categories)>0)
                    foreach($this->all_categories as $c) {
                        echo $this->categories->showCatOption($c,0);
                    } ?>
                    </select>
                </div>
                
                <div class="spr_field">
                    <input id='value_id' type='hidden' value='' />
                    <label for="attributes_value"><?php echo JText::_('SPR_ATTR_AOPTION'); ?></label>
                    <input id="attributes_value" type="text" style='width:240px' /><a href='#' onclick='saveValue()' class='spr_icon spr_icon_save'>&nbsp;</a>
                </div>
                
                <div id="valuesList"></div>
                
                <div class="spr_confirm_buttons">
                    <a href="#" onclick="cancelAT();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
                    <a href="#" onclick="saveAT();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
                </div>
            </fieldset>
        </div>
        <div class="spr_tab_left" id="spr_attributes" style="padding-right: 0;">
        <div class="spr_big_button" id="createAttribute"><?php echo JText::_('SPR_ATT_CREATE'); ?></div>
        
        <table class="spr_table" id="attributeList">
            <thead>
            <tr>
            <th align="left"><?php echo JText::_('SPR_ATTR_NAME'); ?></th>
            <th align="left"><?php echo JText::_('SPR_ATTR_CATS'); ?></th>
            <th width="1%" class="nowrap center"><?php echo JText::_('SPR_ATTR_ACTIONS'); ?></th>
            </tr>
            </thead>
            <tbody id="spr_attributes_list">
            <?php
            if(count($this->attributes)>0) 
                foreach($this->attributes as $a) {
                    $values = '';
                    foreach($a->values as $v) {
                        $values .= str_replace("'", '"',$v->html);
                    }
                    echo "<tr id='spr_attributes_{$a->id}' a_name='{$a->name}' a_categories='".json_encode($a->categories)."' a_values='{$values}'><td class='a_name'>{$a->name}</td><td class='a_cats'>{$a->category_names}</td>
                    <td class='nowrap center' width='1%'><a href='#' onclick='editAT({$a->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteAT({$a->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
                    </tr>";
                }
            ?>
            </tbody>
            </table>
    </div>
</fieldset>

</div>
</div>
</div>
</div>

<input type="hidden" name="spr_table" id="spr_table" value="items" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="items" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>

<?php
if($this->order['sort'] === 'z.sort') { ?>
<script>
jQuery(document).ready(function() {
    //ADD SORTABLE
    if(jQuery('#spr_items_list').length > 0) {
        jQuery( "#spr_items_list" ).sortable({
            axis: 'y',
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                var page = jQuery('#spr_page').val();
                var limit = jQuery('#spr_limit').val();
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=items',
                    data: {'order':order,'page':page,'limit':limit},
                    type: 'POST',
                    dataType: 'text',
                    cache: false,
					timeout: 10000,
					error: function() {
				        alert('Sorry, the request timed out! Please check you are logged in, and try again');
					}
                });
            }
        });
        jQuery( "#spr_items_list").disableSelection();
    }
});
</script>
<?php } ?>