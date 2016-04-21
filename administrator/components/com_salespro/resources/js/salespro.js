jQuery(document).ready(function() {
    
    /*/// SET DATE INPUT FIELDS /// */
    jQuery(".spr_date").datepicker({dateFormat:"yy-mm-dd"});
    
    /* /// CHECK AJAX /// */
    if(jQuery('#ajax-check-img').length > 0) {
        jQuery.ajax({
            url: 'index.php?option=com_salespro&task=&task=checkAjax&format=raw',
            dataType: 'json',
            cache: false,
            success: function(json) {
                if(json.error) {
                    alert(json.error);
                    return;
                }
                if(json.data == '1') {
                    jQuery('#ajax-check-img').removeClass().addClass('status-1');
                    jQuery('#ajax-check-text').html('AJAX enabled');
                } else {
                    jQuery('#ajax-check-img').removeClass().addClass('status-0');
                    jQuery('#ajax-check-text').html('AJAX not enabled');
                }
            },
            error: function() {
                jQuery('#ajax-check-img').removeClass().addClass('status-0');
                jQuery('#ajax-check-text').html('AJAX not enabled');
            }
        });
    }
    
    /* /// START TABS /// */
    jQuery( ".spr_tabs" ).tabs();
    
    /* /// CLOSE POPUP /// */
    jQuery('#spr_popup_close').click(function() {
        closePopup();
    });
});

/* /// SWITCH TAB FUNCTION /// */
function switchTab($tab) {
    var index = jQuery('.spr_tabs a[href="#'+$tab+'"]').parent().index();
    jQuery( ".spr_tabs" ).tabs({ active: index });
}

/* /// CLOSE POPUP FUNCTION /// */

function closePopup() {
    jQuery('#spr_popup').fadeOut();
    jQuery('#spr_overlay').fadeOut();
}

/* /// GENERAL STATUS UPDATE FUNCTION /// */
function status($id) {
    var tab = jQuery('#spr_table').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=status&tab='+tab,
        data: {id:$id},
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
                if(json.status === '1') var accept='spr_icon spr_icon_yes';
                else var accept = 'spr_icon spr_icon_no';
                jQuery('#status_'+$id).removeClass().addClass(accept);
            }
        }
    });
}

/* /// GENERAL ACTIVATION UPDATE FUNCTION /// */
function activated($id) {
    var tab = jQuery('#spr_table').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=activated&tab='+tab,
        data: {id:$id},
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
                if(json.activated === '1') var accept='spr_icon spr_icon_yes';
                else var accept = 'spr_icon spr_icon_no';
                jQuery('#activated_'+$id).removeClass().addClass(accept);
            }
        }
    });
}

/* /// GENERAL FEATURED UPDATE FUNCTION /// */
function featured($id) {
    var tab = jQuery('#spr_table').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=featured&tab='+tab,
        data: {id:$id},
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
                if(json.featured === '1') var accept='spr_icon spr_icon_yes';
                else var accept = 'spr_icon spr_icon_no';
                jQuery('#featured_'+$id).removeClass().addClass(accept);
            }
        }
    });
}

/*/// GENERAL DEFAULT UPDATE FUNCTION /// */
function setDefault($id) {
    var tab = jQuery('#spr_table').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=setDefault&tab='+tab,
        data: {id:$id},
        type: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            if(json.error) {
                alert(json.error);
            } else {
                jQuery('.spr_icon_staryes').removeClass('spr_icon_staryes').addClass('spr_icon_starno');
                jQuery('#default_'+$id).removeClass('spr_icon_starno').addClass('spr_icon_staryes');
            }
        }
    });
}

/* /// GENERAL EDIT FUNCTION /// */
function edit($id) {
    var id=$id;
    jQuery('#spr_id').val(id);
    jQuery('#spr_task').val('edit');
    jQuery('#adminForm').submit();
}

/* /// /// GENERAL COPY FUNCTION /// /// */
function copy($id) {
    var id=$id;
    jQuery('#spr_id').val(id);
    jQuery('#spr_task').val('copy');
    jQuery('#adminForm').submit();
}

/* /// GENERAL DELETE FUNCTION /// */
function del($id) {
    var id=$id;
    var check=confirm('Delete this?');
    if(check !== true) return;
    jQuery('#spr_id').val(id);
    jQuery('#spr_task').val('delete');
    jQuery('#adminForm').submit();
}

/* /// GENERAL SORT FUNCTION /// */
function sort($field) {
    var field = jQuery('#spr_sort').val();
    jQuery('#spr_sort').val($field);
    if($field == field) {
        var desc = jQuery('#spr_dir').val();
        if(desc == 'ASC') jQuery('#spr_dir').val('DESC');
        else jQuery('#spr_dir').val('ASC');
    }
    jQuery('#adminForm').submit();
}

/* /// GENERAL GO TO PAGE FUNCTION /// */
function page($page) {
    jQuery('#spr_page').val($page);
    jQuery('#adminForm').submit();
}

/* /// GENERAL RESULTS LIMIT FUNCTION /// */
function limit($limit) {
    jQuery('#spr_limit').val($limit);
    jQuery('#adminForm').submit();
}

/* /// SEARCH FORM UP/DOWN FUNCTION /// */
function upDown(obj) {
    var myclass = jQuery(obj).attr('class');
    if(myclass == 'spr_icon spr_icon_plus') {
        jQuery('#spr_search_box').hide().addClass('spr_search_box_open').fadeIn();
        jQuery(obj).removeClass().hide().addClass('spr_icon spr_icon_minus').fadeIn();
    } else {
        jQuery('#spr_search_box').hide().removeClass('spr_search_box_open').fadeIn();
        jQuery(obj).removeClass().hide().addClass('spr_icon spr_icon_plus').fadeIn();
    }
}

/* /// SHOW / HIDE MENU PANEL /// */
function showPanel() {
    var margin = parseInt(jQuery('#sidebar').css('marginLeft'));
    if(margin === 0) {
        jQuery("#sidebar").animate({
            marginLeft: "-219px",
        }, 500);
        jQuery("#spr_content").animate({
            marginLeft: "1px",
        }, 500);
    } else {
        jQuery("#sidebar").animate({
            marginLeft: "0",
        }, 500);
        jQuery("#spr_content").animate({
            marginLeft: "220px",
        }, 500);
    }
}