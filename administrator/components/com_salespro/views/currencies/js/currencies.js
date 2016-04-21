/* /// CURRENCIES /// */
function currencyAccept($id) {
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=currAccept&tab=currencies',
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
            jQuery('#currency_accepted_'+$id).removeClass().addClass(accept);
        }
    });
}

function currencyDefault($id) {
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=currDefault&tab=currencies',
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
            jQuery('#currency_default_'+$id).removeClass('spr_icon_starno').addClass('spr_icon_staryes');
        }
    });
}