/* /// /// ITEM LIST RULES /// /// */
function deleteItem($id) {
    var id = $id;
    var check=confirm('Delete this product?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=items',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_items_'+id).remove();
        }
    });
}

/* /// PRODUCT TYPES /// */
jQuery(document).ready(function() {
    jQuery('#createProdType').click(function() {
        var array = new Array('t_id', 't_name', 't_var', 't_del', 't_sm', 't_dl', 't_tc', 't_quantity');
        jQuery.each(array,function(a,b){
            if(b === 't_id') jQuery('#'+b).val(0);
            else jQuery('#'+b).val('');
        });
        showPT();
    });
});

//SHOW EDIT SCREEN
function showPT() {
    jQuery('#ptEdit').fadeIn();
}

//EDIT TYPE
function editPT($id) {
    var pt = jQuery("#spr_prodtypes_"+$id);
    var array = new Array('t_id', 't_name', 't_var', 't_del', 't_sm', 't_dl', 't_tc', 't_quantity');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(pt.attr(b));
    });
    showPT();
}

//CANCEL EDIT
function cancelPT() {
    jQuery('#ptEdit').fadeOut();
}

//SAVE EDIT
function savePT() {
    var array = new Array('t_id', 't_name', 't_var', 't_del', 't_sm', 't_dl', 't_tc', 't_quantity');
    var params = {name:jQuery('#t_name').val(),var:jQuery('#t_var').val(),del:jQuery('#t_del').val(),sm:jQuery('#t_sm').val(),dl:jQuery('#t_dl').val(),tc:jQuery('#t_tc').val(),quantity:jQuery('#t_quantity').val() }
    var t_id = jQuery('#t_id').val();
    var t_name = jQuery('#t_name').val();
    
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=prodTypes',
        data: {id:t_id,name:t_name,params:params},
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
                if(t_id > 0) jQuery('#spr_prodtypes_'+t_id).before(string).remove();
                else jQuery('#spr_prodtypes_list').append(string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                cancelPT();
            }
        }
    });
}

//DELETE TYPE

function deletePT($id) {
    var id = $id;
    var check=confirm('Delete this product type?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=prodTypes',
        data: {id:id},
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
                jQuery('#spr_prodtypes_'+id).remove();
            }
        }
    });
}


/* /// PRODUCT ATTRIBUTES /// */
jQuery(document).ready(function() {
    jQuery('#createAttribute').click(function() {
        jQuery('#a_id').val('');
        jQuery('#a_name').val('');
        jQuery('#a_categories').val('');
        jQuery('#valuesList').html('');
        showAT();
    });
});

//SHOW EDIT SCREEN
function showAT() {
    jQuery('#atEdit').fadeIn();
}

//EDIT ATTRIBUTE
function editAT($id) {
    var at = jQuery("#spr_attributes_"+$id);
    var array = new Array('a_name', 'a_categories', 'a_options');

    jQuery('#a_id').val($id);
    jQuery('#a_name').val(at.attr('a_name'));
    
    var categories = jQuery.parseJSON(at.attr('a_categories'));
    jQuery('#a_categories option').removeAttr('selected');
    jQuery.each(categories,function(a,b) {
        jQuery('#a_categories option[value="'+b+'"]').attr('selected','selected');
    });
    jQuery('#valuesList').html(at.attr('a_values'));
    
    showAT();
}

//CANCEL EDIT
function cancelAT() {
    jQuery('#atEdit').fadeOut();
}

//SAVE EDIT
function saveAT() {
    var array = new Array('a_id', 'a_name', 'a_categories');
    var a_id = jQuery('#a_id').val();
    var a_name = jQuery('#a_name').val();
    var a_categories = JSON.stringify(jQuery('#a_categories').val());

    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=attributes',
        data: {id:a_id,name:a_name,categories:a_categories},
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
                var string = json.string;
                if(a_id > 0) jQuery('#spr_attributes_'+a_id).before(string).remove();
                else jQuery('#spr_attributes_list').append(string);
                cancelAT();
            }
        }
    });
}

