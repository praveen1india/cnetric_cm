<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_DASH_WIZHEADING'), 'salespro');    
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#7"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_DASH_WELCOMEWIZ'); ?></h1>
</div>

<div id="spr_main">
<div class="spr_notab">

<fieldset class="spr_fieldset">
<img src="components/com_salespro/resources/images/wizard.png" style="width: 220px; position: absolute;" />
<div style="display: inline-block; margin-left: 220px;">

<h4><?php echo JText::_('SPR_WIZ_ABOUT1'); ?></h4>
<p><?php echo JText::_('SPR_WIZ_ABOUT2'); ?></p>
<p><?php echo JText::_('SPR_WIZ_ABOUT3'); ?></p>
<p><?php echo JText::_('SPR_WIZ_ABOUT4'); ?></p>
<p><?php echo JText::_('SPR_WIZ_ABOUT5'); ?></p>
<p><?php echo JText::_('SPR_WIZ_ABOUT6'); ?></p>

<?php if($this->install !== 1) echo '<div id="start_install" onclick="install(0)">'.JText::_('SPR_WIZ_START').'</div>'; ?>
<div id="install_progress">
<div id="upgrade" class="ajax"><?php echo JText::_('SPR_WIZ_DL'); ?></div>
<div id="progressBar"><div>0% <?php echo JText::_('SPR_WIZ_COMPLETE'); ?></div></div>
<div id="upgradeComplete"><a href='index.php?option=com_salespro' class='spr_start'>Continue to the dashboard</a></div>
</div>
</div>
</fieldset>

</div>
</div>
</div>
</div>

<style>

#upgradeComplete {
    display: none;
    overflow: auto;
    clear: both;
    width: auto;
}

#start_install {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 20px 20px;
    width: auto;
    float: left;
    color: green;
    cursor: pointer;
}

#start_install:hover {
    color: #fff;
    background: #a9db80;
}
#install_progress {
    display: none;
    float: left;
}


#progressBar {
    width: 300px;
    margin: 20px 5px;
    height: 22px;
    background: rgb(238,238,238); /* Old browsers */
    background: -moz-linear-gradient(top, rgba(238,238,238,1) 0%, rgba(204,204,204,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(238,238,238,1)), color-stop(100%,rgba(204,204,204,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* IE10+ */
    background: linear-gradient(to bottom, rgba(238,238,238,1) 0%,rgba(204,204,204,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 ); /* IE6-9 */
    border-radius: 10px;
    box-shadow: 0 0 1px 1px #ccc;
    overflow: hidden;
    clear: both;
}

#progressBar div {
    height: 100%;
    color: #fff;
    text-align: right;
    line-height: 22px;
    width: 0;
    border-radius: 5px;
background: rgb(154,224,154); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(154,224,154,1) 1%, rgba(119,184,119,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,rgba(154,224,154,1)), color-stop(100%,rgba(119,184,119,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(154,224,154,1) 1%,rgba(119,184,119,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(154,224,154,1) 1%,rgba(119,184,119,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(154,224,154,1) 1%,rgba(119,184,119,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(154,224,154,1) 1%,rgba(119,184,119,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#9ae09a', endColorstr='#77b877',GradientType=0 ); /* IE6-9 */
}

#upgrade {
    font-size: 14px;
    margin: 10px 0;
}
</style>
<?php if ($this->install === 1) echo "<script>install(1);</script>"; ?>