<?php
	defined('_JEXEC') or die();
	$rows=$this->rows;
	$lists=$this->lists;
	$pageNav=$this->pagination;
	$text_search=$this->text_search;
	$category_id=$this->category_id;
	$manufacturer_id=$this->manufacturer_id;
	$currency=$this->currency;
	$count=count ($rows);
	$i=0;
	$saveOrder=$this->filter_order_Dir=="asc" && $this->filter_order=="ordering";
?>
<form action="index.php?option=com_jshopping&controller=products" method="post" name="adminForm">
<?php print $this->tmp_html_start?>
<table width="100%" style="padding-bottom:5px;">
  <tr>
    <td width="95%" align="right">
        <?php print $this->tmp_html_filter?>
        <?php echo _JSHOP_CATEGORY.": ".$lists['treecategories'];?>&nbsp;&nbsp;&nbsp;
        <?php echo _JSHOP_NAME_MANUFACTURER.": ".$lists['manufacturers'];?>&nbsp;&nbsp;&nbsp;
        <?php if ($this->show_vendor) : ?>
            <?php echo _JSHOP_VENDOR.": ".$lists['vendors'];?>&nbsp;&nbsp;&nbsp;
        <?php endif; ?>
        <?php 
        if ($this->config->admin_show_product_labels){
            echo _JSHOP_LABEL.": ".$lists['labels']."&nbsp;&nbsp;&nbsp;";
        }
        ?>
        <?php echo _JSHOP_SHOW.": ".$lists['publish'];?>&nbsp;&nbsp;&nbsp;
    </td>
    <td>
        <input type="text" name="text_search" value="<?php echo htmlspecialchars($text_search);?>" />
    </td>
    <td>
        <input type="submit" class="button" value="<?php echo _JSHOP_SEARCH;?>" />
    </td>
  </tr>
</table>

<table class="adminlist" >
<thead> 
  <tr>
    <th class="title" width ="10">
      #
    </th>
    <th width="20">
      <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo $count;?>);" />
    </th>
    <th width="93">
        <?php echo JHTML::_('grid.sort', _JSHOP_IMAGE, 'product_name_image', $this->filter_order_Dir, $this->filter_order)?>
    </th>
    <th>
      <?php echo JHTML::_('grid.sort', _JSHOP_TITLE, 'name', $this->filter_order_Dir, $this->filter_order)?>
    </th>
    <?php print $this->tmp_html_col_after_title?>
    <?php if (!$category_id){?>
    <th width="80">
        <?php echo JHTML::_('grid.sort', _JSHOP_CATEGORY, 'category', $this->filter_order_Dir, $this->filter_order)?>
    </th>
    <?php }?>
    <?php if (!$manufacturer_id){?>
    <th width="80">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_MANUFACTURER, 'manufacturer', $this->filter_order_Dir, $this->filter_order)?>
    </th>
    <?php }?>
    <?php if ($this->show_vendor){?>
    <th width="80">
      <?php echo JHTML::_( 'grid.sort', _JSHOP_VENDOR, 'vendor', $this->filter_order_Dir, $this->filter_order)?>
    </th>
    <?php }?>
    <th width="80">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_EAN_PRODUCT, 'ean', $this->filter_order_Dir, $this->filter_order);?>
    </th>
    <?php if ($this->config->stock){?>
    <th width="60">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_QUANTITY, 'qty', $this->filter_order_Dir, $this->filter_order);?>
    </th>
    <?php }?>
    <th width="80">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_PRICE, 'price', $this->filter_order_Dir, $this->filter_order);?>
    </th>
    <th width="40">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_HITS, 'hits', $this->filter_order_Dir, $this->filter_order);?>
    </th>
    <th width="60">
        <?php echo JHTML::_( 'grid.sort', _JSHOP_DATE, 'date', $this->filter_order_Dir, $this->filter_order);?>
    </th>
    <?php if ($category_id) {?>
    <th colspan="3" width="40">
      <?php echo JHTML::_( 'grid.sort', _JSHOP_ORDERING, 'ordering', $this->filter_order_Dir, $this->filter_order);?>
      <?php if ($saveOrder){?>
      <a class="saveorder" href="javascript:saveorder(<?php echo ($count-1);?>, 'saveorder')" title="Save Order"></a>
      <?php }?>
    </th>
    <?php }?>
    <th width="40">
      <?php echo _JSHOP_PUBLISH;?>
    </th>
    <th width="40">
        <?php echo _JSHOP_EDIT;?>
    </th>
    <th width="40">
        <?php echo _JSHOP_DELETE;?>
    </th>
    <th width="30">
      <?php echo JHTML::_( 'grid.sort', _JSHOP_ID, 'product_id', $this->filter_order_Dir, $this->filter_order);?>
    </th>
  </tr>