//DELETE ATTRIBUTE

function deleteAT($id) {
    var id = $id;
    var check=confirm('Delete this attribute?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=attributes',
        data: {id:id},
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
                jQuery('#spr_attributes_'+id).remove();
            }
        }
    });
}

/* /// ATTRIBUTE VALUES /// */
function editValue($id) {
    var value = jQuery('#att_value_'+$id);
    jQuery('#value_id').val($id);
    jQuery('#attributes_value').val(value.attr('value'));
}
function deleteValue($id) {
    var id = $id;
    var attribute = jQuery('#a_id').val();
    var check=confirm('Delete this value?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=attributesValues',
        data: {id:id,attribute:attribute},
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
                jQuery('#valuesList').html(json.string);
            }
        }
    });
}

function saveValue() {
    var attribute = jQuery('#a_id').val();
    var value_id = jQuery('#value_id').val();
    var value = jQuery('#attributes_value').val();
    var array = new Array('value_id', 'attributes_value');
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=attributesValues',
        data: {id:value_id,attribute_id:attribute,value:value},
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
                jQuery('#valuesList').html(json.string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
            }
        }
    });
}

/* /// /// ITEM EDIT RULES /// /// */

jQuery(document).ready(function() {
    
    jQuery('#spr_items_type').change(function() {

        //SHOW DOWNLOADS AREA IF REQUIRED
        if(jQuery('#spr_items_type option:selected').attr('dl') == '1') {
            jQuery('#dl_tab').fadeIn('fast');
        } else {
            jQuery('#dl_tab').fadeOut('fast');
        }
        
        //SHOW VARIANTS AREA IF REQUIRED
        if(jQuery('#spr_items_type option:selected').attr('var') == '1') {
            jQuery('#var_tab').fadeIn('fast');
            jQuery('#simple_inputs').fadeOut('fast');
        } else {
            jQuery('#var_tab').fadeOut('fast');
            jQuery('#simple_inputs').fadeIn('fast');
        }
        
        //SHOW STOCK FIELDS IF REQUIRED
        if(jQuery('#spr_items_type option:selected').attr('sm') == '1') {
            jQuery('.stock_man').fadeIn('fast');
        } else {
            jQuery('.stock_man').fadeOut('fast');
        }
    });
});

/* /// VIDEO /// */
jQuery(document).ready(function() {
    //ADD SORTABLE
    if(jQuery('#spr_item_videos_list').length > 0) {
        jQuery( "#spr_item_videos_list" ).sortable({
            axis: 'y',
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=item_videos',
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
        jQuery( "#spr_item_videos_list").disableSelection();
    }


    jQuery('#createVideo').click(function() {
        jQuery('#videoDetails').hide();
        jQuery('#createVideoFields').fadeIn();
    });
});


function cancelVideo() {
    jQuery('#createVideoFields').hide();
    jQuery('#videoDetails').fadeIn();
}


function editVideo($id) {
    var video = jQuery("#spr_item_videos_"+$id);
    var array = new Array('v_id', 'v_url', 'v_height', 'v_width');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(video.attr(b));
    });
    jQuery('#videoDetails').hide();
    jQuery('#createVideoFields').fadeIn();
}


function saveVideo() {
    var array = new Array('v_id','v_url','v_height', 'v_width');
    var v_id = jQuery('#v_id').val();
    var v_url = jQuery('#v_url').val();
    var v_height = jQuery('#v_height').val();
    var v_width = jQuery('#v_width').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=item_videos',
        data: {id:v_id,url:v_url,height:v_height,width:v_width},
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
                var string = '<tr id="spr_item_videos_'+j_id+'" v_id="'+j_id+'" v_url="'+v_url+'" v_height="'+v_height+'" v_width="'+v_width+'"><td width="1%" class="nowrap center"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="margin: 0 10px;">&nbsp;</span></td><td>'+v_url+'</td><td width="1%" class="nowrap center">'+v_height+' px</td><td width="1%" class="nowrap center">'+v_width+' px</td><td width="1%" class="nowrap center"><a href="#" onclick="editVideo('+j_id+')" class="spr_icon spr_icon_edit">&nbsp;</a> <a href="#" onclick="deleteVideo('+j_id+')" class="spr_icon spr_icon_delete">&nbsp;</a></td></tr>';
                if(v_id > 0) jQuery('#spr_item_videos_'+v_id).before(string).remove();
                else jQuery('#spr_item_videos_list').append(string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                cancelVideo();
            }
        }
    });
}


