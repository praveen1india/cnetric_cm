jQuery(document).ready(function() {
    jQuery('#csv_upload').uploadifive({
        'auto'          :true,
        'removeCompleted':true,
        'fileSizeLimit' : 0,
        'queueID'       : 'csv_queue',
        'queueSizeLimit': 0,
        'uploadLimit'   : 0,
        'buttonText'    : 'Select file',
        'uploadScript'  : 'index.php?option=com_salespro&view=csv&format=raw&task=import',
        'onUploadComplete': function(file, data) {
            if(data.substr(0,1) !== '1') {
                alert(data);
            }
        },
        'onFallback'   : function() {
            jQuery('#csv_import_btn').hide();
            jQuery('#csv_import_html5').hide();
        }
    });
});