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

<h1><?php echo JText::_('SPR_YOURBASKET'); ?></h1>

<form action="" method="post" name="sprBasketForm" id="sprBasketForm">
<input type="hidden" name="cart_item" id="cart_item" value="" />
<input type="hidden" id="cart_quantity" name="cart_quantity" value="" />
</form>
<div class="spr_basket">
<?php
if($this->cart->totals->quantity>0) {
    foreach($this->cart->items as $i) {
        $attributes = '';
        if(count($i->data->attributes)>0) {
            $attributes .= '<ul>';
            foreach($i->data->attributes as $attribute) {
                $attributes .= "<li>{$attribute->name}: {$attribute->value}</li>";
            }
            $attributes .= '</ul>';
        }
        $display_quantity = (($i->data->params->quantity) === '2') ? 'style="display:none !important;"' : '';
        echo "
<div class='spr_basket_item' item='{$i->id}'>
    <div class='spr_basket_item_image'>
        <img src='".salesProImage::_($i->data->image, 120, 90)."' />
    </div>
    <div class='spr_basket_item_actions'>
        <div class='spr_basket_action'>
            <h3 class='spr_basket_item_price' style='text-align:right;font-size:18px;margin:0 0 0 0;'>{$i->f_price}</h3>
        </div>
        <div class='spr_basket_action' {$display_quantity}>
            <input type='text' id='item_quantity_{$i->id}' value='{$i->quantity}' />
            <label>".JText::_('SPR_QUANTITY').": </label>
        </div>
        <div class='spr_basket_action'>
            <div class='update' style='float:right;margin:0 5px;cursor:pointer;' onclick='updateCartItem({$i->id});'>&nbsp;</div>
            <div class='delete' style='float:right;margin:0 5px;cursor:pointer;' onclick='deleteCartItem({$i->id});'>&nbsp;</div>
        </div>
    </div>
    <div class='spr_basket_item_content'>
        <h3 class='spr_basket_item_name'><a href='".sprItems::_directLink($i->data->id,$i->data->name,$i->data->alias,$i->data->category,$i->data->category_name,$i->data->category_alias)."'>{$i->data->name}</a></h3>
        {$attributes}
    </div>
</div>";
    }
    echo '<div class="spr_field" style="max-width:100%; margin-right: 20px; overflow: auto; clear: both;">
        <a class="spr_checkout_submit" href="'.JRoute::_('index.php?option=com_salespro&view=checkout').'" style="margin: 0;">'.JText::_('SPR_CONTINUETOCHECKOUT').'</a>
        </div>';
} else {
    echo "<p>".JText::_('SPR_BASKET_EMPTY')."</p>";
} ?>
</div>

<?php echo sprWidgets::_showWidgets('basket'); ?>

</div>

<script>

//DELETE CART ITEM FUNCTION
function deleteCartItem($id) {
    var r = confirm('<?php echo JText::_('SPR_BASKET_REMOVEITEM'); ?>');
    if(r === true) {
        jQuery('#cart_item').val($id);
        jQuery('#cart_quantity').val(0);
        jQuery('#sprBasketForm').submit();
    }
}
</script>