function deleteVideo($id) {
    var id = $id;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=item_videos',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_item_videos_'+id).remove();
        }
    });
}

/* /// FAQS /// */
jQuery(document).ready(function() {
    //ADD SORTABLE
    if(jQuery('#spr_item_faqs_list').length > 0) {
        jQuery( "#spr_item_faqs_list" ).sortable({
            axis: 'y',
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true,
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=item_faqs',
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
        jQuery( "#spr_item_faqs_list").disableSelection();
    }


    jQuery('#createFaq').click(function() {
        jQuery('#faqDetails').hide();
        jQuery('#createFaqFields').fadeIn();
    });
});
function cancelFaq() {
    jQuery('#createFaqFields').hide();
    jQuery('#faqDetails').fadeIn();
}


function editFaq($id) {
    var faq = jQuery("#spr_item_faqs_"+$id);
    var array = new Array('f_id', 'f_question', 'f_answer');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(faq.attr(b));
    });
    jQuery('#faqDetails').hide();
    jQuery('#createFaqFields').fadeIn();
}


function saveFaq() {
    var array = new Array('f_id', 'f_question', 'f_answer');
    var f_id = jQuery('#f_id').val();
    var f_question = jQuery('#f_question').val();
    var f_answer = jQuery('#f_answer').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=item_faqs',
        data: {id:f_id,question:f_question,answer:f_answer},
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
                var string = '<tr id="spr_item_faqs_'+j_id+'" f_id="'+j_id+'" f_question="'+f_question+'" f_answer="'+f_answer+'"><td width="1%" class="nowrap center"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="margin: 0 10px;">&nbsp;</span></td><td>'+f_question+'</td><td>'+f_answer+'</td><td width="1%" class="nowrap center"><a href="#" onclick="editFaq('+j_id+')" class="spr_icon spr_icon_edit">&nbsp;</a> <a href="#" onclick="deleteFaq('+j_id+')" class="spr_icon spr_icon_delete">&nbsp;</a></td></tr>';
                if(f_id > 0) jQuery('#spr_item_faqs_'+f_id).before(string).remove();
                else jQuery('#spr_item_faqs_list').append(string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                jQuery('#createFaqFields').hide();
                jQuery('#faqDetails').fadeIn();
            }
        }
    });
}

function deleteFaq($id) {
    var id = $id;
    var check=confirm('Delete this FAQ?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=item_faqs',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_item_faqs_'+id).remove();
        }
    });
}

/* /// DOWNLOADS /// */
jQuery(document).ready(function() {

    //UPLOAD FILES
    if(jQuery('#spr_dlupload').length > 0) {
        jQuery('#spr_dlupload').uploadifive({
            'auto'          :true,
            'removeCompleted':true,
            'fileSizeLimit' : 0,
            'queueID'       : 'dlqueue',
            'buttonText'    : 'Upload files',
            'uploadScript'  : 'index.php?option=com_salespro&task=runAjax&format=raw&action=uploadFiles&tab=item_dls',
            'onUploadComplete': function(file, data) {
                var json = JSON.parse(data);
                if(json.error !== 0) {
                    alert(json.error);
                } else {
                    var string = json.string;
                    jQuery('#spr_item_dls_list').append(string);
                }
            },
            'onFallback'   : function() {
                jQuery('#spr_dlupload').hide();
                jQuery('#spr_dlhtml5').show();
            }
        });
    }
    
    //ADD SORTABLE
    if(jQuery('#spr_item_dls_list').length > 0) {
        jQuery( "#spr_item_dls_list" ).sortable({
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=item_dls',
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
        jQuery( "#spr_item_dls_list").disableSelection();
    }
});

function editDl($id) {
    var dl = jQuery("#spr_item_dls_"+$id);
    var array = new Array('d_id', 'd_name', 'd_days', 'd_status', 'd_times');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(dl.attr(b));
    });
    jQuery('#d_ext').html(dl.attr('d_ext'));
    jQuery('#dlUpload').hide();
    jQuery('#dlEdit').fadeIn();
}

