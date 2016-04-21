jQuery(document).ready(function() {
    jQuery('#file_upload').uploadifive({
        'auto'          :true,
        'removeCompleted':false,
        'fileSizeLimit' : 0,
        'queueID'       : 'queue',
        'queueSizeLimit': 0,
        'uploadLimit'   : 0,
        'buttonText'    : 'Select file',
        'uploadScript'  : 'index.php?option=com_salespro&view=import&format=raw&task=import',
        'onUploadComplete': function(file, data) {
            if(data.substr(0,1) !== '1') {
                alert(data);
            }
            else {
                importVm(0);
            }
        },
        'onFallback'   : function() {
            jQuery('#spr_import_button').hide();
            jQuery('#spr_import_html5').hide();
        }
    });
});

var jdata = '';

function importVm(point) {

    if(point === 0) {
        progress(0);
        jQuery('#upgrade').html('Initialising awesomeness');
        jQuery('.spr_import_step').hide();
        jQuery('#file_upload').fadeOut();
        jQuery('#spr_import_step2').fadeIn();
        point = 'readFile';
    }
    
    jQuery.ajax({
        url: 'index.php?option=com_salespro&view=import&task='+point+'&format=raw',
        type: 'POST',
        dataType: 'json',
        data: {jdata:jdata},
        cache: false,
        success: function(json) {
            if(json.error) {
                alert(json.error);
                jQuery('.spr_import_step').hide();
                jQuery('#spr_import_step1').fadeIn();
                return;
            }
            jQuery('#spr_import_message').html(json.text);
            progress(json.progress);
            if(json.jdata) jdata = json.jdata;
            if(json.point === 1) {
                jQuery('.spr_import_step').hide();
                jQuery('#spr_import_step3').fadeIn();
            } else {
                importVm(json.point);
            }
        }, error:function (x,y,z) {
            alert(z+"\n\nMigration failed!\n\nError details:\nStatus:"+x.status+"\nError response:"+x.responseText+"\n\nIf this error continues, please contact support at php-web-design.com Thank you.");
            jQuery('.spr_import_step').hide();
            jQuery('#spr_import_step1').fadeIn();
        }
    });
}

function progress(percent) {
    var element = jQuery('#progressBar');
	var progressBarWidth = percent * element.width() / 100;
    percent = parseInt(percent);
	element.find('div').stop(true).animate({ width: progressBarWidth }, 500).html(percent + "%&nbsp;complete&nbsp;");
}