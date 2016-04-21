<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_DASH_HEADING'), 'salespro');

$mycurrency = sprCurrencies::_getDefault()->symbol;
?>
<script class="code" type="text/javascript">
var plot1;
var jsonurl = 'index.php?option=com_salespro&view=dashboard&task=getChart';
var symbol = '<?php echo $mycurrency; ?>';
jQuery(document).ready(function() {
    drawChart();
});
</script>
<script type="text/javascript" src="components/com_salespro/lib/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="components/com_salespro/lib/jqplot/plugins/jqplot.json2.min.js"></script>
<script type="text/javascript" src="components/com_salespro/lib/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="components/com_salespro/lib/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="components/com_salespro/lib/jqplot/plugins/jqplot.cursor.min.js"></script>

<?php if($this->welcome === '1') { ?>
<div id="spr_overlay" style="display: inline;"></div>

<div id="spr_popup" style="display: inline;">
<div id="spr_popup_close" class="spr_icon spr_icon_delete">&nbsp;</div>
<div id="spr_popup_header"><img src="components/com_salespro/resources/images/salespro.png" />
</div>
<div id="spr_popup_content">
    <h2><?php echo JText::_('SPR_DASH_WELCOME'); ?></h2>
    <p><?php echo JText::_('SPR_DASH_ABOUT1'); ?></p>
    <p><?php echo JText::_('SPR_DASH_ABOUT2'); ?><img src="components/com_salespro/resources/images/question-mark-icon.png" style='height: 20px; width: 20px; margin: 0 10px;' /></a></p>
    <p><?php echo JText::_('SPR_DASH_ABOUT3'); ?>: <a href='http://www.sales-pro.co.uk/index.php?option=com_kunena&view=category&layout=list&catid=0' target="_blank">http://www.sales-pro.co.uk</a></p>
</div>

<div style="text-align: center;">
<a href="index.php?option=com_salespro&view=dashboard&layout=wizard">
<div class="spr_dash_box_container" style="width: 33%;">
    <div class="spr_dash_box wizard">
        <span><?php echo JText::_('SPR_DASH_BTN_WIZ'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=import">
<div class="spr_dash_box_container" style="width: 33%;">
    <div class="spr_dash_box import">
        <span><?php echo JText::_('SPR_DASH_BTN_IMPORT'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=config">
<div class="spr_dash_box_container" style="width: 33%;">
    <div class="spr_dash_box config" style="margin-right:  0;">
        <span><?php echo JText::_('SPR_DASH_BTN_CONFIG'); ?></span>
    </div>
</div>
</a>

<br style="clear: both;" />
<p style="text-align:center;"><?php echo JText::_('SPR_DASH_NEW'); ?></p>
</div>
</div>

<?php } ?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_DASH_DASHBOARD'); ?></h1>
</div>

<div id="spr_main">

<div id="spr_firstglance">

<div id="spr_firstglance_right">
    <link class="include" rel="stylesheet" type="text/css" href="components/com_salespro/lib/jqplot/jquery.jqplot.min.css" />
    <div id="chart1" style="margin-top:0; margin:0; width:100%; height:350px;"></div>
</div>
<div id="spr_firstglance_left">

<div class="spr_firstglance_select"><select id="chart_range" style="width:  100%;" onchange="drawChart();"><option value='thisweek'><?php echo JText::_('SPR_DASH_SALESTW'); ?></option><option value='lastweek'><?php echo JText::_('SPR_DASH_SALESLW'); ?></option><option value='thismonth'><?php echo JText::_('SPR_DASH_SALESTM'); ?></option><option value='lastmonth'><?php echo JText::_('SPR_DASH_SALESLM'); ?></option><option value='thisyear'><?php echo JText::_('SPR_DASH_SALESTY'); ?></option><option value='lastyear'><?php echo JText::_('SPR_DASH_SALESLY'); ?></option></select>
</div>

<div class="spr_firstglance_box">
<h2 id="spr_dash_quantity">0</h2>
<p><?php echo JText::_('SPR_DASH_ITEMSSOLD'); ?></p>
</div>

<div class="spr_firstglance_box">
<h2 id="spr_dash_grandtotal">0</h2>
<p><?php echo JText::_('SPR_DASH_TOTALSALES'); ?></p>
</div>

<div class="spr_firstglance_box">
<h2 id="spr_dash_users">0</h2>
<p><?php echo JText::_('SPR_DASH_NEWCLIENTS'); ?></p>
</div>

</div>
</div>


<div class="spr_dashboard">

<a href="index.php?option=com_salespro&view=dashboard&layout=wizard">
<div class="spr_dash_box_container">
    <div class="spr_dash_box wizard">
        <span><?php echo JText::_('SPR_DASH_BTN_WIZ'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=import">
<div class="spr_dash_box_container">
    <div class="spr_dash_box import">
        <span><?php echo JText::_('SPR_DASH_BTN_IMPORT'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=config">
<div class="spr_dash_box_container">
    <div class="spr_dash_box config">
        <span><?php echo JText::_('SPR_DASH_BTN_CONFIG'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=items">
<div class="spr_dash_box_container">
    <div class="spr_dash_box products">
        <span><?php echo JText::_('SPR_DASH_BTN_PRODUCTS'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=backups">
<div class="spr_dash_box_container">
    <div class="spr_dash_box backups">
        <span><?php echo JText::_('SPR_DASH_BTN_BACKUPS'); ?></span>
    </div>
</div>
</a>

<a href="index.php?option=com_salespro&view=dashboard&layout=update">
<div class="spr_dash_box_container">
    <div class="spr_dash_box update">
        <span><?php echo JText::_('SPR_DASH_BTN_UPDATES'); ?></span>
    </div>
</div>
</a>

<a target="_blank" href="components/com_salespro/salespro.pdf">
<div class="spr_dash_box_container">
    <div class="spr_dash_box userguide">
        <span><?php echo JText::_('SPR_DASH_BTN_USERGUIDE'); ?></span>
    </div>
</div>
</a>

<a href="http://www.sales-pro.co.uk/index.php?option=com_kunena&view=category&layout=list&catid=0" target="_blank">
<div class="spr_dash_box_container">
    <div class="spr_dash_box help">
        <span><?php echo JText::_('SPR_DASH_BTN_PROBLEM'); ?></span>
    </div>
</div>
</a>

</div>

</div>
</div>
</div>





















<style>
.spr_firstglance_box {
    background-color: #a9db80;
    padding: 23px 0;
    border: 0 solid #ccc;
    width: 100%;
    float: right;
    margin: 0 0 10px 0;
    overflow: hidden;
}
#spr_firstglance {
    overflow: auto;
    clear: both;
    margin: 0 0 20px 0;
}

#spr_firstglance_right {
    width: 73%;
    float: left;
}

#spr_firstglance_left {
    width: 25%;
    float: right;
}

#spr_firstglance p {
    margin: 0 20px;
    font-family: Arial, sans-serif;
    font-size: 12px;
    font-weight: normal;
    line-height: normal;
    color: #666;
}

#spr_firstglance h2 {
    margin: 0 20px;
    font-family: Arial, sans-serif;
    font-size: 24px;
    font-weight: normal;
    line-height: normal;
    color: #f39200;
}

#spr_firstglance select {
    width: 100%;
    margin: 0 0 10px 0;
    padding: 0;
    font-family: Arial, sans-serif;
    font-size: 13px;
    line-height: 18px;
}

