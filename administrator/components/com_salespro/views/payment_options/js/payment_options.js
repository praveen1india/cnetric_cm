/* /// PAYMENT OPTIONS /// */
jQuery(document).ready(function() {
    //ADD SORTABLE
    if(jQuery('#spr_payment_options_list').length > 0) {
        jQuery("#spr_payment_options_list").sortable({
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=payment_options',
                    data: order,
                    type: 'POST',
                    dataType: 'text',
                    cache: false,
					timeout: 10000,
					error: function() {
				        alert('Sorry, the request timed out! Please check you are logged in, and try again');
					}
                });
            }
        });
        jQuery( "#spr_payment_options_list").disableSelection();
    }
});