function saveDl() {
    var array = new Array('d_id', 'd_name', 'd_days', 'd_status', 'd_times');
    var d_id = jQuery('#d_id').val();
    var d_name = jQuery('#d_name').val();
    var d_days = jQuery('#d_days').val();
    var d_times = jQuery('#d_times').val();
    var d_status = jQuery('#d_status').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=item_dls',
        data: {id:d_id,name:d_name,days:d_days,status:d_status,times:d_times},
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
                var dl = jQuery("#spr_item_dls_"+j_id);
                jQuery("#spr_item_dls_"+j_id).replaceWith(json.string);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                cancelDl();
            }
        }
    });
}

function dlStatus($id) {
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=status&tab=item_dls',
        data: {id:$id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            if(json.status === '1') var accept='spr_icon spr_icon_yes';
            else var accept = 'spr_icon spr_icon_no';
            jQuery('#dlstatus_'+$id).removeClass().addClass(accept);
        }
    });
}

function cancelDl() {
    jQuery('#dlEdit').hide();
    jQuery('#dlUpload').fadeIn();
}

function deleteDl($id) {
    var id = $id;
    var check=confirm('Delete this download?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=item_dls',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_item_dls_'+id).remove();
        }
    });
}

/* /// IMAGES /// */
jQuery(document).ready(function() {
    
    //UPLOAD IMAGES
    if(jQuery('#spr_upload').length > 0) {
        var uniqhash = jQuery('#spr_hash').val();
        jQuery('#spr_upload').uploadifive({
            'auto'          :true,
            'removeCompleted':true,
            'fileSizeLimit' : 0,
            'queueID'       : 'queue',
            'buttonText'    : 'Upload images',
            'uploadScript'  : 'index.php?option=com_salespro&task=runAjax&format=raw&action=uploadImage&tab=item_images',
            'formData'      : {'hash' : uniqhash},
            'onUploadComplete': function(file, data) {
                var json = JSON.parse(data);
                if(json.error !== 0) {
                    alert(json.error);
                } else {
                    showImg(json.src,json.id);
                }
            },
            'onFallback'   : function() {
                jQuery('#spr_import').hide();
                jQuery('#spr_html5').show();
            }
        });
    }
    
    //ADD SORTABLE
    if(jQuery('#spr_item_images_list').length > 0) {
        jQuery( "#spr_item_images_list" ).sortable({
            stop : function (event,ui) {
                var order = jQuery(this).sortable('serialize');
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=resort&tab=item_images',
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
        jQuery( "#spr_item_images_list").disableSelection();
    }
});

function editImage($id) {
    var image = jQuery("#spr_item_images_"+$id);
    var array = new Array('i_id', 'i_title','i_desc');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(image.attr(b));
    });
    jQuery('#imagesDetails').hide();
    jQuery('#imagesEdit').fadeIn();
}
function saveImage() {
    var array = new Array('i_id', 'i_title','i_desc');
    var i_id = jQuery('#i_id').val();
    var i_title = jQuery('#i_title').val();
    var i_desc = jQuery('#i_desc').val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=item_images',
        data: {id:i_id,title:i_title,desc:i_desc},
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
                var image = jQuery("#spr_item_images_"+j_id);
                jQuery(image).attr('i_id',i_id);
                jQuery(image).attr('i_title',i_title);
                image.attr('i_desc',i_desc);
                jQuery.each(array,function(a,b) {
                    jQuery('#'+b).val('');
                });
                cancelImage();
            }
        }
    });
}

function cancelImage() {
    jQuery('#imagesEdit').hide();
    jQuery('#imagesDetails').fadeIn();
}

