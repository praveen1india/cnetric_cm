<?php defined('_JEXEC') or die(); ?>
<table class = "jshop" id = "jshop_menu_order">
  <tr>
    <?php foreach($this->steps as $k=>$step){?>
      <td class = "jshop_order_step <?php print $this->cssclass[$k]?>">
        <?php print $step;?>
      </td>
    <?php }?>
  </tr>
</table>