<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_BAK_TITLE'), 'salespro');
?>

<link rel="stylesheet" href="components/com_salespro/resources/uploadifive/uploadifive.css" type="text/css" />
<script src="components/com_salespro/resources/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_BAK_HEADING'); ?></h1>
</div>

<div id="spr_tabs">

<div class="spr_tabs">
<ul>
    <li><a href="#about"><?php echo JText::_('SPR_BAK_ABOUT'); ?></a></li>
    <li><a href="#backups"><?php echo JText::_('SPR_BAK_BACKUPS'); ?></a></li>
    <li><a href="#restore"><?php echo JText::_('SPR_BAK_RESTORE'); ?></a></li>
    <li><a href="#config"><?php echo JText::_('SPR_BAK_CONFIG'); ?></a></li>
</ul>

<div id="about" style="min-height: 220px;">
<div class="spr_notab">

<div style="position: absolute; width: 220px;">
<img src="components/com_salespro/resources/images/backups.png" style="width: 220px; float: left;" />
<a href='index.php?option=com_salespro&view=backups&task=mkBackup&format=raw'>
<div class="spr_submit_button" id="createOptionBtn" style="margin: 20px; float: right;">
    <?php echo JText::_('SPR_BAK_CREATE'); ?>
    </div>
</a>
</div>