.spr_dashboard {
    overflow: hidden;
    margin-right: -15px;
    text-align: center;
}
.spr_dash_box_container {
    width: 25%;
    min-width: 165px;
    float: left;
    padding: 0;
    margin: 0;
}


.spr_dash_box {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 120px 10px 10px 10px;
    height: 20px;
    min-width: 140px;
    background: url('components/com_salespro/resources/images/wizard-sm.png') 50% 40% no-repeat;
    background-color: #fff;
    cursor: pointer;
    margin: 0 15px 10px 0;
    text-align: center;
    position: relative;
}

.config {
    background-image:  url('components/com_salespro/resources/images/config-sm.png') !important;
}

.backups {
    background-image:  url('components/com_salespro/resources/images/backups-sm.png') !important;
}

.update {
    background-image:  url('components/com_salespro/resources/images/update-sm.png') !important;
}

.import {
    background-image:  url('components/com_salespro/resources/images/vm-sm.png') !important;
}

.help {
    background-image:  url('components/com_salespro/resources/images/help-sm.png') !important;
}

.products {
    background-image:  url('components/com_salespro/resources/images/products-sm.png') !important;
}

.userguide {
    background-image:  url('components/com_salespro/resources/images/question-mark-icon.png') !important;
}

.spr_dash_box:hover {
   background-color: #a9db80;
   transition: background-color .25s ease-in-out;
}
.spr_dash_box span {
    font-size:13px;
    font-weight: normal;
    font-family: Arial, sans-serif;
    line-height: 20px;
}
.newfeat {
    font-family: Arial, sans-serif;
    font-size: 12px;
    font-weight: normal;
    line-height: normal;
    position: absolute;
    top: 0;
    right: 0;
    border: 1px solid #f00;
    background: #c00;
    color: #f5f5f5;
    padding: 5px 10px;
}
</style>