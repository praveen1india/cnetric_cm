<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_EMAIL_HEADING'), 'salespro');
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_EMAIL_MANAGER'); ?></h1>
</div>

<div id="spr_main">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset" style="margin-top: 20px;">

<table class="spr_table" id="emailList">
<thead>
<tr>
<th align="left"><?php echo JText::_('SPR_EMAIL_SUBJECT'); ?></th>
<th align="left"><?php echo JText::_('SPR_EMAIL_TRIGGER'); ?></th>
<th align="left"><?php echo JText::_('SPR_EMAIL_PRODTYPES'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_EMAIL_ACTIVE'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_EMAIL_PREVIEW'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_EMAIL_ACTION'); ?></th>
</tr>
</thead>
<tbody>
<?php
foreach($this->emails as $e) {
    $ayes = ($e->status == '1') ? 'yes':'no';
    $active = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$e->id}' onclick='status({$e->id});' style='margin:0 auto;'>&nbsp;</a>";
    echo "
<tr>
    <td><a href='#' onclick='edit({$e->id})'>{$e->subject}</a></td>
    <td>{$e->trigger_name}</a></td>
    <td>{$e->prodtypes_name}</a></td>
    <td class='nowrap center'>{$active}</td>
    <td class='nowrap center'><a href='#' onclick='preview({$e->id})' class='spr_icon spr_icon_preview'>&nbsp;</a></td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$e->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='del({$e->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
</tr>";
} ?>
</tbody>
</table>

</fieldset>

<div id="email_preview" title="<?php echo JText::_('SPR_EMAIL_PREVIEW'); ?>">


</div>

<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script>
function preview ($id) {
    jQuery.ajax({
            url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=getDummy&tab=emails',
            dataType: 'text',
            cache: false,
            data: {email_id:$id},
            type: 'POST',
            success: function(text) {
                jQuery('#email_preview').html(text);
                jQuery( "#email_preview" ).dialog({ height: 'auto', width: 600, modal:true });
            },
            error: function() {
                alert('<?php echo JText::_('SPR_CHECKLOGIN'); ?>');
            }
        });
}

</script>

</div>
<input type="hidden" name="spr_table" id="spr_table" value="emails" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="emails" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>

</div>