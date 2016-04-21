<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_CAT_HEADING'), 'salespro');
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_CAT_PRODUCTCATS'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip">

<p><?php echo JText::_('SPR_CAT_TIP'); ?></p>

</div>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset">

<table class="spr_table" id="categoryList">
<thead>
<tr>
<th class="center nowrap" width="1%"><a href="#" onclick="sort('sort')"><?php echo JText::_('SPR_CAT_ORDER'); ?></a><?php if($this->class->order['sort'] === 'sort') echo $this->icon; ?></th>
<th align="left"><a href="#" onclick="sort('name');"><?php echo JText::_('SPR_CAT_NAME'); ?></a><?php if($this->class->order['sort'] === 'name') echo $this->icon; ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_CAT_ITEMS'); ?></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('status');"><?php echo JText::_('SPR_CAT_STATUS'); ?></a><?php if($this->class->order['sort'] === 'status') echo $this->icon; ?></th>
<th width="70" class="nowrap center"><?php echo JText::_('SPR_CAT_ACTION'); ?></th>
</tr>
</thead>
</table>
<ul class="categories cat0">
<?php
if(count($this->categories)>0) 
    foreach($this->categories as $c) 
        echo $this->class->showCat($c);
?>
</ul>

</fieldset>
<?php echo $this->class->pageControls(); ?>
<?php if($this->class->order['sort'] === 'sort') { ?>
<script>
jQuery(document).ready(function() {
    if(jQuery('ul.categories').length > 0) {
        jQuery("ul.categories").sortable({
            axis: 'y',
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                var page = jQuery('#spr_page').val();
                var limit = jQuery('#spr_limit').val();
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=categories',
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
        jQuery("ul.categories").disableSelection();
    }
});
</script>
<?php } ?>

<input type="hidden" name="spr_table" id="spr_table" value="categories" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="categories" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>