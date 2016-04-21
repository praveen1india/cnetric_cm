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

<h3><?php echo JText::_('SPR_DLS_WELCOME'); ?></h3>

<?php foreach($this->dls as $item) {
    echo "<div class='spr_dl_link'><div class='spr_dl_link_name'>{$item['name']}</div><table class='spr_table'>";
    foreach($item['dls'] as $dl) {
        $expiry = ((int)$dl['expiry'] > 0) ? date('Y-m-j', $dl['expiry']) : JText::_('SPR_DLS_NEVER');
        $expired = ($dl['link'] === '') ? JText::_('SPR_DLS_EXPIRED') : JText::_('SPR_DLS_DLHERE');
        $remaining = JText::_('SPR_DLS_REMAINING');
        $times =  $dl['limit'] - $dl['dls'];
        if($dl['limit'] == '0') $times = JText::_('SPR_DLS_UNLIMITED');
        elseif($times <= 0) $times = 0;
        echo "<tr><th>{$dl['name']}.{$dl['ext']}</th><td>".JText::_('SPR_DLS_EXPIRES').": {$expiry}</td><td>{$remaining}: {$times}</td><td width='1%' class='center nowrap'><a href='{$dl['link']}'}'>{$expired}</a></td></tr>";
    }
    echo "</table></div>";
} ?>

</div>

<style>
.spr_dl_link {
    border: 1px solid #eee;
    padding: 10px 20px;
    border-radius: 4px;
    margin: 20px 0;
}

.spr_dl_link_name {
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    margin: 0 0 10px 0;
}

table.spr_table {
    width: 100%;
    padding: 0;
    margin: 0;
    border: 0;
    border-collapse: collapse;
}

table.spr_table tr td, table.spr_table tr th {
    padding: 10px 20px;
    background: #eee;
    text-align: left;
    border-bottom: dotted 1px #ccf;
}

table.spr_table tr:nth-child(2n) td, table.spr_table tr:nth-child(2n) th {
    background: #f5f5f5;
}

</style>