</thead> 
<?php foreach($rows as $row){?>
  <tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $pageNav->getRowOffset($i);?>
   </td>
   <td>
     <input type="checkbox" onclick="isChecked(this.checked)" name="cid[]" id="cb<?php echo $i;?>" value="<?php echo $row->product_id?>" />
   </td>
   <td>
    <?php if ($row->label_id){?>
        <div class="product_label">
            <?php if ($row->_label_image){?>
                <img src="<?php print $row->_label_image?>" width="25" alt="" />
            <?php }else{?>
                <span class="label_name"><?php print $row->_label_name;?></span>
            <?php }?>
        </div>
    <?php }?>
    <?php if ($row->image){?>
        <a href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php print $row->product_id?>">
            <img src="<?php print $this->config->image_product_live_path."/".$row->image?>" width="90" border="0" />
        </a>
    <?php }?>
   </td>
   <td>
     <b><a href="index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php print $row->product_id?>"><?php echo $row->name;?></a></b>
     <br/><?php echo $row->short_description;?>
   </td>
   <?php print $row->tmp_html_col_after_title?>
   <?php if (!$category_id){?>
   <td>
      <?php echo $row->namescats;?>
   </td>
   <?php }?>
   <?php if (!$manufacturer_id){?>
   <td>
      <?php echo $row->man_name;?>
   </td>
   <?php }?>
   <?php if ($this->show_vendor){?>
   <td>
        <?php echo $row->vendor_name;?>
   </td>
   <?php }?>
   <td>
    <?php echo $row->ean?>
   </td>
   <?php if ($this->config->stock){?>
   <td>
    <?php if ($row->unlimited){
        print _JSHOP_UNLIMITED;
    }else{
        echo $row->qty;
    }
    ?>
   </td>
   <?php }?>
   <td>
    <?php echo formatprice($row->product_price, sprintCurrency($row->currency_id));?>
   </td>
   <td>
    <?php echo $row->hits;?>
   </td>
   <td>
    <?php echo formatdate($row->product_date_added, 1);?>
   </td>
   <?php if ($category_id) {?>
   <td align="right" width="20">
    <?php
      if ($i!=0 && $saveOrder) echo '<a href="index.php?option=com_jshopping&controller=products&task=order&product_id='.$row->product_id.'&category_id='.$category_id.'&order=up&number='.$row->product_ordering.'"><img alt="' . _JSHOP_UP . '" src="components/com_jshopping/images/uparrow.png"/></a>';
    ?>
   </td>
   <td align="left" width="20">
      <?php
        if ($i!=($count-1) && $saveOrder) echo '<a href="index.php?option=com_jshopping&controller=products&task=order&product_id='.$row->product_id.'&category_id='.$category_id.'&order=down&number='.$row->product_ordering.'"><img alt="' . _JSHOP_DOWN . '" src="components/com_jshopping/images/downarrow.png"/></a>';
      ?>
   </td>
   <td align="center" width="10">
    <input type="text" name="order[]" id="ord<?php echo $row->product_id;?>"  size="5" value="<?php echo $row->product_ordering; ?>" <?php if (!$saveOrder) echo 'disabled'?> class="text_area" style="text-align: center" />
   </td>
   <?php }?>
   <td align="center">
     <?php
       echo $published=($row->product_publish) ? ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb'.$i. '\', \'unpublish\')"><img title="' . _JSHOP_PUBLISH . '" alt="" src="components/com_jshopping/images/tick.png"></a>') : ('<a href="javascript:void(0)" onclick="return listItemTask(\'cb' . $i . '\', \'publish\')"><img title="'._JSHOP_UNPUBLISH.'" alt="" src="components/com_jshopping/images/publish_x.png"></a>');
     ?>
   </td>
   <td align="center">
    <a href='index.php?option=com_jshopping&controller=products&task=edit&product_id=<?php print $row->product_id?>'><img src='components/com_jshopping/images/icon-16-edit.png'></a>
   </td>
   <td align="center">
    <a href='index.php?option=com_jshopping&controller=products&task=remove&cid[]=<?php print $row->product_id?>' onclick="return confirm('<?php print _JSHOP_DELETE?>')"><img src='components/com_jshopping/images/publish_r.png'></a>
   </td>
   <td align="center">
     <?php echo $row->product_id; ?>
   </td>
  </tr>
<?php
$i++;
}
?>
<tfoot>
<tr>
	<?php print $this->tmp_html_col_before_td_foot?>
    <td colspan="18"><?php echo $pageNav->getListFooter();?></td>
	<?php print $this->tmp_html_col_after_td_foot?>
</tr>
</tfoot>
</table>
<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end?>
</form>