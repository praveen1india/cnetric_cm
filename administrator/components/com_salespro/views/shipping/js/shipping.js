/* /// SHIPPING RULES /// */
jQuery(document).ready(function() {
    jQuery('#createRule').click(function() {
        jQuery('#createRuleFields').fadeIn();
    });
});

function cancelRule() {
    jQuery('#createRuleFields').fadeOut();
}

function editRule($id) {
    var rule = jQuery("#spr_shipping_rules_"+$id);
    var array = new Array('r_id','r_price','r_start_weight','r_end_weight','r_start_items','r_end_items','r_start_price','r_end_price','r_height','r_width','r_depth');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(rule.attr(b));
    });
    jQuery('#r_regions option').removeAttr('selected');
    var regions = rule.attr('r_regions');
    if(regions.length > 0) {
        regions = jQuery.parseJSON(regions);
        jQuery.each(regions, function(index, element) {
            jQuery('#r_regions').find('option[value="'+ element +'"]').attr('Selected', 'Selected');
        });
    }
    jQuery('#createRuleFields').fadeIn();
}

function saveRule() {
    var array = new Array('r_id','r_price','r_start_weight','r_end_weight','r_start_items','r_end_items','r_start_price','r_end_price','r_regions','r_height','r_width','r_depth');
    var r_id = jQuery('#r_id').val();
    var r_price = jQuery('#r_price').val();
    var r_start_weight = jQuery('#r_start_weight').val();
    var r_end_weight = jQuery('#r_end_weight').val();
    var r_start_items = jQuery('#r_start_items').val();
    var r_end_items = jQuery('#r_end_items').val();
    var r_start_price = jQuery('#r_start_price').val();
    var r_end_price = jQuery('#r_end_price').val();
    var r_height = jQuery('#r_height').val();
    var r_width = jQuery('#r_width').val();
    var r_depth = jQuery('#r_depth').val();
    var r_regions = JSON.stringify(jQuery('#r_regions').val());
    var r_shipping_id = jQuery('#spr_id').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=shipping_rules',
        data: {id:r_id,shipping_id:r_shipping_id,price:r_price,start_weight:r_start_weight,end_weight:r_end_weight,start_items:r_start_items,end_items:r_end_items,start_price:r_start_price,end_price:r_end_price,regions:r_regions,height:r_height,width:r_width,depth:r_depth},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            if(json.error) {
                alert(json.error);
            } else {
                var j_id = json.id;
                var string = json.string;
                if(r_id > 0) {
                    jQuery('#spr_shipping_rules_'+r_id).before(string).remove();
                }
                else jQuery('#spr_shipping_rules_list').append(string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                cancelRule();
            }
        }
    });
}


function deleteRule($id) {
    var id = $id;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=shipping_rules',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_shipping_rules_'+id).remove();
        }
    });
}