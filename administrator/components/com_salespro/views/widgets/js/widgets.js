/* /// WIDGETS /// */
jQuery(document).ready(function() {
    //ADD SORTABLE
    if(jQuery('#spr_widgets_list').length > 0) {
        jQuery("#spr_widgets_list").sortable({
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=widgets',
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
        jQuery( "#spr_widgets_list").disableSelection();
    }
});