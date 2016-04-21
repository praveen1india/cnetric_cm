/* /// REGIONS /// */
function regionAccept($id) {
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=regAccept&tab=regions',
        data: {id:$id},
        type: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(result) {
            if(result == '1') var accept='spr_icon_yes';
            else var accept = 'spr_icon_no';
            jQuery('#region_accepted_'+$id).removeClass().addClass(accept);
        }
    });
}

function regionDefault($id) {
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=setDefault&tab=regions',
        data: {id:$id},
        type: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(result) {
            jQuery('.spr_icon_staryes').removeClass('spr_icon_staryes').addClass('spr_icon_starno');
            jQuery('#region_default_'+$id).removeClass('spr_icon_starno').addClass('spr_icon_staryes');
        }
    });
}


/* /// STATES SETUP /// */
var stateArray = new Array('id','name','code_2','code_3');

jQuery(document).ready(function() {
    jQuery('#createState').click(function() {
        jQuery('#createStateFields').fadeIn();
    });
});

function cancelState() {
    jQuery.each(stateArray,function(a,b){
        jQuery('#s_'+b).val('');
    });
    jQuery('#createStateFields').fadeOut();
}

function editState($id) {
    var rule = jQuery("#spr_states_"+$id);
    jQuery.each(stateArray,function(a,b){
        jQuery('#s_'+b).val(rule.attr('s_'+b));
    });
    jQuery('#createStateFields').fadeIn();
}

function saveState() {
    var s_id = jQuery('#s_id').val();
    var s_name = jQuery('#s_name').val();
    var s_code_2 = jQuery('#s_code_2').val();
    var s_code_3 = jQuery('#s_code_3').val();
    var s_parent = jQuery('#s_parent').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=regions',
        data: {id:s_id,name:s_name,code_2:s_code_2,code_3:s_code_3,parent:s_parent,status:1},
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
                var string = "<tr id='spr_states_"+j_id+"' s_id='"+j_id+"' s_name='"+s_name+"' s_code_2='"+s_code_2+"' s_code_3='"+s_code_3+"'>";
                string += "<td>"+s_name+"</td>";
                string += "<td>"+s_code_2+"</td>";
                string += "<td>"+s_code_3+"</td>";
                string += "<td width='1%' class='nowrap center'><a href='#' onclick='editState("+j_id+")' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteState("+j_id+")' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
                if(s_id > 0) {
                    jQuery('#spr_states_'+s_id).before(string).remove();
                }
                else jQuery('#spr_states_list').append(string);
                cancelState();
            }
        }
    });
}


function deleteState($id) {
    var id = $id;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=regions',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_states_'+id).remove();
        }
    });
}

function checkState(state) {
    jQuery('.shipping_rules').hide();
    if(jQuery('#shipping_rules_'+rule).length > 0) jQuery('#shipping_rules_'+rule).fadeIn();
}