function deleteImage($id) {
    var id = $id;
    var check=confirm('Delete this image?');
    if(check !== true) return;
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=item_images',
        data: {id:id},
        type: 'POST',
        dataType: 'json',
        cache: false,
        timeout: 10000,
        error: function() {
            alert('Sorry, the request timed out! Please check you are logged in, and try again');
        },
        success: function(json) {
            jQuery('#spr_item_images_'+id).remove();
        }
    });
}

function showImg($src,$id) {
    var string = '<li class="spr_img_edit" id="spr_item_images_'+$id+'"><span class="ui-icon ui-icon-arrowthick-2-n-s" style="position: absolute; margin: 5px;float: left;">&nbsp;</span><div class="spr_icon_holder"><a class="spr_icon spr_icon_edit" onclick="editImage({$img->id})">&nbsp;</a><a class="spr_icon spr_icon_delete" onclick="deleteImage('+$id+')">&nbsp;</a></div><img src="'+$src+'" /></div>';
    jQuery('#spr_item_images_list').append(string);  
}


/* /// VARIANTS /// */
jQuery(document).ready(function() {
    if(jQuery('#spr_items_category').length) getItemAttributes(1);
    jQuery('#spr_items_category').change(function() {
        getItemAttributes(1);
    });
});
            
function getAttributeFields() {
    var item_id = jQuery('#spr_id').val();
    var catid = jQuery('#spr_items_category').val();
    jQuery.ajax({
    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=getFields&tab=itemAttributesMap',
    data: {catid:catid,item_id:item_id},
    type: 'POST',
    dataType: 'json',
    cache: false,
    timeout: 10000,
    error: function() {
        alert('Sorry, the request timed out! Please check you are logged in, and try again');
    },
    success: function(json) {
        jQuery('#spr_attr_fields').html(json.string);
    }
    });
}
function fetchValues($attr) {
    var input = jQuery('#attr_'+$attr).val();
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=loadValues&tab=attributesValues',
        data: {attribute:$attr,input:input},
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
        } else if ((json.string).length < 1) {
            jQuery('#spr_attr_fields .spr_field_options[attr="'+$attr+'"]').hide();
        } else {
            jQuery('#spr_attr_fields .spr_field_options[attr="'+$attr+'"]').html(json.string).show();
        }
        }
    });
}
            function closeValues() {
                jQuery('#spr_attr_fields .spr_field_options').hide();
            }
            jQuery(document).on('click', '.attribute',function() {
                var attr = jQuery(this).attr('attr');
                closeValues();
                fetchValues(attr);
            });
            jQuery(document).on('keyup', '.attribute',function() {
                var attr = jQuery(this).attr('attr');
                fetchValues(attr);
            });
            jQuery(document).on('click', '#createVariant',function() {
                jQuery('.variant_field').val('');
                jQuery('#variantFields').fadeIn();
                jQuery('#itemAttributes').hide();
            });
            jQuery(document).on('click', '#spr_attr_fields .spr_field_options li', function() {
                var attr = jQuery(this).closest('div').attr('attr');
                jQuery('#attr_'+attr).val(jQuery(this).html());
                closeValues();
            });
            

            function editVariant($id) {
                var variant = jQuery("#variant_"+$id);
                jQuery('.variant_field').val('');
                jQuery.each(variant.get(0).attributes, function(i, attrib){
                    var name = attrib.name;
                    if(jQuery('#'+name).length > 0) jQuery('#'+name).val(attrib.value);
                });
                jQuery('#variantFields').fadeIn();
                jQuery('#itemAttributes').hide();
            }
            
            function cancelVariant() {
                jQuery('.variant_field').val('');
                jQuery('#variantFields').hide();
                jQuery('#itemAttributes').fadeIn();
            }

            function deleteVariant($id) {
                var check=confirm('Delete this variant?');
                if(check !== true) return;
                var id = $id;
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=delete&tab=item_variants',
                    data: {id:id},
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    timeout: 10000,
                    error: function() {
                        alert('Sorry, the request timed out! Please check you are logged in, and try again');
                    },
                    success: function(json) {
                        jQuery('#variant_'+id).remove();
                    }
                });
            }
            
            function varStatus($id) {
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=status&tab=item_variants',
                    data: {id:$id},
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    timeout: 10000,
                    error: function() {
                        alert('Sorry, the request timed out! Please check you are logged in, and try again');
                    },
                    success: function(json) {
                        if(json.status === '1') var accept='spr_icon spr_icon_yes';
                        else var accept = 'spr_icon spr_icon_no';
                        jQuery('#var_status_'+$id).removeClass().addClass(accept);
                        jQuery('#variant_'+$id).attr('var_status',json.status);
                    }
                });
            }
            
            function varOnSale($id) {
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=onsale&tab=item_variants',
                    data: {id:$id},
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    timeout: 10000,
                    error: function() {
                        alert('Sorry, the request timed out! Please check you are logged in, and try again');
                    },
                    success: function(json) {
                        if(json.onsale === '1') var accept='spr_icon spr_icon_yes';
                        else var accept = 'spr_icon spr_icon_no';
                        jQuery('#var_onsale_'+$id).removeClass().addClass(accept);
                        jQuery('#variant_'+$id).attr('var_onsale',json.onsale);
                    }
                });
            }
            
            function saveVariant() {
                var array = new Array('var_id','var_price', 'var_sku', 'var_image_id', 'var_sale', 'var_onsale', 'var_stock', 'var_status');
                var var_id = jQuery('#var_id').val();
                var var_price = jQuery('#var_price').val();
                var var_sku = jQuery('#var_sku').val();
                var var_image_id = jQuery('#var_image_id').val();
                var var_sale = jQuery('#var_sale').val();
                var var_onsale = jQuery('#var_onsale').val();
                var var_stock = jQuery('#var_stock').val();
                var var_status = jQuery('#var_status').val();
                var var_item_id = jQuery('#spr_id').val();
                var category = jQuery('#spr_items_category').val();
                var attributes = {};
                jQuery('.attribute').each(function() {
                    attributes[jQuery(this).attr('attr')] = jQuery(this).val();
                });
                attributes = JSON.stringify(attributes);
                jQuery.ajax({
                    url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=save&tab=item_variants',
                    data: {id:var_id,item_id:var_item_id,price:var_price,sku:var_sku,image_id:var_image_id,sale:var_sale,onsale:var_onsale,stock:var_stock,status:var_status,attributes:attributes,category:category},
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
                            if(var_id > 0) jQuery('#variant_'+var_id).before(json.string).remove();
                            else jQuery('#variantsList').append(json.string);
                            jQuery.each(array,function(a,b) {
                                jQuery('#'+b).val('');
                            });
                            cancelVariant();
                        }
                    }
                });
            }


/* /// POPUP VARIANT IMAGES /// */
function getVariantImages() {
    var item = jQuery('#spr_id').val();
    jQuery('#spr_popup').fadeIn();
    jQuery('#spr_overlay').fadeIn();
    jQuery('#spr_popup_images_list').html('Please upload images in the images tab');
    var string = '';
    jQuery.ajax({
        url: 'index.php?option=com_salespro&task=runAjax&format=raw&action=getImages&tab=item_variants',
        data: {item:item},
        type: 'POST',
        dataType: 'json',
        cache: false,
		timeout: 10000,
        success: function(json) {
            if(json.data.length > 0) jQuery.each(json.data,function(a,b) {
                string += "<div class='spr_popup_image'><label for='option_img_"+b.id+"'><img src='"+b.src+"' /></label><input type='radio'  name='option_img' id='option_img_"+b.id+"' value='"+b.id+"' onclick='saveVariantImage("+b.id+")' /></div>";
            });
            jQuery('#spr_popup_images_list').html(string);
        },
		error: function() {
		  alert('Sorry, the request timed out! Please check you are logged in, and try again');
		}
    });
}


function saveVariantImage($id) {
    jQuery('#var_image_id').val($id);
    closePopup();
}