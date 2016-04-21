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
    <h1 style="float: right; margin-top: 30px;"><?php echo JText::_('SPR_CAT_PRODUCTCATS'); ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div>

<fieldset class="spr_fieldset">

<table class="spr_table" id="categoryList">
<thead>
<tr>
<th align="left"><a href="#" onclick="sort('name');"><?php echo JText::_('SPR_CAT_NAME'); ?></a></th>
<th width="1%" class="nowrap center"><a href="#" onclick="sort('status');"><?php echo JText::_('SPR_CAT_STATUS'); ?></a></th>
</tr>
</thead>
<tbody>
<?php


    function showCat($r,$level=0) {
        $name = '';
        if($level === 0) {
            $name = $r->name;
        } else {
            for($i=0;$i<$level;$i++) $name .= '&mdash;';
            $name .= ' '.$r->name;
        }
        $ayes = ($r->status === '1') ? 'yes':'no';
        $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$r->id}' onclick='status({$r->id});' style='margin:0 auto;'>&nbsp;</a>";
        $ret = "<tr><td><a href='#' onclick='if(window.parent) window.parent.selectItem(\"".(int)$r->id."\",\"".addSlashes($r->name)."\")'>{$name}</a></td>
        <td class='nowrap center' width='1%'>{$status}</td></tr>";
        $level++;
        if(count($r->children)>0) foreach($r->children as $c) $ret .= showCat($c,$level);
        return $ret;
    }

    if(count($this->categories)>0) 
        foreach($this->categories as $c) 
            echo showCat($c);
?>
</tbody>
</table>
</fieldset>

<?php echo $this->class->pageControls(); ?>

</div>

<input type="hidden" name="spr_table" id="spr_table" value="categories" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="categories" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>