<?php
defined('_JEXEC') or die();
$row = $this->row;
?>
<form action="index.php?option=com_jshopping&controller=attributesgroups" method="post" name="adminForm" id="adminForm">
<?php print $this->tmp_html_start?>
<div class="col100">
<fieldset class="adminform">
<table width="100%" class="admintable">
<?php 
foreach($this->languages as $lang){
    $field = "name_".$lang->language;
?>
   <tr>
     <td class="key" style="width:250px;">
        <?php echo _JSHOP_TITLE; ?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
     </td>
     <td>
        <input type="text" class="inputbox" id="<?php print $field?>" name="<?php print $field?>" value="<?php echo $row->$field;?>" />
     </td>
   </tr>
<?php }?>
<?php $pkey = "etemplatevar";if ($this->$pkey){print $this->$pkey;}?>    
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $row->id?>" />
<?php print $this->tmp_html_end?>
</form>