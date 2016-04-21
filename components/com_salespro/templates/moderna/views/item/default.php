<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?>

<div id="salespro" class="spr_item_view">

<div id="moderna_popup">     
    <img src="" style="max-width: 100%; max-height: 100%;" />
</div>

<script type="text/javascript" src="<?php echo JURI::base(); ?>/components/com_salespro/templates/moderna/js/jssor.core.js"></script>
<script type="text/javascript" src="<?php echo JURI::base(); ?>/components/com_salespro/templates/moderna/js/jssor.utils.js"></script>
<script type="text/javascript" src="<?php echo JURI::base(); ?>/components/com_salespro/templates/moderna/js/jssor.slider.min.js"></script>
<script>
var showThumbs = '<?php echo (count($this->item->images)>1) ? '2' : '0'; ?>';
var showArrows = '<?php echo (count($this->item->images)>3) ? '2' : '0'; ?>';
var jssor_slider1;
jQuery(document).ready(function ($) {
    var options = {
        $ThumbnailNavigatorOptions: {
            $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
            $ChanceToShow: showThumbs,                      //[Required] 0 Never, 1 Mouse Over, 2 Always
            $Loop: 2,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1
            $SpacingX: 3,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
            $SpacingY: 3,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
            $DisplayPieces: 3,                              //[Optional] Number of pieces to display, default value is 1
            $ParkingPosition: 204,                          //[Optional] The offset position to park thumbnail,
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                $ChanceToShow: showArrows,                  //[Required] 0 Never, 1 Mouse Over, 2 Always
                $AutoCenter: 2,                             //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                $Steps: 1                                   //[Optional] Steps to go for each navigation request, default value is 1
            }
        }
    };
    jssor_slider1 = new $JssorSlider$("moderna_slider", options);
    jQuery(document).ready(function() {
        jQuery('#moderna_slider_slides img').click(function() {
            jQuery('#moderna_popup img').attr('src',jQuery(this).attr('original'));
            jQuery('#moderna_popup').fadeIn();
        });
        jQuery('#moderna_popup').click(function() {
            jQuery(this).fadeOut();
        });
    });
});
</script>

<div id="moderna_breadcrumbs">
<ul class="moderna_breadcrumbs"><?php echo sprCategories::_getBreadCrumbs($this->item->category, 0); ?><li class="moderna_active_breadcrumb"><?php echo $this->item->name; ?></li></ul>
</div>

<div style="display: table; width: 100%;">

<div style="display: table-row;">

<div id="moderna_item_left">
    <div id="moderna_slider">

        <!-- Loading Screen -->
        <div id="moderna_slider_loading" u="loading">
            <div id="moderna_slider_loading_filter"></div>
            <div id="moderna_slider_loading_image"></div>
        </div>

        <!-- Slides Container -->
        <div id="moderna_slider_slides" u="slides">
<?php if(count($this->item->images)>0) foreach($this->item->images as $i) {
    echo "<div class='moderna_slider_slide'>";
    $origImg = $i->src;
    $largeImg = salesProImage::_($i->filename,300,300);
    $thumbImg = salesProImage::_($i->filename,100,100);
    echo "<img u='image' rel='{$i->id}' src='{$largeImg}' original='{$origImg}' width='300' height='auto' alt='{$this->item->name} {$i->title}' style='max-height:auto !important' /><img u='thumb' src='{$thumbImg}' alt='{$this->item->name} {$i->title}' width='100' height='100' /></div>";
} ?>
        </div>
        
        <!-- Thumbnail Navigator Skin Begin -->
        <div u="thumbnavigator" id="moderna_slider_thumbholder">
            <div style="background-color: #f4f3f3; width: 100%; height:100%; border-radius: 10px;"></div>
            <div u="slides" id="moderna_slider_thumbnails">
                <div u="prototype" class="p moderna_slider_thumb">
                    <thumbnailtemplate class="i" style="position:absolute;"></thumbnailtemplate>
                    <div class="o"></div>
                </div>
            </div>
            <span u="arrowleft" class="jssora11l" style="width: 37px; height: 37px; top: 123px; left: 8px;"></span>
            <span u="arrowright" class="jssora11r" style="width: 37px; height: 37px; top: 123px; right: 8px"></span>
        </div>
    </div>
    <?php if(count($this->item->images)>1) echo '<div id="moderna_thumbs_spacing">&nbsp;</div>'; ?>
