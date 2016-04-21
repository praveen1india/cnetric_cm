function install(point) {
    
    if(point === 0) {
        if(!confirm("WARNING: this wizard will completely reset your SalesPro system, and will replace any categories, products, regions, taxes and currencies that you have set up. Do you want to continue?")) return;
        progress(0);
        jQuery('#upgrade').html('Downloading sample data').fadeIn();
        jQuery('#progressBar').fadeIn();
        jQuery('#upgrade').removeClass('complete').addClass('ajax');
        point = 'getZip';
        jQuery('#start_install').hide(function() {
            jQuery('#install_progress').fadeIn();
        });
    }
    
    if(point === 1) {
        progress(0);
        jQuery('#install_progress').fadeIn();
        jQuery('#upgrade').html('Downloading sample data').fadeIn();
        jQuery('#progressBar').fadeIn();
        jQuery('#upgrade').removeClass('complete').addClass('ajax');
        point = 'getZip';
        
    }
    
    jQuery.ajax({
        url: 'index.php?option=com_salespro&view=dashboard&layout=wizard&do='+point,
        type: 'POST',
        dataType: 'json',
        cache: false,
        success: function(json) {
            if(json.error) {
                alert(json.error);
                jQuery('#install_progress').hide();
                return;
            }
            jQuery('#upgrade').html(json.text);
            progress(json.progress);
            if(json.point === 1) {
                jQuery('#upgrade').removeClass('ajax').addClass('complete');
                jQuery('#upgradeComplete').fadeIn();
            } else {
                install(json.point);
            }
        }, error:function (x,y,z) {
            alert(z+"\n\Sample install failed!\n\nError details:\nStatus:"+x.status+"\nError response:"+x.responseText+"\n\nIf this error continues, please contact support. Thank you.");
            jQuery('#install').hide();
        }
    });
}

function progress(percent) {
    var element = jQuery('#progressBar');
	var progressBarWidth = percent * element.width() / 100;
    percent = parseInt(percent);
	element.find('div').stop(true).animate({ width: progressBarWidth }, 500).html(percent + "%&nbsp;complete&nbsp;");
}

function drawChart() {
    if(plot1) plot1.destroy();
    jQuery.jqplot.config.enablePlugins = true;
    var ajaxDataRenderer = function(url, plot, options) {
        var ret = null;
        var range = jQuery('#chart_range').val();
        jQuery.ajax({
            async: false,
            data: {"range": range},
            url: url,
            dataType:"json",
            success: function(json) {
                ret = json.chart;
                jQuery('#spr_dash_grandtotal').html(json.grandtotal);
                jQuery('#spr_dash_quantity').html(json.quantity);
                jQuery('#spr_dash_users').html(json.users);
            }
        });
        return ret;
    }
    plot1 = jQuery.jqplot('chart1', jsonurl,{
        dataRenderer: ajaxDataRenderer,
            animate: true,
            animateReplot: true,
            showTicks: false,
            showMarkers: false,
            seriesDefaults: {
                showMarker: false,
                shadow: false,
                markerOptions: {
                    shadow: false,
                    style: 'circle',
                }
            },
            series:[
                {
                    color: '#f39200',
                    pointLabels: {
                        show: false
                    },
                    yaxis: 'yaxis',
                    rendererOptions: {
                        animation: {
                            speed: 1500
                        }
                    }
                },
            ],
            grid: {
                backgroundColor: 'white',
                borderWidth: 1,
                gridLineColor: '#f5f5f5',
                borderColor: '#ddd',
                shadow: false,
            },
            axesDefaults: {
                pad: 1
            },
            axes: {
                xaxis: {
                    renderer:jQuery.jqplot.DateAxisRenderer,
                    tickOptions:{
                        formatString:'%b&nbsp;%#d'
                    },
                },
                yaxis: {
                    tickOptions: {
                        formatString: symbol+"%d"
                    },
                    rendererOptions: {
                        alignTicks: true,
                        forceTickAt0: false
                    },
                },
            },
            highlighter: {
                show: true,
                sizeAdjust: 7.5
            },

    });
}