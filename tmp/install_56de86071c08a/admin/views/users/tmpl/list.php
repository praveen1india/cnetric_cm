<?php
	defined('_JEXEC') or die();
	$rows=$this->rows;
	$pageNav=$this->pageNav;
?>
<form name="adminForm" method="post" action="index.php?option=com_jshopping&controller=users">
<?php print $this->tmp_html_start?>
<table width="100%" style="padding-bottom:5px;">
<tr>
  <td align="right">
    <?php print $this->tmp_html_filter?>
    <input type="text" name="text_search" value="<?php echo htmlspecialchars($this->text_search);?>" />&nbsp;&nbsp;&nbsp;
    <input type="submit" class="button" value="<?php echo _JSHOP_SEARCH;?>" />
  </td>
</tr>
</table>
 
<table class="adminlist" width="100%">
<thead>
<tr>
 <th width="20">
   #
 </th>
 <th width="20">
   <input type="checkbox" onclick="checkAll(<?php echo count($rows)?>)" value="" name="toggle" />
 </th>
 <th align="left">
   <?php echo JHTML::_('grid.sort', _JSHOP_NUMBER, 'number', $this->filter_order_Dir, $this->filter_order)?>
 </th>
 <th align="left">
   <?php echo JHTML::_('grid.sort', _JSHOP_USERNAME, 'u_name', $this->filter_order_Dir, $this->filter_order)?>
 </th>
 <th width="150" align="left">
   <?php echo JHTML::_('grid.sort', _JSHOP_USER_FIRSTNAME, 'f_name', $this->filter_order_Dir, $this->filter_order)?>
 </th>
 <th width="150" align="left">
   <?php echo JHTML::_('grid.sort', _JSHOP_USER_LASTNAME, 'l_name', $this->filter_order_Dir, $this->filter_order)?>
 </th>
 <th>
    <?php echo JHTML::_('grid.sort', _JSHOP_EMAIL, 'U.email', $this->filter_order_Dir, $this->filter_order)?>
 </th>
 <?php print $this->tmp_html_col_after_email?>
 <th>
    <?php echo JHTML::_('grid.sort', _JSHOP_USERGROUP_NAME, 'usergroup_name', $this->filter_order_Dir, $this->filter_order)?>
 </th>
  <th>
    <?php print _JSHOP_ORDERS;?>
 </th>
 <th>
    <?php print _JSHOP_ENABLED;?>
 </th>
 <th width="50">
    <?php echo _JSHOP_EDIT;?>
</th>
 <th width="40">
    <?php echo JHTML::_('grid.sort', _JSHOP_ID, 'user_id', $this->filter_order_Dir, $this->filter_order)?>
</th>
</tr>
</thead> 
<?php $i=0; foreach($rows as $row){?>
<tr class="row<?php echo ($i  %2);?>">
 <td>
   <?php echo $pageNav->getRowOffset($i);?>
 </td>
 <td>
   <input onclick="isChecked(this.checked)" type="checkbox" id="cb<?php echo $i?>" name="cid[]" value="<?php echo $row->user_id?>" />
 </td>
 <td>
   <?php echo $row->number;?>
 </td>
 <td>
   <a href="index.php?option=com_jshopping&controller=users&task=edit&user_id=<?php echo $row->user_id?>">
     <?php echo $row->u_name?>
   </a>
 </td>
 <td>
   <?php echo $row->f_name;?>
 </td>
 <td>
   <?php echo $row->l_name;?>
 </td>
 <td>
   <?php echo $row->email;?>
 </td>
 <?php print $row->tmp_html_col_after_email?>
 <td>
   <?php echo $row->usergroup_name;?>
 </td>
 <td align="center">
   <?php echo "<a href='index.php?option=com_jshopping&controller=orders&client_id=".$row->user_id."' target='_blank'>"._JSHOP_ORDERS."</a>";?>
 </td>
 <td align="center">
   <?php 
       echo (!$row->block) ? ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb'.$i. '\', \'unpublish\')"><img title="'._JSHOP_YES.'" alt="" src="components/com_jshopping/images/tick.png"></a>') : ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb'.$i.'\', \'publish\')"><img title="'._JSHOP_NO.'" alt="" src="components/com_jshopping/images/publish_x.png"></a>');
   ?>
 </td>
 <td align="center">
    <?php print "<a href='index.php?option=com_jshopping&controller=users&task=edit&user_id=".$row->user_id."'><img src='components/com_jshopping/images/icon-16-edit.png'></a>"?>
 </td>
 <td align="center">
    <?php print $row->user_id?>
 </td>
</tr>
<?php 
$i++;
}?>
<tfoot>
<tr>
	<?php print $this->tmp_html_col_before_td_foot?>
    <td colspan="12"><?php echo $pageNav->getListFooter();?></td>
	<?php print $this->tmp_html_col_after_td_foot?>
</tr>
</tfoot>  
</table>
<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end?>
</form>