</div>

<div id="moderna_item_right">
<div id="moderna_item_details" style="width: inherit">

<h1><?php echo $this->item->name; ?></h1>
<?php if($this->item->tagline !== "") echo "<h2>{$this->item->tagline}</h2>"; ?>

<div id="moderna_item_buy_price">

    <form action="" method="post" name="adminForm" id="adminForm">
    <input type="hidden" name="extension" value="com_salespro" />
    <input type="hidden" name="task" value="buy" id="spr_task" />
    <input type="hidden" name="view" value="items" id="spr_view" />
    <input type="hidden" name="spr_id" value="<?php echo $this->item->id; ?>" />
    <?php echo JHTML::_( 'form.token' ); ?>

    <?php if($this->item->prodtype->params->var === '1') { ?>
    <input type="hidden" name="spr_variant" id="spr_variant" value="0" />
    <div id="moderna_item_options">
    <?php foreach($this->item->attributes as $attr) {
        if(!array_key_exists($attr->id, $this->item->valid_attr)) continue;
        echo "<label>{$attr->name}</label>&nbsp;";
        echo "<select name='attr_{$attr->id}' class='moderna_item_attribute'>";
        if(count($attr->values)>0) foreach($attr->values as $a) {
            if(!in_array($a->id, $this->item->valid_attr[$attr->id])) continue;
            echo "<option value='{$a->id}'>{$a->value}</option>";
        }
        echo "</select>";
        echo "<br style='clear:both' />";
    }?>
    <script>
    jQuery(document).ready(function() {
        jQuery('.moderna_item_attribute').change(function() {
            setVariant(0);
        });
        setVariant(0);
    });
    
    var okvars = new Array;
    var myVars = [<?php
        $n = 0;
        foreach($this->item->valid_attrs as $v) {
            if($n++ > 0) echo ',';
            echo "[";
            foreach($v as $w) echo $w.',';
            echo "]";
    } ?>];
    var goodVars = new Array;
        
    function checkAttr($level) {
        okvars = new Array;
        if($level === 0) goodVars = myVars;
        var saveVars = new Array;

        var myattrs = jQuery('.moderna_item_attribute');
        jQuery.each(myattrs,function (a,b) {
            if(a === $level) {
                var selvar = parseInt(this.value);
                jQuery.each(goodVars, function (n,data) {
                    if(selvar === data[$level]) {
                        saveVars[saveVars.length] = data;
                        if(data[$level+1] !== undefined) {
                            var newvar = parseInt(data[$level+1]);
                            if(jQuery.inArray(newvar, okvars) === -1) {
                                okvars[okvars.length] = newvar;
                            }
                        }
                    }
                });
            }
        });
        goodVars = saveVars;
        $level = $level + 1;
        setAttrs($level);
    }
    
    function setAttrs($level) {
        var myattrs = jQuery('.moderna_item_attribute');
        jQuery.each(myattrs,function (a,b) {
            if(a === $level) {
                var options = jQuery('option',this);
                jQuery.each(options,function (c,d) {
                    if(jQuery.inArray(parseInt(jQuery(d).val()),okvars) > -1) {
                        jQuery(d).removeAttr('disabled');
                        jQuery(d).show();
                    } else {
                        jQuery(d).attr('disabled','disabled');
                        jQuery(d).hide();
                    }
                });
                if(jQuery('option:selected',b).attr('disabled') !== undefined) {
                    jQuery('option',b).removeAttr("selected");
                    jQuery('option:enabled',b).first().attr("selected", "selected");
                }
            }
        });
        setVariant($level);
    } 
    
    function setVariant($level) {
    
        var myattrs = jQuery('.moderna_item_attribute').serializeArray();
        var json = {};
        
        var myjson = [
            <?php
            $n = 0;
            foreach($this->item->variant_key as $key=>$data) {
                if($n++ > 0) echo ",";
                echo "[{$key},'{$data}']";
            } ?>
        ];
        var myvars = [
            <?php
            $n = 0;
            foreach($this->item->variants as $var) {
                if($n++ > 0) echo ",";
                echo "[{$var->id},'{$var->image_id}','{$var->f_price}','{$var->f_sale}','{$var->button}']";
            } ?>
        ];             
        jQuery.each(myattrs, function() {
            json[this.name] = this.value;
        });
        json = JSON.stringify(json);
             
        var selvar = 0;
        jQuery(myjson).each(function(a,b) {
            if(b[1] === json) selvar = b[0];
        });
        if(selvar > 0) {
            jQuery(myvars).each(function(a,b) {
                if(b[0] === selvar) {
                    var image_id = b[1];
                    jQuery('#moderna_item_price').html(b[2]);
                    if(b[3].length > 0) {
                        jQuery('#moderna_item_sale').html(b[3]);
                        jQuery('.moderna_item_sale').show();
                    } else {
                        jQuery('.moderna_item_sale').hide();
                    }
                    if(b[4].length > 0) {
                        jQuery('#moderna_item_buy_button').html(b[4]);
                    }
                    var slides = jQuery('.moderna_slider_slide');
                    jQuery(slides).each(function(a,b) {
                        var img = jQuery('img',this).attr('rel');
                        if(img === image_id) jssor_slider1.$PlayTo(a,600);
                    });
                    jQuery('#spr_variant').val(selvar);
                }
            });
        }
        if(myattrs.length > $level) checkAttr($level);
    }
    </script>
    
    </div>
    <?php }
    $style = $this->item->onsale === '1' ? '' : 'display: none';
    
     ?>
    <div class="moderna_item_price_holder">
    <h1><span class="moderna_item_sale" style="font-size: 16px !important; <?php echo $style; ?>">WAS: </span><span id="moderna_item_price"><?php echo $this->item->f_price; ?></span></h1>
    <h1 class="moderna_item_sale" style="color: red !important; <?php echo $style; ?>">SALE: <span id="moderna_item_sale"><?php echo $this->item->f_sale; ?></span></h1>
    </div>
    <div id='moderna_item_buy_button' style="float:right"><?php echo $this->item->button; ?></div>
    </form>