<div style="display: inline-block; margin-left: 250px;">
<h4><?php echo JText::_('SPR_BAK_WELCOME'); ?></h4>
<p><?php echo JText::_('SPR_BAK_ABOUT1'); ?></p>
<p><?php echo JText::_('SPR_BAK_ABOUT2'); ?></p>
<p><?php echo JText::_('SPR_BAK_ABOUT3'); ?>:</p>
<ul>
    <li><?php echo JText::_('SPR_BAK_ABOUT4'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT5'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT6'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT7'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT8'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT9'); ?></li>
    <li><?php echo JText::_('SPR_BAK_ABOUT10'); ?></li>
</ul>
<p><?php echo JText::_('SPR_BAK_ABOUT11'); ?><strong> <a href='http://www.sales-pro.co.uk/index.php?option=com_kunena&view=category&layout=list&catid=0' target='_blank'>http://sales-pro.co.uk</a></strong>.</p>
</div>
</div>
</div>

<!-- CURRENT BACKUPS -->
<div id="backups">
<div class="spr_notab" style="min-height: 140px;">
<div style="position: absolute; width: 220px;">
<img src="components/com_salespro/resources/images/backups.png" style="width: 220px; float: left;" />
<a href='index.php?option=com_salespro&view=backups&task=mkBackup&format=raw'>
<div class="spr_submit_button" id="createOptionBtn" style="margin: 20px; float: right;">
    <?php echo JText::_('SPR_BAK_CREATE'); ?>
    </div>
</a>
</div>

<div style="display: block; margin-left: 250px;">
<h4><?php echo JText::_('SPR_BAK_YOURBKPS'); ?></h4>

<table class="spr_table" style="width: 100%">
<thead>
<tr><th class="nowrap" style="text-align:left"><?php echo JText::_('SPR_BAK_NAME'); ?></th><th class="nowrap center" width="1%"><?php echo JText::_('SPR_BAK_DATE'); ?></th><th class="nowrap center" width="1%"><?php echo JText::_('SPR_BAK_SIZE'); ?></th><th class="nowrap center" width="1%"><?php echo JText::_('SPR_BAK_DOWNLOAD'); ?></th><th class="nowrap center" width="1%"><?php echo JText::_('SPR_BAK_RESTORE'); ?></th><th class="nowrap center" width="1%"><?php echo JText::_('SPR_BAK_DELETE'); ?></th></tr>
</thead>
<tbody>

<?php if(count($this->backups)>0) foreach($this->backups as $b) {
    echo "<tr>
        <td><a href=''>{$b->name}</a></td>
        <td class='nowrap center'>{$b->date}</td>
        <td>{$b->size}</td>
        <td align='center'><a href='index.php?option=com_salespro&view=backups&task=dlBackup&dl={$b->name}' class='spr_icon spr_icon_download'>&nbsp;</a></td>
        <td align='center'><a href='#' onclick='restore(\"{$b->name}\")' class='spr_icon spr_icon_restore'>&nbsp;</a></td>
        <td align='center'><a href='index.php?option=com_salespro&view=backups&task=delBackup&dl={$b->name}' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
    </tr>";
} ?>
</tbody>
</table>

</div>
</div>
</div>
<!-- END OF CURRENT BACKUPS -->

<!-- RESTORE -->
<div id="restore">
<div class="spr_notab" style="min-height: 140px;">
<div style="position: absolute; width: 220px;">
<img src="components/com_salespro/resources/images/backups.png" style="width: 220px; float: left;" />
</div>

<div style="display: block; margin-left: 250px;">
<h4><?php echo JText::_('SPR_BAK_RESTOREBKP'); ?></h4>

<fieldset class="spr_fieldset">
<form action="index.php?option=com_salespro&view=backups&task=restoreBackup" method="post" id="sprRestore">
<div class="spr_field">
    <label for="spr_bkp_backup"><?php echo JText::_('SPR_BAK_SELECTBKP'); ?>: </label>
    <select id="spr_bkp_backup" name="spr_bkp_backup" style="width: 250px;">
    <?php if(count($this->backups)>0) foreach($this->backups as $b) {
        echo "<option value='{$b->name}'>{$b->name}</option>";
    } ?>
    </select>
</div>

<script>
function restore($bk) {
    jQuery('#spr_bkp_backup').val($bk);
    switchTab('restore');
}
function selectAll() {
    var sel = jQuery('#spr_bkp_what_all');
    if(sel.prop('checked') === true) {
        jQuery('input[name="spr_bkp_what[]"]').prop('checked', true);
    } else {
        jQuery('input[name="spr_bkp_what[]"]').prop('checked', false);
    }
    return;
}
</script>

<div class="spr_field">
    <label><?php echo JText::_('SPR_BAK_SELECTWHAT'); ?>: </label>
    <table>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_all" name="spr_bkp_what[]" value="all" onclick="selectAll()" />
            </td>
            <td>
                <label for="spr_bkp_what_all"><strong><?php echo JText::_('SPR_BAK_SELECTALL'); ?></strong></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_cats" name="spr_bkp_what[]" value="cats" />
            </td>
            <td>
                <label for="spr_bkp_what_cats"><?php echo JText::_('SPR_BAK_CATS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_regions" name="spr_bkp_what[]" value="regions" />
            </td>
            <td>
                <label for="spr_bkp_what_regions"><?php echo JText::_('SPR_BAK_REGS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_shipping" name="spr_bkp_what[]" value="shipping" />
            </td>
            <td>
                <label for="spr_bkp_what_shipping"><?php echo JText::_('SPR_BAK_SHIPS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_payment" name="spr_bkp_what[]" value="payment" />
            </td>
            <td>
                <label for="spr_bkp_what_payment"><?php echo JText::_('SPR_BAK_PAYS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_emails" name="spr_bkp_what[]" value="emails" />
            </td>
            <td>
                <label for="spr_bkp_what_emails"><?php echo JText::_('SPR_BAK_EMAILS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_sales" name="spr_bkp_what[]" value="sales" />
            </td>
            <td>
                <label for="spr_bkp_what_sales"><?php echo JText::_('SPR_BAK_SALES'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_users" name="spr_bkp_what[]" value="users" />
            </td>
            <td>
                <label for="spr_bkp_what_users"><?php echo JText::_('SPR_BAK_USERS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_widgets" name="spr_bkp_what[]" value="widgets" />
            </td>
            <td>
                <label for="spr_bkp_what_widgets"><?php echo JText::_('SPR_BAK_WIDGETS'); ?></label>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="spr_bkp_what_configs" name="spr_bkp_what[]" value="configs" />
            </td>
            <td>
                <label for="spr_bkp_what_configs"><?php echo JText::_('SPR_BAK_CONFIGS'); ?></label>
            </td>
        </tr>
    </table>
</div>

<div class="spr_field">
    <label>Begin Restore: </label>
<a href='#' onclick="jQuery('#sprRestore').submit();">
    <div class="spr_submit_button" style="margin: 0; float: left; padding-left: 60px;background-image: url('components/com_salespro/resources/css/images/icons/gold/history.png')" id="createOptionBtn">
        <?php echo JText::_('SPR_BAK_RESTOREBTN'); ?>
    </div>
</a>
</div>
</form>
</fieldset>


</div>
</div>
</div>
<!-- END OF RESTORE -->

<!-- CONFIGURATION -->
<div id="config">
<div class="spr_notab" style="min-height: 140px;">
<div style="position: absolute; width: 220px;">
<img src="components/com_salespro/resources/images/backups.png" style="width: 220px; float: left;" />
</div>

<div style="display: block; margin-left: 250px;">
<h4><?php echo JText::_('SPR_BAK_CONFIG'); ?></h4>

<fieldset class="spr_fieldset">
<form action="index.php?option=com_salespro&view=backups&task=saveConfig" method="post" id="sprSave">

<div class="spr_field">
    <label for="spr_auto"><?php echo JText::_('SPR_BAK_AUTOBKP'); ?></label>
    <select id="spr_auto" name="spr_backups_config[auto]"><?php echo sprConfig::_options($this->config->auto,array(JText::_('SPR_BAK_OFF'),JText::_('SPR_BAK_HOURLY'),JText::_('SPR_BAK_DAILY'), JText::_('SPR_BAK_WEEKLY'), JText::_('SPR_BAK_MONTHLY'))); ?></select>
</div>
<div class="spr_field">
    <label for="spr_keep"><?php echo JText::_('SPR_BAK_KEEPBKP'); ?></label>
    <select id="spr_keep" name="spr_backups_config[keep]"><?php echo sprConfig::_options($this->config->keep,array(JText::_('SPR_BAK_FOREVER'),JText::_('SPR_BAK_ONEDAY'), JText::_('SPR_BAK_ONEWEEK'), JText::_('SPR_BAK_ONEMONTH'), JText::_('SPR_BAK_TWOMONTHS'))); ?></select>
</div>

<div class="spr_field">
    <label for="spr_gzip"><?php echo JText::_('SPR_BAK_ENABLEGZIP'); ?></label>
    <select id="spr_gzip" name="spr_backups_config[gzip]"><?php echo sprConfig::_options($this->config->gzip,'yesno'); ?></select>
</div>
<div class="spr_field">
    <label for="spr_optimize"><?php echo JText::_('SPR_BAK_OPTIMIZE'); ?></label>
    <select id="spr_optimize" name="spr_backups_config[optimize]"><?php echo sprConfig::_options($this->config->optimize,'yesno'); ?></select>
</div>
<div class="spr_field">
    <label for="spr_repair"><?php echo JText::_('SPR_BAK_REPAIR'); ?></label>
    <select id="spr_repair" name="spr_backups_config[repair]"><?php echo sprConfig::_options($this->config->repair,'yesno'); ?></select>
</div>

<div class="spr_field">
    <label for="spr_email"><?php echo JText::_('SPR_BAK_EMAIL'); ?></label>
    <select id="spr_email" name="spr_backups_config[email]"><?php echo sprConfig::_options($this->config->email,'yesno'); ?></select>
</div>
<div class="spr_field">
    <label for="spr_address"><?php echo JText::_('SPR_BAK_ADDRESS'); ?></label>
    <input type="text" id="spr_address" style="width: 160px;" name="spr_backups_config[address]" value="<?php echo $this->config->address; ?>" />
</div>

<div class="spr_field">
    <label><?php echo JText::_('SPR_BAK_SAVECONFIG'); ?>: </label>
<a href='#' onclick="jQuery('#sprSave').submit();">
    <div class="spr_submit_button" style="margin: 0; float: left; padding-left: 60px;background-image: url('components/com_salespro/resources/css/images/icons/gold/history.png')" id="createOptionBtn">
        <?php echo JText::_('SPR_BAK_SAVECONFIG'); ?>
    </div>
</a>
</div>
</form>
</fieldset>
</div>
</div>
</div>
<!-- CSV EXPORT -->

</div>
</div>
</div>
</div>