jQuery(document).ready(function() {

    //ITEM TABS
    if(jQuery('.spr_tabs').length > 0) {
        var rel = jQuery('.spr_tabs li').first().attr('rel');
        selTab(rel);
    }
    jQuery('.spr_tabs li').click(function() {
        jQuery('.spr_tabs li').removeClass('spr_active_tab');
        jQuery(this).addClass('spr_active_tab');
        var rel = jQuery(this).attr('rel');
        selTab(rel);
    });
    
    //ITEM FAQs
    jQuery('p.spr_faq_question').click(function() {
        jQuery('.spr_faq_answer').slideUp();
        var rel = jQuery(this).attr('rel');
        jQuery('#'+rel).slideDown();
    });
    
    //CHECKOUT SUBMIT
    jQuery('.spr_checkout_submit').click(function() {
        jQuery('#sprCheckoutForm').submit();
    });
});

//ITEM TAB CLICK
function selTab($rel) {
    var rel = $rel;
    jQuery('.spr_tab').hide();
    if(jQuery('.'+rel).length > 0) jQuery('.'+rel).addClass('spr_active_tab').show();
}

//SHOW STATES FUNCTION WHEN COUNTRY IS SELECTED
function showStates($area,$region) {
    if($region === 'unset') {
        $region = jQuery('#spr_users_'+$area+'_country').val();
    }
    var stateid = '';
    var statename = '';
    var stateoptions = '<option value="0">N/A</option>';
    var selected = 'selected="selected"';
    if(typeof states[$region] != 'undefined') {
        state = states[$region];
        if(state.length > 0) {
            stateoptions = '';
            jQuery.each(state,function(a,b) {
                jQuery.each(b,function(c,d) {
                    if(c === 0) stateid = d;
                    else if(c === 1) statename = d;
                });
                stateoptions += '<option value="'+stateid+'"';
                if(($area === 'bill' && stateid == billstate) || ($area === 'del' && stateid == delstate)) stateoptions += selected;
                stateoptions +='>'+statename+'</option>';
            });
        }
    }
    jQuery('#spr_users_'+$area+'_state').html(stateoptions);
}

//UPDATE CART ITEM FUNCTION
function updateCartItem($id) {
    jQuery('#cart_item').val($id);
    jQuery('#cart_quantity').val(jQuery('#item_quantity_'+$id).val());
    jQuery('#sprBasketForm').submit();
}

//UPDATE CART PAYMENT AND SHIPPING FEES - ADD TO TOTAL
function updatePaymentShipping() {
    var shipfee = 0;
    var payfee = 0;
    if(jQuery('#shipping_method option:selected').length > 0) {
        var shipopt = jQuery('#shipping_method option:selected');
        shipfee = parseFloat(shipopt.attr('price'));
        jQuery('#shipping_info').html(shipopt.attr('info'));
    }
    if(jQuery('#payment_option option:selected').length > 0) {
        var payopt = jQuery('#payment_option option:selected')
        payfee = parseFloat(payopt.attr('price'));
        jQuery('#payment_info').html(payopt.attr('info'));
    }
    var cart = parseFloat(jQuery('#spr_total').attr('price'));
    var total = parseFloat(shipfee+payfee+cart);
    total = localiseCurrency(total);
    jQuery('#spr_total').html(total);
}