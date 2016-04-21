<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_IMPORT_HEADING'), 'salespro');
?>

<link rel="stylesheet" href="components/com_salespro/resources/uploadifive/uploadifive.css" type="text/css" />
<script src="components/com_salespro/resources/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_IMPORT_WIZARD'); ?></h1>
</div>

<div id="spr_tabs">

<div class="spr_tabs">
<ul>
    <li><a href="#welcome" onclick="jQuery('#spr_import').hide();"><?php echo JText::_('SPR_IMPORT_TAB_WELCOME'); ?></a></li>
    <li><a href="#vm1" onclick="jQuery('#spr_import').show();"><?php echo JText::_('SPR_IMPORT_TAB_VM1'); ?></a></li>
    <li><a href="#vm2" onclick="jQuery('#spr_import').show();"><?php echo JText::_('SPR_IMPORT_TAB_VM23'); ?></a></li>
</ul>

<div id="welcome" style="min-height: 220px;">
<div class="spr_notab">
<img src="components/com_salespro/resources/images/wizard.png" style="width: 220px; position: absolute;" />
<div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_IMPORT_WELCOME'); ?></h4>
<p><?php echo JText::_('SPR_IMPORT_ABOUT1'); ?></p>
<p><?php echo JText::_('SPR_IMPORT_ABOUT2'); ?></p>
<p><?php echo JText::_('SPR_IMPORT_ABOUT3'); ?></p>
<p><?php echo JText::_('SPR_IMPORT_ABOUT4'); ?><strong> <a href='http://www.sales-pro.co.uk/index.php?option=com_kunena&view=category&layout=list&catid=0' target='_blank'>http://sales-pro.co.uk</a></strong>.</p>
</div>
</div>
</div>

<!-- VIRTUEMART 1 IMPORT -->
<div id="vm1">
<div class="spr_notab" style="min-height: 140px;">
<img src="components/com_salespro/resources/images/vm-sm.png" style="width: 140px; position: absolute; margin-left: 40px;" />
<div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_IMPORT_MIGRATE_VM'); ?></h4>

<p><strong><?php echo JText::_('SPR_IMPORT_STEP1'); ?>: </strong><?php echo JText::_('SPR_IMPORT_COPY_IMAGES'); ?> /components/com_virtuemart/shop_image/</p>
<p><?php echo JText::_('SPR_IMPORT_COPY_IMAGES_FOLDERS'); ?>:</p>
<ul>
<li><?php echo JText::_('SPR_IMPORT_COPY_FILES'); ?> <strong>/components/com_virtuemart/shop_image/category/</strong> <?php echo JText::_('SPR_IMPORT_TO'); ?> <strong>/images/salesPro/categories/</strong></li>
<li><?php echo JText::_('SPR_IMPORT_COPY_FILES'); ?> <strong>/components/com_virtuemart/shop_image/product/</strong> <?php echo JText::_('SPR_IMPORT_TO'); ?> <strong>/images/salesPro/items/</strong></li>
<li><?php echo JText::_('SPR_IMPORT_COPY_FILE'); ?> <strong>/components/com_virtuemart/shop_image/default.jpg</strong> <?php echo JText::_('SPR_IMPORT_TO'); ?> <strong>/images/salesPro/default/default.jpg</strong></li>
</ul>

<p><strong><?php echo JText::_('SPR_IMPORT_STEP2'); ?>: </strong><?php echo JText::_('SPR_IMPORT_STEP2_INFO'); ?> <strong><a href='http://sales-pro.co.uk/dl_plugins/plg_salespro_vm_migrate.zip'>[<?php echo JText::_('SPR_IMPORT_DL'); ?>]</a></strong></p>

<p><strong><?php echo JText::_('SPR_IMPORT_STEP3'); ?>: </strong><?php echo JText::_('SPR_IMPORT_STEP3_INFO'); ?></p>

</div>
</div>
</div>
<!-- END OF VM 1 IMPORT -->

<!-- VIRTUEMART 2 IMPORT -->
<div id="vm2">
<div class="spr_notab" style="min-height: 140px;">
<img src="components/com_salespro/resources/images/vm-sm.png" style="width: 140px; position: absolute; margin-left: 40px;" />
<div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_IMPORT_MIGRATE_VM2'); ?></h4>

<p style="clear:both; overflow: auto;"><strong><?php echo JText::_('SPR_IMPORT_STEP1'); ?>: </strong><?php echo JText::_('SPR_IMPORT_COPY_IMAGES'); ?> /images/stories/virtuemart/</p>
<p><?php echo JText::_('SPR_IMPORT_COPY_IMAGES_FOLDERS'); ?>:</p>
<ul>
<li><?php echo JText::_('SPR_IMPORT_COPY_FILES'); ?> <strong>/images/stories/virtuemart/category/</strong> <?php echo JText::_('SPR_IMPORT_TO'); ?> <strong>/images/salesPro/categories/</strong></li>
<li><?php echo JText::_('SPR_IMPORT_COPY_FILES'); ?> <strong>/images/stories/virtuemart/product/</strong> <?php echo JText::_('SPR_IMPORT_TO'); ?> <strong>/images/salesPro/items/</strong></li>
</ul>
<p><strong><?php echo JText::_('SPR_IMPORT_STEP2'); ?>: </strong><?php echo JText::_('SPR_IMPORT_VM2_STEP2_INFO'); ?> <strong><a href='http://sales-pro.co.uk/dl_plugins/plg_salespro_vm2_migrate.zip'>[<?php echo JText::_('SPR_IMPORT_DL'); ?>]</a></strong></p>
<p><strong><?php echo JText::_('SPR_IMPORT_STEP3'); ?>: </strong><?php echo JText::_('SPR_IMPORT_STEP3_INFO'); ?></p>
</div>
</div>
</div>
<!-- END OF VM2 IMPORT -->
</div>
</div>


<div id="spr_import" style="display: none; margin: 0 auto; width: 400px; border: 1px solid white; padding: 5px 10px; overflow: auto; clear: both;">
<div class="spr_import_step" id="spr_import_step1">
    <div id="spr_import_html5"><p><?php echo JText::_('SPR_IMPORT_HTML5ONLY'); ?></p><p><?php echo JText::_('SPR_IMPORT_HTML5ONLY2'); ?></p></div>
    <div id="spr_import_button">
        <input id="file_upload" name="file_upload" type="file" multiple="false" />
    	<div id="queue"></div>
    </div>
</div>

<div class="spr_import_step" id="spr_import_step2" style="display: none;">
<h3><?php echo JText::_('SPR_IMPORT_INPROGRESS'); ?></h3>
<p><?php echo JText::_('SPR_IMPORT_CLOSE'); ?></p>
<div id="spr_import_message" class="ajax">
<div id="progressBar"><div></div></div>
</div>
</div>


<div class="spr_import_step" id="spr_import_step3" style="display: none;">
<p><strong><?php echo JText::_('SPR_IMPORT_COMPLETE'); ?></strong></p>
</div>
</div>


</div>

</div>

<script type='text/javascript'>
var tablen = 0;
var complete = 0;
</script>