</div>
<p><?php echo $this->item->mini_desc; ?></p>
</div>
</div>
</div>
</div>

<!-- ITEM TABS SWITCHER -->
<div id="moderna_tabs_holder"><?php echo $this->item->tab_html; ?></div>

<!-- ITEM TABS CONTENT -->
<div id="moderna_tabs_content">
<div class="spr_tab spr_tab_1"><?php echo $this->item->full_desc; ?></div>
<div class="spr_tab spr_tab_2" id="moderna_item_images"><center><?php foreach($this->item->images as $i) {
    echo "{$i->html}<br /><br />";
    if(strlen(trim($i->desc))>0) echo "<caption>{$i->desc}</caption><br /><br />";
} ?></center></div>
<div class="spr_tab spr_tab_3"><center><?php foreach($this->item->videos as $v) echo $v->html; ?></center></div>
<div class="spr_tab spr_tab_4"><?php foreach($this->item->faqs as $f) echo $f->html; ?></div>
<div class="spr_tab spr_tab_5">
    <div class="spr_item_attributes">
    <br /><h3><?php echo JText::_('SPR_MAINSPECIFICATIONS'); ?></h3><br />
    <?php if($this->item->tab5_active === '1') {
        echo '<table cellspacing="0" cellpadding="4" border="0">';
        foreach($this->class->attributes as $field=>$desc) {
            $value = trim($this->item->$field);
            if(strlen($value)>0 && $value !== '0') {
                if(in_array($field,array('height','width','depth'))) $value .= ' '.$this->units->size;
                elseif($field == 'weight') $value .= ' '.$this->units->weight;
                echo "<tr><td>{$desc}</td><th align='left'>{$value}</th></tr>";
            }
        }
        echo '</table>';
    } ?>
    <hr />
    </div>
    <div class="spr_item_specification"><?php echo $this->item->specification; ?></div>
</div>
</div>
<?php echo sprWidgets::_showWidgets('item'); ?>
</div>
<script>
jQuery('#moderna_tabs_content p.spr_faq_question').click(function() {
    jQuery('p.spr_faq_question').removeClass('moderna_faq_active');
    jQuery(this).addClass('moderna_faq_active');
});
</script>